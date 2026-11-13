@extends('pages.legal.layout', ['heading' => __('Privacy Policy')])

@section('title', 'Privacy Policy | Kedia Builders & Colonizers')
@section('draft_notice', __('Draft for the demo. Have this reviewed by your legal counsel (including against the Digital Personal Data Protection Act, 2023) before launch.'))

@section('legal')
    <h2>{{ __('What we collect') }}</h2>
    <ul>
        <li>{{ __('Contact details you give us: name, mobile number, email and city.') }}</li>
        <li>{{ __('Booking details: the unit you reserve, payment references, and identity details you choose to share (PAN, last four digits of Aadhaar).') }}</li>
        <li>{{ __('Your shortlist, comparison list and recently viewed items are stored only in your browser, not on our servers.') }}</li>
    </ul>
    <h2>{{ __('How we use it') }}</h2>
    <ul>
        <li>{{ __('To respond to enquiries, schedule visits and process bookings.') }}</li>
        <li>{{ __('To send payment reminders, receipts and construction updates for your unit.') }}</li>
        <li>{{ __('To meet legal and regulatory obligations, including RERA and tax requirements.') }}</li>
    </ul>
    <h2>{{ __('Sharing') }}</h2>
    <p>{{ __('We share data only with service providers who help us run the website, payments and messaging, with banks you ask us to coordinate with for a home loan, and with authorities where the law requires it. We do not sell personal data.') }}</p>
    <h2>{{ __('Your choices') }}</h2>
    <p>{{ __('You can ask us to access, correct or delete your personal data, or to stop marketing messages, by writing to us at the address below.') }}</p>
    <h2>{{ __('Retention & security') }}</h2>
    <p>{{ __('We keep booking records for as long as the law requires and enquiry data for as long as it is useful to serve you. Access is limited to staff who need it.') }}</p>
@endsection
