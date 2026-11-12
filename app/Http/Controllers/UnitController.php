<?php

namespace App\Http\Controllers;

use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Unit "product" page: gallery, floor plan, specifications, price breakup, booking.
     */
    public function show(string $slug, string $unitCode)
    {
        $unit = Unit::with('project')
            ->where('project_slug', $slug)
            ->where('unit_code', $unitCode)
            ->firstOrFail();

        $pricing = $unit->computePricing();

        $similarUnits = Unit::with('project')
            ->where('project_slug', $unit->project_slug)
            ->where('id', '!=', $unit->id)
            ->where('status', 'available')
            ->orderByRaw('ABS(total_price - ?)', [$unit->total_price])
            ->take(3)
            ->get();

        return view('pages.units.show', compact('unit', 'pricing', 'similarUnits'));
    }

    /**
     * Printable cost sheet (the browser's "Save as PDF" produces the PDF).
     */
    public function costSheet(int $unit)
    {
        $unit = Unit::with('project')->findOrFail($unit);
        $pricing = $unit->computePricing();

        return view('pages.units.cost-sheet', compact('unit', 'pricing'));
    }

    /**
     * Old /unit/{code} links: redirect when the code identifies exactly one unit.
     * Codes reused across projects are ambiguous, so those send the visitor to the catalogue.
     */
    public function legacy(string $unitCode)
    {
        $matches = Unit::where('unit_code', $unitCode)->get(['project_slug', 'unit_code']);

        if ($matches->count() === 1) {
            return redirect()->route('units.show', [$matches[0]->project_slug, $matches[0]->unit_code], 301);
        }

        if ($matches->isEmpty()) {
            abort(404);
        }

        return redirect()->route('projects.index');
    }
}
