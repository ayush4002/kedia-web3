<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
| Repairs a database that was loaded from the old UTF-16 SQL dump:
|  - restores text where characters like · ₹ — were saved as "??", using the
|    seeder data (only when the stored value is otherwise identical, so manual
|    edits are never overwritten; statuses, prices and bookings are untouched);
|  - with --baseline, creates the migrations table and marks the original four
|    migrations as already run, so `php artisan migrate` can apply new ones.
*/
Artisan::command('kedia:repair-db {--baseline : Record the original migrations as already run}', function () {
    $normalise = fn ($v) => preg_replace('/(\?+|[^\x00-\x7F]+)/u', '?', (string) $v);

    $restore = function (string $table, array $rows, callable $find) use ($normalise) {
        $fixed = 0;
        foreach ($rows as $seed) {
            $current = $find($seed);
            if (!$current) {
                continue;
            }
            $updates = [];
            foreach ($seed as $column => $value) {
                $stored = $current->{$column} ?? null;
                if (is_string($value) && is_string($stored) && str_contains($stored, '??')
                    && $stored !== $value && $normalise($stored) === $normalise($value)) {
                    $updates[$column] = $value;
                }
            }
            if ($updates) {
                DB::table($table)->where('id', $current->id)->update($updates);
                $fixed += count($updates);
            }
        }
        $this->info("{$table}: restored {$fixed} field(s).");
    };

    $restore('projects', \Database\Seeders\ProjectSeeder::data(),
        fn ($s) => DB::table('projects')->where('slug', $s['slug'])->first());
    $restore('units', \Database\Seeders\UnitSeeder::data(),
        fn ($s) => DB::table('units')->where('project_slug', $s['project_slug'])->where('unit_code', $s['unit_code'])->first());

    // projects.gallery only exists in databases built from the old dump (no seed data);
    // its only corruption is the curly apostrophe in "Kedia’s", stored as "???".
    if (Schema::hasColumn('projects', 'gallery')) {
        $n = DB::table('projects')->where('gallery', 'like', '%???%')
            ->update(['gallery' => DB::raw("REPLACE(gallery, '???', '’')")]);
        $this->info("projects.gallery: repaired {$n} row(s).");
    }

    foreach (['projects', 'units', 'bookings', 'site_visit_leads'] as $table) {
        $columns = Schema::getColumnListing($table);
        $left = DB::table($table)->where(function ($q) use ($columns) {
            foreach ($columns as $c) {
                $q->orWhere($c, 'like', '%??%');
            }
        })->count();
        if ($left) {
            $this->warn("{$table}: {$left} row(s) still contain \"??\" (no matching seed text) — check by hand.");
        }
    }

    if ($this->option('baseline')) {
        if (!Schema::hasTable('migrations')) {
            Schema::create('migrations', function ($table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });
        }
        foreach ([
            '2026_01_01_000001_create_projects_table' => 'projects',
            '2026_01_01_000002_create_units_table' => 'units',
            '2026_01_01_000003_create_bookings_table' => 'bookings',
            '2026_01_01_000004_create_site_visit_leads_table' => 'site_visit_leads',
        ] as $migration => $table) {
            if (Schema::hasTable($table) && !DB::table('migrations')->where('migration', $migration)->exists()) {
                DB::table('migrations')->insert(['migration' => $migration, 'batch' => 1]);
            }
        }
        $this->info('Baseline recorded. Now run: php artisan migrate');
    }
})->purpose('Repair "??" text corruption and baseline migrations for databases imported from the old SQL dump');
