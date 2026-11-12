<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;

class HomeController extends Controller
{
    public function index()
    {
        $withAvailable = ['units as available_units_count' => fn ($q) => $q->where('status', 'available')];

        $ongoing = Project::where('status', 'ongoing')->withCount($withAvailable)->orderBy('sort_order')->get();
        $completed = Project::where('status', 'completed')->orderBy('sort_order')->get();
        $featured = $ongoing->take(6);

        $search = [
            'locations' => Project::query()->distinct()->orderBy('location')->pluck('location'),
            'max_area' => (int) Unit::max('area_sqft'),
        ];

        $inventory = [
            'total' => Unit::count(),
            'available' => Unit::where('status', 'available')->count(),
        ];

        return view('pages.home', compact('ongoing', 'completed', 'featured', 'search', 'inventory'));
    }
}
