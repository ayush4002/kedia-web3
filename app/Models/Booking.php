<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    public const STATUS_PENDING_VERIFICATION = 'pending_verification';

    /** Statuses an admin can set, with display labels. */
    public const STATUSES = [
        'pending_verification' => 'Pending Verification',
        'confirmed' => 'Confirmed',
        'holding' => 'Holding',
        'cancelled' => 'Cancelled',
        'refunded' => 'Refunded',
    ];

    /** Statuses whose token counts as money actually received. */
    public const RECEIVED_STATUSES = ['confirmed', 'holding', 'agreement_pending', 'allotted', 'registry_complete'];

    protected $fillable = [
        'booking_id',
        'unit_id',
        'unit_code',
        'unit_number',
        'project_slug',
        'project_name',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_pan',
        'customer_aadhar',
        'address',
        'booking_date',
        'token_amount_paid',
        'total_unit_amount',
        'payment_method',
        'payment_reference',
        'status', // confirmed, pending_verification, agreement_pending, allotted, registry_complete, cancelled
        'payment_schedule',
        'cost_breakdown'
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'token_amount_paid' => 'integer',
        'total_unit_amount' => 'integer',
        'payment_schedule' => 'array',
        'cost_breakdown' => 'array',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_slug', 'slug');
    }

    // Accessors for view compatibility
    public function getTokenAmountAttribute(): int
    {
        return (int) $this->token_amount_paid;
    }

    public function getTotalAmountAttribute(): int
    {
        return (int) $this->total_unit_amount;
    }

    public function getBookingStatusAttribute(): string
    {
        return $this->status ?? 'confirmed';
    }

    public function getTransactionRefAttribute(): ?string
    {
        return $this->payment_reference;
    }

    public function getPaymentStatusAttribute(): string
    {
        return in_array($this->status, self::RECEIVED_STATUSES, true) ? 'paid' : 'pending';
    }

    /**
     * The voucher shows the buyer's personal details, so it is only reachable
     * through a signed link (handed to the buyer, the dashboard lookup and admin).
     */
    public function voucherUrl(): string
    {
        return \Illuminate\Support\Facades\URL::signedRoute('booking.confirmation', ['bookingId' => $this->booking_id]);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucwords(str_replace('_', ' ', (string) $this->status));
    }
}
