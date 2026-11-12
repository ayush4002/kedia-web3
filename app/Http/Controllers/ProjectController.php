<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * The shop page. ?view=projects (default) lists townships; ?view=units lists
     * individual plots, villas and flats. The same filters drive both.
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => 'nullable|string|max:80',
            'view' => 'nullable|in:projects,units',
            'layout' => 'nullable|in:grid,list',
            'status' => 'nullable|in:ongoing,completed',
            'location' => 'nullable|string|max:80',
            'type' => 'nullable|in:plot,villa,apartment',
            'classification' => 'nullable|in:Residential,Commercial,Single-Family,Township',
            'bedrooms' => 'nullable|integer|min:1|max:10',
            'bathrooms' => 'nullable|integer|min:1|max:10',
            'min_area' => 'nullable|integer|min:0',
            'max_area' => 'nullable|integer|min:0',
            'min_price' => 'nullable|integer|min:0',
            'max_price' => 'nullable|integer|min:0',
            'unit_status' => 'nullable|in:available,on_hold,sold',
            'sort' => 'nullable|in:featured,price-low,price-high,newest,oldest',
        ]);

        $view = $filters['view'] ?? 'projects';
        $layout = $filters['layout'] ?? 'grid';
        $sort = $filters['sort'] ?? 'featured';

        // Constraints that live on the project
        $projectScope = function (Builder $q) use ($filters) {
            $q->when($filters['status'] ?? null, fn ($w, $v) => $w->where('status', $v))
              ->when($filters['location'] ?? null, fn ($w, $v) => $w->where('location', $v))
              ->when($filters['classification'] ?? null, fn ($w, $v) => $w->where('classification', $v));
        };

        // Constraints that live on the unit
        $unitScope = function (Builder $q) use ($filters) {
            $q->when($filters['type'] ?? null, fn ($w, $v) => $w->where('unit_type', ucfirst($v)))
              ->when($filters['bedrooms'] ?? null, fn ($w, $v) => $w->where('unit_type', '!=', 'Plot')->where('bedrooms', $v))
              ->when($filters['bathrooms'] ?? null, fn ($w, $v) => $w->where('unit_type', '!=', 'Plot')->where('bathrooms', '>=', $v))
              ->when($filters['min_area'] ?? null, fn ($w, $v) => $w->where('area_sqft', '>=', $v))
              ->when($filters['max_area'] ?? null, fn ($w, $v) => $w->where('area_sqft', '<=', $v))
              ->when($filters['min_price'] ?? null, fn ($w, $v) => $w->where('total_price', '>=', $v))
              ->when($filters['max_price'] ?? null, fn ($w, $v) => $w->where('total_price', '<=', $v))
              ->when($filters['unit_status'] ?? null, fn ($w, $v) => $w->where('status', $v));
        };
        $hasUnitFilter = (bool) array_intersect_key($filters, array_flip(['type', 'bedrooms', 'bathrooms', 'min_area', 'max_area', 'min_price', 'max_price', 'unit_status']));

        $like = isset($filters['q']) ? '%' . str_replace(['%', '_'], ['\%', '\_'], $filters['q']) . '%' : null;

        $projects = null;
        $units = null;

        if ($view === 'units') {
            $query = Unit::with('project')
                ->where($unitScope)
                ->whereHas('project', $projectScope)
                ->when($like, fn ($w) => $w->where(fn ($s) => $s->where('unit_code', 'like', $like)
                    ->orWhere('configuration', 'like', $like)->orWhere('project_name', 'like', $like)->orWhere('unit_type', 'like', $like)));

            match ($sort) {
                'price-low' => $query->orderBy('total_price'),
                'price-high' => $query->orderByDesc('total_price'),
                'newest' => $query->orderByDesc('id'),
                'oldest' => $query->orderBy('id'),
                default => $query->orderByRaw("CASE status WHEN 'available' THEN 0 WHEN 'on_hold' THEN 1 ELSE 2 END")->orderByDesc('is_featured')->orderBy('total_price'),
            };

            $units = $query->paginate(12)->withQueryString();
            $resultCount = $units->total();
        } else {
            $query = Project::query()
                ->where($projectScope)
                ->when($hasUnitFilter, fn ($w) => $w->whereHas('units', $unitScope))
                ->when($like, fn ($w) => $w->where(fn ($s) => $s->where('name', 'like', $like)
                    ->orWhere('location', 'like', $like)->orWhere('description', 'like', $like)->orWhere('type', 'like', $like)))
                ->withCount(['units', 'units as available_units_count' => fn ($q) => $q->where('status', 'available')]);

            match ($sort) {
                'price-low' => $query->orderBy('starting_price'),
                'price-high' => $query->orderByDesc('starting_price'),
                'newest' => $query->orderByDesc('id'),
                'oldest' => $query->orderBy('id'),
                default => $query->orderBy('sort_order')->orderBy('id'),
            };

            $projects = $query->get();
            $resultCount = $projects->count();
        }

        $options = [
            'locations' => Project::query()->distinct()->orderBy('location')->pluck('location'),
            'classifications' => Project::query()->distinct()->orderBy('classification')->pluck('classification'),
            'area' => ['min' => (int) Unit::min('area_sqft'), 'max' => (int) Unit::max('area_sqft')],
            'price' => ['min' => (int) Unit::min('total_price'), 'max' => (int) Unit::max('total_price')],
        ];

        $activeFilters = collect($filters)->except(['view', 'layout', 'sort'])->filter(fn ($v) => $v !== null && $v !== '');

        return view('pages.projects.index', compact('projects', 'units', 'view', 'layout', 'sort', 'filters', 'options', 'resultCount', 'activeFilters'));
    }

    /**
     * Project detail: gallery, video, map, amenities, plans, progress, RERA, brochure, units.
     */
    public function show(string $slug)
    {
        $project = Project::with(['units' => fn ($q) => $q->orderBy('unit_code')])
            ->where('slug', $slug)
            ->firstOrFail();

        $project->units->each->setRelation('project', $project);

        $unitStats = [
            'total' => $project->units->count(),
            'available' => $project->units->where('status', 'available')->count(),
            'on_hold' => $project->units->where('status', 'on_hold')->count(),
            'sold' => $project->units->where('status', 'sold')->count(),
            'min_price' => $project->units->min('total_price') ?? $project->starting_price,
            'max_price' => $project->units->max('total_price') ?? $project->starting_price,
        ];

        $relatedProjects = Project::where('id', '!=', $project->id)
            ->where('status', $project->status)
            ->withCount(['units as available_units_count' => fn ($q) => $q->where('status', 'available')])
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        return view('pages.projects.show', compact('project', 'unitStats', 'relatedProjects'));
    }
}
