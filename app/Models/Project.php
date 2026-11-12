<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'type',
        'classification',
        'status',
        'location',
        'address',
        'description',
        'latitude',
        'longitude',
        'rera_number',
        'rera_verified',
        'price_label',
        'starting_price',
        'possession_label',
        'cover_image',
        'logo_image',
        'brochure_path',
        'sales_phone',
        'sort_order',
        'amenities',
        'configs',
        'images',
        'specs',
        'aerial_backdrop'
    ];

    protected $casts = [
        'rera_verified' => 'boolean',
        'starting_price' => 'integer',
        'amenities' => 'array',
        'configs' => 'array',
        'images' => 'array',
        'specs' => 'array',
    ];

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'project_slug', 'slug');
    }

    public function availableUnits(): HasMany
    {
        return $this->units()->where('status', 'available');
    }

    // Accessor aliases for view compatibility
    public function getTitleAttribute(): string
    {
        return $this->name ?? 'Kedia Township';
    }

    public function getHeroImageAttribute(): string
    {
        if (!empty($this->cover_image)) {
            if (str_starts_with($this->cover_image, '/') || str_starts_with($this->cover_image, 'http')) {
                return $this->cover_image;
            }
            return '/media/' . ltrim($this->cover_image, '/') . '-1920.webp';
        }
        return '/media/img/2021/04/Kedia-Nikunj-1-1920.webp';
    }

    /**
     * URL for a stored media path (e.g. "img/2025/04/DSC07902") at a given width.
     * Media is pre-rendered at 600, 900 and 1920 px.
     */
    public static function media(?string $path, int $width = 900): ?string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return null;
        }
        if (str_starts_with($path, '/') || str_starts_with($path, 'http')) {
            return $path;
        }
        $width = in_array($width, [600, 900, 1920], true) ? $width : 900;
        return '/media/' . ltrim($path, '/') . '-' . $width . '.webp';
    }

    /** Card-sized cover image. */
    public function getCardImageAttribute(): string
    {
        return self::media($this->cover_image, 900) ?? '/media/img/2021/04/Kedia-Nikunj-1-1920.webp';
    }

    /** Logo URL when one exists on disk. */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_image) {
            return null;
        }
        foreach (['480', '600', '900'] as $w) {
            $rel = 'media/' . ltrim($this->logo_image, '/') . '-' . $w . '.webp';
            if (is_file(public_path($rel))) {
                return '/' . $rel;
            }
        }
        return null;
    }

    /** Gallery images as [src, thumb, caption]. */
    public function galleryImages(): array
    {
        return collect($this->images ?? [])
            ->filter(fn ($img) => !empty($img['path']))
            ->map(fn ($img) => [
                'src' => self::media($img['path'], 1920),
                'thumb' => self::media($img['path'], 600),
                'caption' => $img['caption'] ?? $this->name,
            ])->values()->all();
    }

    /** Drone flyover video for this project, if one exists. */
    public function getVideoUrlAttribute(): ?string
    {
        $file = 'media/video/' . $this->slug . '-flyover.mp4';
        return is_file(public_path($file)) ? '/' . $file : null;
    }

    /** Payload for the wishlist/compare store in site.js. */
    public function shopItem(): array
    {
        return [
            'id' => 'project-' . $this->slug,
            'type' => 'project',
            'title' => $this->name,
            'url' => route('projects.show', $this->slug),
            'price' => $this->price_range,
            'location' => $this->location,
            'img' => self::media($this->cover_image, 600),
        ];
    }

    public function getPriceRangeAttribute(): string
    {
        if (!empty($this->starting_price) && $this->starting_price > 0) {
            return 'Starting at ' . \App\Support\Format::lakhCrore($this->starting_price) . '*';
        }
        if (!empty($this->price_label)) {
            // Seed labels are written "₹24.80"; add the space used elsewhere on the site.
            return preg_replace('/₹\s*/u', '₹ ', $this->price_label);
        }
        return 'Price on Request';
    }

    /**
     * Public URL of the project's brochure PDF, or null when the file isn't on disk.
     */
    public function getBrochureUrlAttribute(): ?string
    {
        $path = ltrim((string) $this->brochure_path, '/');
        if ($path === '' || !is_file(public_path('media/' . $path))) {
            return null;
        }
        return '/media/' . $path;
    }

    public function getPriceStartNumAttribute(): int
    {
        return (int) $this->starting_price;
    }

    public function getIsFeaturedAttribute(): bool
    {
        return $this->sort_order <= 3;
    }
}
