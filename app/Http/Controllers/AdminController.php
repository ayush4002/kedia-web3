<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Project;
use App\Models\SiteVisitLead;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Admin console (HTTP Basic Auth): overview, inventory, projects, bookings, leads.
 */
class AdminController extends Controller
{
    /* ------------------------------------------------------------------ overview */

    public function dashboard()
    {
        $inventory = [
            'total' => Unit::count(),
            'available' => Unit::where('status', 'available')->count(),
            'on_hold' => Unit::where('status', 'on_hold')->count(),
            'sold' => Unit::where('status', 'sold')->count(),
        ];

        $kpis = [
            'gmv' => (int) Unit::whereIn('status', ['sold', 'on_hold'])->sum('total_price'),
            // Only tokens the team has verified count as revenue (unverified test payments don't).
            'revenue' => (int) Booking::whereIn('status', Booking::RECEIVED_STATUSES)->sum('token_amount_paid'),
            'pending_tokens' => (int) Booking::where('status', Booking::STATUS_PENDING_VERIFICATION)->sum('token_amount_paid'),
            'bookings' => Booking::whereNotIn('status', ['cancelled', 'refunded', Booking::STATUS_AWAITING_PAYMENT])->count(),
            'leads_new' => SiteVisitLead::where('status', 'new')->count(),
            'follow_ups_due' => SiteVisitLead::whereDate('follow_up_at', '<=', today())->whereNotIn('status', ['won', 'closed'])->count(),
        ];

        // Bookings and verified token revenue for the last 6 months
        $months = collect(range(5, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));
        $bookings = Booking::where('created_at', '>=', $months->first())->get(['created_at', 'status', 'token_amount_paid']);
        $monthly = $months->map(fn (Carbon $m) => [
            'label' => $m->format('M'),
            'bookings' => $bookings->filter(fn ($b) => $b->created_at->isSameMonth($m) && $b->status !== Booking::STATUS_AWAITING_PAYMENT)->count(),
            'revenue' => (int) $bookings->filter(fn ($b) => $b->created_at->isSameMonth($m) && in_array($b->status, Booking::RECEIVED_STATUSES, true))->sum('token_amount_paid'),
        ]);

        // Inventory by project (stacked)
        $byProject = Unit::selectRaw("project_name, SUM(status = 'available') as available, SUM(status = 'on_hold') as on_hold, SUM(status = 'sold') as sold")
            ->groupBy('project_name')->orderBy('project_name')->get();

        $leadsBySource = SiteVisitLead::selectRaw('source, COUNT(*) as total')->groupBy('source')->orderByDesc('total')->get();

        $recentBookings = Booking::with(['unit', 'project'])->latest()->take(6)->get();
        $dueLeads = SiteVisitLead::whereNotIn('status', ['won', 'closed'])
            ->orderByRaw('follow_up_at IS NULL')->orderBy('follow_up_at')->latest()->take(6)->get();

        return view('pages.admin.dashboard', compact('inventory', 'kpis', 'monthly', 'byProject', 'leadsBySource', 'recentBookings', 'dueLeads'));
    }

    /* ------------------------------------------------------------------ inventory */

    public function inventory(Request $request)
    {
        $projects = Project::orderBy('name')->get(['slug', 'name']);
        $filters = $request->validate([
            'project' => 'nullable|string|max:120',
            'status' => 'nullable|in:available,on_hold,sold',
            'search' => 'nullable|string|max:60',
        ]);

        $units = Unit::with('project')
            ->when($filters['project'] ?? null, fn ($q, $v) => $q->where('project_slug', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['search'] ?? null, fn ($q, $v) => $q->where(fn ($w) => $w->where('unit_code', 'like', "%{$v}%")
                ->orWhere('unit_number', 'like', "%{$v}%")->orWhere('configuration', 'like', "%{$v}%")))
            ->orderBy('project_name')->orderBy('unit_code')
            ->paginate(25)->withQueryString();

        return view('pages.admin.inventory', compact('units', 'projects', 'filters'));
    }

    public function editUnit($id)
    {
        $unit = Unit::with('project')->findOrFail($id);
        $projects = Project::orderBy('name')->get(['slug', 'name']);

        return view('pages.admin.unit-edit', compact('unit', 'projects'));
    }

    public function updateUnit(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $validated = $request->validate([
            'unit_number' => 'required|string|max:60',
            'tower_or_block' => 'nullable|string|max:120',
            'unit_type' => 'required|in:Villa,Plot,Apartment',
            'configuration' => 'required|string|max:120',
            'bedrooms' => 'nullable|integer|min:0|max:12',
            'bathrooms' => 'nullable|integer|min:0|max:12',
            'area_sqft' => 'required|integer|min:1',
            'area_sqyd' => 'nullable|numeric|min:0',
            'facing' => 'nullable|string|max:60',
            'floor_level' => 'nullable|string|max:60',
            'status' => 'required|in:available,on_hold,sold',
            'possession' => 'nullable|string|max:60',
            'is_featured' => 'nullable|boolean',
            'total_price' => 'required|integer|min:100000',
        ]);

        $total = (int) $validated['total_price'];
        unset($validated['total_price']);
        $validated['is_featured'] = $request->boolean('is_featured');
        // Blank or missing optional fields keep their current value (the columns are NOT NULL).
        foreach (['bedrooms', 'bathrooms', 'area_sqyd', 'facing', 'floor_level'] as $optional) {
            if (($validated[$optional] ?? null) === null) {
                unset($validated[$optional]);
            }
        }

        $unit->fill($validated);
        if ($total !== (int) $unit->total_price) {
            $unit->applyTotalPrice($total);
        }
        $unit->save();

        return redirect()->route('admin.inventory', ['project' => $unit->project_slug])->with('success', "Unit {$unit->unit_code} saved.");
    }

    public function updateUnitStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:available,on_hold,sold']);

        $unit = Unit::findOrFail($id);
        $unit->status = $request->status;
        $unit->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Unit {$unit->unit_code} status updated to {$unit->status}.", 'status' => $unit->status]);
        }

