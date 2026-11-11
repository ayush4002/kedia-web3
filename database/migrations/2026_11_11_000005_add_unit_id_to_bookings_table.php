<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Unit codes are only unique within a project, so linking bookings to units by
 * unit_code alone is ambiguous. Add a real unit_id and backfill it from
 * (project_slug, unit_code).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('booking_id');
            $table->index('unit_id');
        });

        self::backfill();
    }

    public static function backfill(): void
    {
        DB::statement('
            UPDATE bookings
            SET unit_id = (
                SELECT units.id FROM units
                WHERE units.project_slug = bookings.project_slug
                  AND units.unit_code = bookings.unit_code
                LIMIT 1
            )
            WHERE unit_id IS NULL
        ');
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
};
