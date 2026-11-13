@extends('pages.legal.layout', ['heading' => __('Terms of Use')])

@section('title', 'Terms of Use | Kedia Builders & Colonizers')
@section('draft_notice', __('Draft for the demo. Have these terms reviewed by your legal counsel before launch.'))

@section('legal')
    <h2>{{ __('About this website') }}</h2>
    <p>{{ __('This website is operated by Kedia Builders & Colonizers, Jaipur. By using it you agree to these terms.') }}</p>
    <h2>{{ __('Information on this website') }}</h2>
    <ul>
        <li>{{ __('Prices, availability, offers, images, layouts and specifications are indicative and may change without notice.') }}</li>
        <li>{{ __('Floor plans, 360° tours and sketches marked "Sample" are illustrations only.') }}</li>
        <li>{{ __('The agreement to sale, approved plans and RERA filings are the binding documents.') }}</li>
    </ul>
    <h2>{{ __('Online reservations') }}</h2>
    <p>{{ __('An online token reserves a unit provisionally. The allotment becomes final only after document verification and signing of the agreement to sale. See the Refund & Booking Policy.') }}</p>
    <h2>{{ __('Your account') }}</h2>
    <p>{{ __('You sign in with a one-time code sent to your mobile. Keep your phone secure and tell us if you lose access to your number.') }}</p>
    <h2>{{ __('Governing law') }}</h2>
    <p>{{ __('These terms are governed by the laws of India, with courts at Jaipur, Rajasthan having jurisdiction.') }}</p>
@endsection