        return back()->with('success', "Unit {$unit->unit_code} status updated to {$unit->status}.");
    }

    public function updateUnitPrice(Request $request, $id)
    {
        $request->validate(['price_num' => 'required|numeric|min:100000']);

        $unit = Unit::findOrFail($id);
        $unit->applyTotalPrice((int) $request->price_num);
        $unit->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Price for Unit {$unit->unit_code} updated successfully.", 'unit' => $unit]);
        }

        return back()->with('success', "Price for Unit {$unit->unit_code} updated.");
    }

    public function storeUnit(Request $request)
    {
        $validated = $request->validate([
            'project_slug' => 'required|exists:projects,slug',
            'unit_code' => [
                'required', 'string', 'max:60',
                Rule::unique('units', 'unit_code')->where('project_slug', $request->input('project_slug')),
            ],
            'unit_number' => 'nullable|string|max:60',
            'unit_type' => 'required|in:Villa,Plot,Apartment',
            'configuration' => 'nullable|string|max:120',
            'status' => 'required|in:available,on_hold,sold',
            'area_sqft' => 'required|integer|min:1',
            'area_sqyd' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'facing' => 'nullable|string|max:60',
            'total_price' => 'required|integer|min:100000',
            'floor_level' => 'nullable|string|max:60',
        ], [
            'unit_code.unique' => 'That unit code already exists in this township.',
        ]);

        $project = Project::where('slug', $validated['project_slug'])->first();
        $validated['project_name'] = $project ? $project->name : 'Kedia Township';
        $totalPrice = (int) $validated['total_price'];
        unset($validated['total_price']);

        // These columns are NOT NULL in the units table; fill sensible defaults when left blank.
        $validated['unit_number'] = $validated['unit_number'] ?? $validated['unit_code'];
        $validated['configuration'] = $validated['configuration'] ?? $validated['unit_type'];
        $validated['area_sqyd'] = $validated['area_sqyd'] ?? round($validated['area_sqft'] / 9, 2);
        foreach (['bedrooms', 'bathrooms', 'facing', 'floor_level'] as $optional) {
            if (!isset($validated[$optional])) {
                unset($validated[$optional]); // let the column default apply
            }
        }

        $unit = new Unit($validated);
        $unit->applyTotalPrice($totalPrice);
        $unit->save();

        return back()->with('success', "New unit {$unit->unit_code} created successfully.");
    }

    public function destroyUnit($id)
    {
        $unit = Unit::findOrFail($id);

        if ($unit->bookings()->whereNotIn('status', ['cancelled', 'refunded'])->exists()) {
            return back()->with('error', "Unit {$unit->unit_code} has an active booking and can't be deleted.");
        }

        $code = $unit->unit_code;
        $unit->delete();

        return back()->with('success', "Unit {$code} removed from inventory.");
    }

    /* ------------------------------------------------------------------ projects */

    public function projects()
    {
        $projects = Project::withCount(['units', 'units as available_units_count' => fn ($q) => $q->where('status', 'available')])
            ->orderBy('sort_order')->get();

        return view('pages.admin.projects', compact('projects'));
    }

    public function createProject()
    {
        return view('pages.admin.project-form', ['project' => new Project(['status' => 'ongoing', 'classification' => 'Residential'])]);
    }

    public function storeProject(Request $request)
    {
        $data = $this->validateProject($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['sort_order'] = (int) Project::max('sort_order') + 1;

        $project = Project::create($data);

        return redirect()->route('admin.projects')->with('success', "Project {$project->name} created.");
    }

    public function editProject($id)
    {
        return view('pages.admin.project-form', ['project' => Project::findOrFail($id)]);
    }

    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->update($this->validateProject($request, $project));

        // Keep the denormalised project name on units in step.
        Unit::where('project_slug', $project->slug)->update(['project_name' => $project->name]);

        return redirect()->route('admin.projects')->with('success', "Project {$project->name} saved.");
    }

    private function validateProject(Request $request, ?Project $project = null): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'type' => 'required|string|max:120',
            'classification' => 'required|in:Residential,Commercial,Single-Family,Township',
            'status' => 'required|in:ongoing,completed',
            'location' => 'required|string|max:120',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:3000',
            'rera_number' => 'nullable|string|max:60',
            'starting_price' => 'nullable|integer|min:0',
            'possession_label' => 'nullable|string|max:120',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'amenities_text' => 'nullable|string|max:2000',
        ]);

        $data['amenities'] = collect(preg_split('/\r?\n/', (string) ($data['amenities_text'] ?? '')))
            ->map(fn ($l) => trim($l))->filter()->values()->all();
        unset($data['amenities_text']);
        $data['starting_price'] = (int) ($data['starting_price'] ?? 0);
        $data['rera_verified'] = !empty($data['rera_number']);

        return $data;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'project';
        $slug = $base;
        for ($i = 2; Project::where('slug', $slug)->exists(); $i++) {
            $slug = $base . '-' . $i;
        }
        return $slug;
    }

    /* ------------------------------------------------------------------ bookings */

    public function bookings(Request $request)
    {
        $status = $request->query('status');
        $bookings = Booking::with(['unit', 'project'])
            ->when($status && array_key_exists($status, Booking::STATUSES), fn ($q) => $q->where('status', $status))
            ->latest()->paginate(20)->withQueryString();

        $counts = Booking::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('pages.admin.bookings', compact('bookings', 'counts', 'status'));
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $request->validate([
            'booking_status' => ['required', Rule::in(array_keys(Booking::STATUSES))],
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->booking_status;

        // Keep the token milestone in step with verification.
        $schedule = $booking->payment_schedule;
        if (!empty($schedule[0])) {
            $schedule[0]['status'] = match ($booking->status) {
                'confirmed', 'holding' => 'Paid',
                'refunded' => 'Refunded',
                'cancelled' => 'Cancelled',
                default => 'Pending Verification',
            };
            $booking->payment_schedule = $schedule;
        }
        $booking->save();

        // Release the unit back to inventory when a booking is cancelled or refunded.
        if (in_array($booking->status, ['cancelled', 'refunded'], true) && $booking->unit && $booking->unit->status === 'on_hold') {
            $booking->unit->update(['status' => 'available']);
        }

        return back()->with('success', "Booking #{$booking->booking_id} status changed to {$booking->status_label}.");
    }

    /* ------------------------------------------------------------------ leads */

    public function leads(Request $request)
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(array_keys(SiteVisitLead::STATUSES))],
            'advisor' => 'nullable|string|max:80',
            'due' => 'nullable|in:1',
        ]);

        $leads = SiteVisitLead::query()
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['advisor'] ?? null, fn ($q, $v) => $q->where('assigned_advisor', $v))
            ->when($filters['due'] ?? null, fn ($q) => $q->whereDate('follow_up_at', '<=', today())->whereNotIn('status', ['won', 'closed']))
            ->latest()->paginate(20)->withQueryString();

        $pipeline = SiteVisitLead::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('pages.admin.leads', compact('leads', 'pipeline', 'filters'));
    }

    public function updateLead(Request $request, $id)
    {
        $lead = SiteVisitLead::findOrFail($id);
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(SiteVisitLead::STATUSES))],
            'assigned_advisor' => ['required', Rule::in(config('kedia.advisors'))],
            'follow_up_at' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ]);

        $lead->update($data);

        return back()->with('success', "Lead {$lead->lead_code} updated.");
    }

    /* ------------------------------------------------------------------ exports */

    public function exportOrders(): StreamedResponse
    {
        $fileName = 'kedia-orders-' . date('Y-m-d-His') . '.csv';
        $bookings = Booking::with(['unit', 'project'])->latest()->get();

        // Customer-entered text could start with =, +, - or @ and run as a formula in Excel.
        $safe = fn ($v) => is_string($v) && preg_match('/^[=+\-@\t\r]/', $v) ? "'" . $v : $v;

        return response()->stream(function () use ($bookings, $safe) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Booking ID', 'Date', 'Customer Name', 'Phone', 'Email', 'Project', 'Unit Code', 'Status', 'Token (INR)', 'Total Value (INR)', 'Payment Ref']);

            foreach ($bookings as $b) {
                fputcsv($handle, array_map($safe, [
                    $b->booking_id,
                    $b->created_at->format('Y-m-d H:i'),
                    $b->customer_name,
                    $b->customer_phone,
                    $b->customer_email,
                    $b->project ? $b->project->title : $b->project_name,
                    $b->unit_code,
                    $b->status_label,
                    $b->token_amount,
                    $b->total_amount,
                    $b->transaction_ref,
                ]));
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Cache-Control' => 'no-store',
        ]);
    }

    public function exportCatalog()
    {
        $units = Unit::with('project')->get();
        return response()->json($units, 200, ['Content-Disposition' => 'attachment; filename="kedia-inventory-catalog.json"']);
    }
}
