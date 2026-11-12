<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Unit;
use App\Models\Project;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/units', function (Request $request) {
    $query = Unit::with('project');

    // Filter by project slug (?project=kedias-landmark) or, for older clients, by project id.
    $slug = $request->query('project');
    if (!$slug && $request->filled('project_id')) {
        $slug = Project::whereKey($request->integer('project_id'))->value('slug') ?? '__none__';
    }
    if ($slug) {
        $query->where('project_slug', $slug);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    return response()->json($query->get());
});

// Numeric values are unit ids; otherwise a unit code, which needs ?project=<slug>
// when that code is used in more than one project.
Route::get('/units/{code}', function (Request $request, string $code) {
    if (ctype_digit($code)) {
        $unit = Unit::with('project')->findOrFail((int) $code);
    } else {
        $matches = Unit::with('project')
            ->where('unit_code', $code)
            ->when($request->filled('project'), fn ($q) => $q->where('project_slug', $request->query('project')))
            ->get();

        if ($matches->isEmpty()) {
            abort(404);
        }
        if ($matches->count() > 1) {
            return response()->json([
                'message' => 'Unit code is used in several projects; pass ?project=<slug>.',
                'projects' => $matches->pluck('project_slug'),
            ], 409);
        }
        $unit = $matches->first();
    }

    return response()->json([
        'unit' => $unit,
        'pricing' => $unit->computePricing(),
    ]);
});

// Global header search: projects by name/location, units by code/configuration.
Route::get('/search', function (Request $request) {
    $q = trim((string) $request->query('q', ''));
    if (mb_strlen($q) < 2) {
        return response()->json(['projects' => [], 'units' => []]);
    }
    $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';

    $projects = Project::where(fn ($w) => $w->where('name', 'like', $like)->orWhere('location', 'like', $like)->orWhere('rera_number', 'like', $like))
        ->orderBy('sort_order')->take(5)->get()
        ->map(fn ($p) => ['title' => $p->name, 'subtitle' => $p->location . ' · RERA ' . $p->rera_number, 'meta' => $p->status === 'completed' ? 'Completed' : 'Ongoing', 'url' => route('projects.show', $p->slug)]);

    $units = Unit::with('project')
        ->where(fn ($w) => $w->where('unit_code', 'like', $like)->orWhere('configuration', 'like', $like)->orWhere('unit_type', 'like', $like)->orWhere('project_name', 'like', $like))
        ->orderByRaw("CASE status WHEN 'available' THEN 0 ELSE 1 END")->take(6)->get()
        ->map(fn ($u) => ['title' => $u->title . ' · ' . $u->unit_code, 'subtitle' => $u->project_name, 'meta' => $u->price_formatted, 'url' => route('units.show', [$u->project_slug, $u->unit_code])]);

    return response()->json(['projects' => $projects, 'units' => $units]);
})->middleware('throttle:60,1');

// AI assistant: rule-based recommendations from live, available inventory.
Route::get('/recommend', function (Request $request) {
    [$min, $max] = array_map('intval', explode('-', (string) $request->query('budget', '0-999999999')) + [0, 999999999]);
    $type = in_array($request->query('type'), ['Plot', 'Villa', 'Apartment'], true) ? $request->query('type') : null;
    $location = (string) $request->query('location', '');

    $query = Unit::with('project')->where('status', 'available')
        ->whereBetween('total_price', [$min, $max])
        ->when($type, fn ($q) => $q->where('unit_type', $type))
        ->when($location !== '', fn ($q) => $q->whereHas('project', fn ($p) => $p->where('location', $location)));

    $count = (clone $query)->count();
    $units = $query->orderBy('total_price')->take(4)->get();

    $summary = $count
        ? "{$count} available " . strtolower($type ?? 'home') . ($count === 1 ? '' : 's') . ' match your budget' . ($location ? " in {$location}" : '') . '. Here are the best-value options:'
        : 'Nothing available matches exactly right now. Try a wider budget or another area — or book a visit and our team will suggest options.';

    return response()->json([
        'summary' => $summary,
        'units' => $units->map(fn ($u) => [
            'title' => $u->title . ' · ' . $u->unit_code,
            'subtitle' => $u->project_name . ' · ' . ($u->project->location ?? 'Jaipur'),
            'price' => $u->price_formatted,
            'url' => route('units.show', [$u->project_slug, $u->unit_code]),
        ]),
        'browse_url' => route('projects.index', array_filter(['view' => 'units', 'type' => $type ? strtolower($type) : null, 'location' => $location ?: null, 'min_price' => $min ?: null, 'max_price' => $max < 999999999 ? $max : null])),
    ]);
})->middleware('throttle:60,1');
