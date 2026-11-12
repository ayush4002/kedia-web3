<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'unit_code',
        'unit_number',
        'project_slug',
        'project_name',
        'tower_or_block',
        'unit_type',
        'configuration',
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'area_sqyd',
        'facing',
        'floor_level',
        'status', // available, on_hold, sold
        'base_price',
        'development_charges',
        'club_membership',
        'parking_charges',
        'gst',
        'stamp_duty_registration',
        'total_price',
        'is_featured',
        'specifications',
        'possession'
    ];

    protected $casts = [
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'area_sqft' => 'integer',
        'area_sqyd' => 'float',
        'base_price' => 'integer',
        'development_charges' => 'integer',
        'club_membership' => 'integer',
        'parking_charges' => 'integer',
        'gst' => 'integer',
        'stamp_duty_registration' => 'integer',
        'total_price' => 'integer',
        'is_featured' => 'boolean',
        'specifications' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_slug', 'slug');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'unit_id');
    }

    // Accessor aliases for view compatibility
    public function getTitleAttribute(): string
    {
        return $this->configuration ?: ($this->bedrooms . ' BHK ' . ($this->unit_type ?? 'Villa'));
    }

    public function getPriceFormattedAttribute(): string
    {
        $price = $this->total_price ?: $this->base_price;
        return \App\Support\Format::lakhCrore($price);
    }

    public function getUrlAttribute(): string
    {
        return route('units.show', [$this->project_slug, $this->unit_code]);
    }

    /** Payload for the wishlist/compare store in site.js. */
    public function shopItem(): array
    {
        return [
            'id' => 'unit-' . $this->id,
            'unit' => $this->id,
            'type' => 'unit',
            'title' => $this->title . ' · ' . $this->unit_code,
            'url' => $this->url,
            'price' => $this->price_formatted,
            'location' => $this->project_name,
            'img' => $this->relationLoaded('project') && $this->project ? Project::media($this->project->cover_image, 600) : null,
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return ['available' => __('Available'), 'on_hold' => __('On Hold'), 'sold' => __('Sold')][$this->status] ?? ucfirst((string) $this->status);
    }

    public function getPriceNumAttribute(): int
    {
        return (int) ($this->total_price ?: $this->base_price);
    }

    public function getPlotSizeSqydAttribute(): float
    {
        return (float) $this->area_sqyd;
    }

    public function getFloorAttribute(): string
    {
        return $this->floor_level ?? 'Ground';
    }

    public function getTypeAttribute(): string
    {
        return $this->unit_type ?? 'Villa';
    }

    public const TOKEN_RATE = 0.05;

    /**
     * Itemised cost sheet built from the breakdown stored on the unit, so the
     * total always equals the listed total_price shown across the site.
     */
    public function computePricing(): array
    {
        $components = [
            'base_price' => (int) $this->base_price,
            'development_charges' => (int) $this->development_charges,
            'club_charges' => (int) $this->club_membership,
            'parking_charges' => (int) $this->parking_charges,
            'gst' => (int) $this->gst,
            'stamp_duty_estimate' => (int) $this->stamp_duty_registration,
        ];

        $sum = array_sum($components);
        $total = (int) $this->total_price ?: $sum;

        // Any gap between the stored total and its parts is shown as an adjustment
        // rather than silently changing the price the buyer saw.
        $components['other_charges'] = $total - $sum;
        $components['grand_total'] = $total;
        $components['token_amount'] = (int) round($total * self::TOKEN_RATE);

        return $components;
    }

    /**
     * Set a new all-inclusive price and rebuild the breakdown so its parts still
     * add up to it: existing parts are scaled proportionally; a unit with no
     * breakdown yet gets a standard one (no GST on plots, 5% on built units).
     */
    public function applyTotalPrice(int $total): void
    {
        $parts = ['development_charges', 'club_membership', 'parking_charges', 'gst', 'stamp_duty_registration'];
        $oldTotal = (int) $this->total_price;

        if ($oldTotal > 0 && $this->base_price > 0) {
            $ratio = $total / $oldTotal;
            foreach ($parts as $part) {
                $this->{$part} = (int) round(((int) $this->{$part}) * $ratio);
            }
        } else {
            $gstRate = $this->unit_type === 'Plot' ? 0 : 0.05;
            // total = base * (1 + 0.04 dev + gst + 0.06 stamp) + club
            $club = $this->unit_type === 'Plot' ? 35000 : 75000;
            $base = (int) round(($total - $club) / (1 + 0.04 + $gstRate + 0.06));
            $this->development_charges = (int) round($base * 0.04);
            $this->club_membership = $club;
            $this->parking_charges = 0;
            $this->gst = (int) round($base * $gstRate);
            $this->stamp_duty_registration = (int) round($base * 0.06);
        }

        $others = 0;
        foreach ($parts as $part) {
            $others += (int) $this->{$part};
        }
        $this->base_price = $total - $others;
        $this->total_price = $total;
    }
}
