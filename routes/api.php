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
