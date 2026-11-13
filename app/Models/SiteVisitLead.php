<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisitLead extends Model
{
    protected $table = 'site_visit_leads';

    /** Pipeline stages shown in the admin follow-up tracker. */
    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'site_visit_scheduled' => 'Visit scheduled',
        'negotiation' => 'Negotiation',
        'won' => 'Booked',
        'closed' => 'Closed / lost',
    ];

    protected $fillable = [
        'lead_code',
        'name',
        'phone',
        'email',
        'project',
        'preferred_date',
        'preferred_time',
        'message',
        'source',
        'status',
        'assigned_advisor',
        'follow_up_at',
        'notes',
    ];

    protected $casts = [
        'follow_up_at' => 'date',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucwords(str_replace('_', ' ', (string) $this->status));
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->follow_up_at && $this->follow_up_at->isPast() && !$this->follow_up_at->isToday()
            && !in_array($this->status, ['won', 'closed'], true);
    }
}
