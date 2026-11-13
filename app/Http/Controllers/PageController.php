<?php

namespace App\Http\Controllers;

use App\Models\Project;

class PageController extends Controller
{
    /** Our story: editorial page with the heritage timeline. */
    public function about()
    {
        $projects = Project::orderBy('sort_order')->get(['slug', 'name', 'location', 'status', 'cover_image']);

        return view('pages.about.story', compact('projects'));
    }

    public function chairman()
    {
        return view('pages.about.chairman');
    }

    public function vision()
    {
        return view('pages.about.vision');
    }

    public function why()
    {
        return view('pages.about.why');
    }

    public function gallery()
    {
        // Project photos come from the projects themselves; events, press and CSR from config.
        $items = collect(config('kedia.gallery'));
        foreach (Project::orderBy('sort_order')->get() as $project) {
            foreach (array_slice($project->galleryImages(), 0, 3) as $img) {
                $items->push(['category' => 'Project', 'src' => $img['src'], 'thumb' => $img['thumb'], 'caption' => $img['caption'], 'project' => $project->name]);
            }
        }
        $items = $items->unique('src')->values();
        $categories = ['Project', 'Event', 'Press Release', 'CSR'];

        return view('pages.gallery', compact('items', 'categories'));
    }

    public function nri()
    {
        $projects = Project::where('status', 'ongoing')->orderBy('sort_order')->get(['slug', 'name', 'location']);

        return view('pages.nri', compact('projects'));
    }

    public function careers()
    {
        return view('pages.careers');
    }

    public function contact()
    {
        $projects = Project::where('status', 'ongoing')->orderBy('sort_order')->get(['slug', 'name']);

        return view('pages.contact', compact('projects'));
    }

    /** RERA disclosures: every project with its registration number. */
    public function legal()
    {
        $projects = Project::orderBy('sort_order')->get(['slug', 'name', 'location', 'status', 'rera_number']);

        return view('pages.legal.rera', compact('projects'));
    }

    public function privacy()
    {
        return view('pages.legal.privacy');
    }

    public function terms()
    {
        return view('pages.legal.terms');
    }

    public function refund()
    {
        return view('pages.legal.refund');
    }
}
