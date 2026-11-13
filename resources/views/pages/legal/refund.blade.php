@extends('pages.legal.layout', ['heading' => __('Refund & Booking Policy')])

@section('title', 'Refund & Booking Policy | Kedia Builders & Colonizers')
@section('draft_notice', __('Draft for the demo. Confirm the hold period, refund window and timelines with management and legal counsel before launch.'))

@section('legal')
    <h2>{{ __('Online token') }}</h2>
    <ul>
        <li>{{ __('The online token is 5% of the unit\'s all-inclusive price shown on the cost sheet.') }}</li>
        <li>{{ __('Once the token is received, the unit is held for you for :hours hours while we verify the payment and your documents.', ['hours' => config('kedia.hold_hours')]) }}</li>
        <li>{{ __('During the hold, the unit is shown as "On Hold" and cannot be booked by anyone else.') }}</li>
    </ul>
    <h2>{{ __('Confirming the allotment') }}</h2>
    <p>{{ __('A relationship manager contacts you to verify identity documents and schedule signing of the agreement to sale. The balance is payable in construction-linked milestones as per your payment schedule.') }}</p>
    <h2>{{ __('Cancellation & refunds') }}</h2>
    <ul>
        <li>{{ __('If you cancel within 15 days of paying the token, the token is refunded in full to the original payment method.') }}</li>
        <li>{{ __('If we cannot confirm the allotment for any reason, the token is refunded in full.') }}</li>
        <li>{{ __('Cancellations after signing the agreement to sale follow the terms of that agreement and RERA rules.') }}</li>
        <li>{{ __('Refunds are processed within 7–10 working days of approval.') }}</li>
    </ul>
    <h2>{{ __('Demo notice') }}</h2>
    <p>{{ __('On this demo build, payments run in test mode: no money is collected and no real refund is due.') }}</p>
@endsection
