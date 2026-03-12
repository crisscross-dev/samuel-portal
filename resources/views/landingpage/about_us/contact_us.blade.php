@extends('layouts.app-index')

@section('title', 'Contact Us')
@section('hide_footer', ' ')

@push('styles')
<style>
    .contact-page {
        font-family: inherit;
        padding: 2rem 0 3rem;
    }

    .contact-layout {
        display: flex;
        gap: 0;
        min-height: 500px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    /* Left panel */
    .contact-info {
        background: #1e3a5f;
        color: #f0f4f8;
        padding: 2.5rem 2rem;
        flex: 0 0 400px;
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .contact-info h2 {
        font-size: 1.4rem;
        font-weight: 900;
        color: #f0f4f8;
        margin-bottom: 0.5rem;
    }

    .contact-info hr {
        border: none;
        border-top: 1.5px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 1.5rem;
    }

    .contact-block {
        margin-bottom: 1.4rem;
    }

    .contact-block p {
        font-size: 0.88rem;
        color: #c8d8ea;
        line-height: 1.6;
        margin: 0 0 0.2rem;
    }

    .contact-block .label {
        font-weight: 700;
        color: #f0f4f8;
    }

    .contact-dept {
        margin-bottom: 1.25rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .contact-dept:last-of-type {
        border-bottom: none;
        margin-bottom: 0;
    }

    .contact-dept h3 {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #93c5fd;
        margin: 0 0 0.6rem;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 0.3rem;
        font-size: 0.87rem;
        color: #c8d8ea;
        line-height: 1.45;
    }

    .contact-item i {
        color: #60a5fa;
        font-size: 0.78rem;
        margin-top: 0.2rem;
        flex-shrink: 0;
        width: 14px;
        text-align: center;
    }

    .contact-item a {
        color: #c8d8ea;
        text-decoration: none;
    }

    .contact-item a:hover {
        color: #f1c40f;
        text-decoration: underline;
    }

    .contact-hours {
        margin-top: auto;
        padding-top: 1.5rem;
        border-top: 1.5px solid rgba(255, 255, 255, 0.15);
    }

    .contact-hours h3 {
        font-size: 1.1rem;
        font-weight: 800;
        color: #f0f4f8;
        margin-bottom: 0.5rem;
    }

    .contact-hours p {
        font-size: 0.92rem;
        color: #c8d8ea;
        line-height: 1.7;
        margin: 0;
    }

    .hours-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
        color: #c8d8ea;
        margin-top: 0.5rem;
    }

    .hours-table td {
        padding: 0.2rem 0.5rem 0.2rem 0;
        vertical-align: top;
    }

    .hours-table td:first-child {
        font-weight: 700;
        color: #f0f4f8;
        width: 2.8rem;
    }

    .hours-table tr.today td {
        color: #f1c40f;
        font-weight: 700;
    }

    /* Right map panel */
    .contact-map {
        flex: 1;
        min-height: 500px;
    }

    .contact-map iframe {
        width: 100%;
        height: 100%;
        min-height: 500px;
        border: 0;
        display: block;
    }

    @media (max-width: 768px) {
        .contact-layout {
            flex-direction: column;
        }

        .contact-info {
            flex: none;
        }

        .contact-map,
        .contact-map iframe {
            min-height: 350px;
        }
    }
</style>
@endpush

@section('content')
<div class="contact-page">

    <div class="contact-layout">

        <!-- Contact Info -->
        <div class="contact-info">
            <h2>Contact Us</h2>
            <hr>

            <div class="contact-block">
                <p>Brgy. Navarro, City of General Trias, Cavite</p>
                <p>Arnaldo Highway, General Trias, Cavite, Philippines</p>
            </div>

            <div class="contact-dept">
                <h3><i class="fas fa-school" style="margin-right:0.4rem"></i>Junior High School</h3>
                <div class="contact-item"><i class="fas fa-phone"></i><a href="tel:+6346402-0725">(046) 402-0725</a></div>
                <div class="contact-item"><i class="fas fa-mobile-screen-button"></i><a href="tel:+639167295830">0916 729 5830</a></div>
                <div class="contact-item"><i class="fab fa-facebook-messenger"></i><span>Scc Jhs Registrar</span></div>
                <div class="contact-item"><i class="fas fa-envelope"></i><a href="mailto:sccjhsdepartment@gmail.com">sccjhsdepartment@gmail.com</a></div>
            </div>

            <div class="contact-dept">
                <h3><i class="fas fa-graduation-cap" style="margin-right:0.4rem"></i>Senior High School</h3>
                <div class="contact-item"><i class="fas fa-phone"></i><a href="tel:+6346402-0725">(046) 402-0725</a></div>
                <div class="contact-item"><i class="fas fa-mobile-screen-button"></i><a href="tel:+639167295830">0916 729 5830</a></div>
                <div class="contact-item"><i class="fab fa-facebook-messenger"></i><span>Scc Shs Registrar</span></div>
                <div class="contact-item"><i class="fas fa-envelope"></i><a href="mailto:scc.shsregistrar@gmail.com">scc.shsregistrar@gmail.com</a></div>
            </div>

            <div class="contact-dept">
                <h3><i class="fas fa-university" style="margin-right:0.4rem"></i>College</h3>
                <div class="contact-item"><i class="fas fa-mobile-screen-button"></i><a href="tel:+639568633828">0956 863 3828</a></div>
                <div class="contact-item"><i class="fas fa-phone"></i><a href="tel:+63464569955">(046) 456-9955</a></div>
                <div class="contact-item"><i class="fas fa-envelope"></i><a href="mailto:sccgticollegedepartment@gmail.com">sccgticollegedepartment@gmail.com</a></div>
            </div>

            <div class="contact-dept">
                <h3><i class="fas fa-cash-register" style="margin-right:0.4rem"></i>Cashier</h3>
                <div class="contact-item"><i class="fas fa-phone"></i><a href="tel:+6346402-0725">(046) 402-0725</a></div>
                <div class="contact-item"><i class="fas fa-mobile-screen-button"></i><a href="tel:+639959881911">0995-988 1911</a></div>
            </div>

            <div class="contact-dept">
                <h3><i class="fas fa-heart" style="margin-right:0.4rem"></i>Guidance</h3>
                <div class="contact-item"><i class="fas fa-phone"></i><a href="tel:+63465097310">(046) 509-7310</a></div>
                <div class="contact-item"><i class="fas fa-phone"></i><a href="tel:+63465098481">(046) 509-8481</a></div>
                <div class="contact-item"><i class="fas fa-mobile-screen-button"></i><a href="tel:+639533769919">0953-376-9919</a></div>
            </div>

            <div class="contact-hours">
                <h3>Office Hours</h3>
                @php $todayDow = now()->dayOfWeek; @endphp
                <table class="hours-table">
                    <tr class="{{ $todayDow === 1 ? 'today' : '' }}">
                        <td>Mon</td>
                        <td>09:00 am – 05:00 pm</td>
                    </tr>
                    <tr class="{{ $todayDow === 2 ? 'today' : '' }}">
                        <td>Tue</td>
                        <td>09:00 am – 05:00 pm</td>
                    </tr>
                    <tr class="{{ $todayDow === 3 ? 'today' : '' }}">
                        <td>Wed</td>
                        <td>09:00 am – 05:00 pm</td>
                    </tr>
                    <tr class="{{ $todayDow === 4 ? 'today' : '' }}">
                        <td>Thu</td>
                        <td>09:00 am – 05:00 pm</td>
                    </tr>
                    <tr class="{{ $todayDow === 5 ? 'today' : '' }}">
                        <td>Fri</td>
                        <td>09:00 am – 05:00 pm</td>
                    </tr>
                    <tr class="{{ $todayDow === 6 ? 'today' : '' }}">
                        <td>Sat</td>
                        <td>Closed</td>
                    </tr>
                    <tr class="{{ $todayDow === 0 ? 'today' : '' }}">
                        <td>Sun</td>
                        <td>Closed</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Google Map -->
        <div class="contact-map">
            <iframe
                src="https://maps.google.com/maps?q=Samuel+Christian+College+of+General+Trias+Inc,+Arnaldo+Highway,+General+Trias,+Cavite,+Philippines&output=embed"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Samuel Christian College Location">
            </iframe>
        </div>

    </div>

</div>
@endsection