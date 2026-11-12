<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * About Kedia Builders & Colonizers (Founders, Vision, Mission, Why Invest).
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Visual Archives & Project Gallery.
     */
    public function gallery()
    {
        $projects = Project::all();
        return view('pages.gallery', compact('projects'));
    }

    /**
     * Dedicated NRI Investment Desk.
     */
    public function nri()
    {
        return view('pages.nri');
    }

    /**
     * Careers at Kedia Builders.
     */
    public function careers()
    {
        return view('pages.careers');
    }

    /**
     * Contact Headquarters & Site Offices.
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Legal, RERA & Privacy Disclosures.
     */
    public function legal()
    {
        return view('pages.legal');
    }
}
