<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;

class HomeController extends Controller
{
    public function index()
    {
        // Featured Developments
        $featuredProjects = Project::whereIn('slug', [
            'kedias-landmark',
            'nikunj-villas',
            'kedias-atulyam',
            'kedias-tulsi',
            'jagannathpuri',
            'kedia-aravali'
        ])->get();

        if ($featuredProjects->isEmpty()) {
            $featuredProjects = Project::take(6)->get();
        }

        // The homepage inventory strip shows six units.
        $units = Unit::orderBy('id')->take(6)->get();

        return view('pages.home', compact('featuredProjects', 'units'));
    }
}
