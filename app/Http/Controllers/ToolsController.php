<?php

namespace App\Http\Controllers;

use App\Models\Unit;

class ToolsController extends Controller
{
    /**
     * EMI calculator, price calculator and home-loan enquiry.
     * The price calculator uses the average all-inclusive rate per sq ft of each
     * project's listed units, by unit type.
     */
    public function index()
    {
        $rates = Unit::query()
            ->selectRaw('project_slug, project_name, unit_type, ROUND(SUM(total_price) / NULLIF(SUM(area_sqft), 0)) as rate, MIN(area_sqft) as min_area, MAX(area_sqft) as max_area')
            ->groupBy('project_slug', 'project_name', 'unit_type')
            ->orderBy('project_name')
            ->get()
            ->filter(fn ($r) => $r->rate > 0)
            ->map(fn ($r) => [
                'key' => $r->project_slug . '|' . $r->unit_type,
                'label' => $r->project_name . ' · ' . $r->unit_type,
                'rate' => (int) $r->rate,
                'min' => (int) $r->min_area,
                'max' => (int) $r->max_area,
            ])->values();

        return view('pages.tools', compact('rates'));
    }
}
