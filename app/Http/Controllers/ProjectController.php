<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a paginated listing of projects with dynamic filters and sorting.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Filter by Status (ongoing, completed, all)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Property Type (villa, apartment, plot, commercial, all)
        if ($request->filled('type') && $request->type !== 'all') {
            $t = $request->type;
            $query->where('type', 'like', "%{$t}%");
        }

        // Filter by Location / Search query
        if ($request->filled('location')) {
            $loc = $request->location;
            $query->where(function ($q) use ($loc) {
                $q->where('location', 'like', "%{$loc}%")
                  ->orWhere('name', 'like', "%{$loc}%")
                  ->orWhere('description', 'like', "%{$loc}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('starting_price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('starting_price', 'desc');
                break;
            case 'newest':
                $query->orderBy('id', 'desc');
                break;
            case 'featured':
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
                break;
        }

        $projects = $query->withCount([
            'units',
            'units as available_units_count' => function ($q) {
                $q->where('status', 'available');
            }
        ])->get();

        // Compute summary counts for filters
        $stats = [
            'total' => Project::count(),
            'ongoing' => Project::where('status', 'ongoing')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'villas' => Project::where('type', 'like', '%villa%')->count(),
            'apartments' => Project::where('type', 'like', '%apartment%')->count(),
            'plots' => Project::where('type', 'like', '%plot%')->count(),
        ];

        return view('pages.projects.index', compact('projects', 'stats'));
    }

    /**
     * Display the specified project with gallery, units inventory, and interactive blueprint.
     */
    public function show($slug)
    {
        $project = Project::with(['units' => function ($q) {
            $q->orderBy('unit_code', 'asc');
        }])->where('slug', $slug)->firstOrFail();

        // Summary of project units
        $unitStats = [
            'total' => $project->units->count(),
            'available' => $project->units->where('status', 'available')->count(),
            'on_hold' => $project->units->where('status', 'on_hold')->count(),
            'sold' => $project->units->where('status', 'sold')->count(),
            'min_price' => $project->units->min('total_price') ?? $project->starting_price,
            'max_price' => $project->units->max('total_price') ?? ($project->starting_price * 1.5),
        ];

        $relatedProjects = Project::where('id', '!=', $project->id)
            ->where(function ($q) use ($project) {
                $q->where('type', $project->type)
                  ->orWhere('status', $project->status);
            })
            ->take(3)
            ->get();

        return view('pages.projects.show', compact('project', 'unitStats', 'relatedProjects'));
    }
}
