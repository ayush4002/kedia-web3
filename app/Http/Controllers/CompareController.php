<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    /**
     * Side-by-side comparison of up to three units: /compare?units=unit-12,unit-40
     * (the ids come from the compare tray in site.js).
     */
    public function index(Request $request)
    {
        $ids = collect(explode(',', (string) $request->query('units', '')))
            ->map(fn ($v) => (int) preg_replace('/\D+/', '', $v))
            ->filter()
            ->unique()
            ->take(3)
            ->values();

        $units = $ids->isEmpty()
            ? collect()
            : Unit::with('project')->whereIn('id', $ids)->get()->sortBy(fn ($u) => $ids->search($u->id))->values();

        $pricing = $units->mapWithKeys(fn ($u) => [$u->id => $u->computePricing()]);

        return view('pages.compare', compact('units', 'pricing'));
    }
}
