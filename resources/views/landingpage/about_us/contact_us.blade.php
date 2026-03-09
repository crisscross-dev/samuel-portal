@extends('layouts.app-index')

@section('title', 'Contact Us')

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
        flex: 0 0 360px;
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
        font-size: 0.92rem;
        color: #c8d8ea;
        line-height: 1.7;
        margin: 0;
    }

    .contact-block p a {
        color: #f1c40f;
        text-decoration: none;
    }

    .contact-block p a:hover {
        text-decoration: underline;
    }

    .contact-block .label {
        font-weight: 700;
        color: #f0f4f8;
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
                <p>Address: Brgy. Navarro, City of General Trias, Cavite</p>
            </div>

            <div class="contact-block">
                <p>Samuel Christian College, Arnaldo Highway, General Trias, Cavite, Philippines</p>
            </div>

            <div class="contact-block">
                <p><span class="label">Contact No.:</span> <a href="tel:+6346402-0725">(046) 402-0725</a></p>
                <p><span class="label">Email:</span> <a href="mailto:samuelchristiancollegegti@gmail.com">samuelchristiancollegegti@gmail.com</a></p>
            </div>

            <div class="contact-block">
                <p><span class="label">Contact No.:</span> <a href="tel:+6346456-9955">(046) 456-9955</a> / <a href="tel:+639167295830">09167295830</a></p>
                <p><span class="label">Email:</span> <a href="mailto:sccgticollegedepartment@gmail.com">sccgticollegedepartment@gmail.com</a></p>
            </div>

            <div class="contact-block">
                <p>For more inquiries and concerns, kindly send us a message or call.</p>
            </div>

            <div class="contact-hours">
                <h3>Office Hours</h3>
                <table class="hours-table">
                    <tr class="today">
                        <td>Mon</td>
                        <td>09:00 am – 05:00 pm</td>
                    </tr>
                    <tr>
                        <td>Tue</td>
                        <td>09:00 am – 05:00 pm</td>
                    </tr>
                    <tr>
                        <td>Wed</td>
                        <td>09:00 am – 05:00 pm</td>
                    </tr>
                    <tr>
                        <td>Thu</td>
                        <td>09:00 am – 05:00 pm</td>
                    </tr>
                    <tr>
                        <td>Fri</td>
                        <td>09:00 am – 05:00 pm</td>
                    </tr>
                    <tr>
                        <td>Sat</td>
                        <td>Closed</td>
                    </tr>
                    <tr>
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