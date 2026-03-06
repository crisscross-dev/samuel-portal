@extends('layouts.app-index')

@section('title', 'Junior High School - SCC Portal')

@push('styles')
<style>
    /* ── JHS Page ─────────────────────────────────────── */
    .jhs-page {
        font-family: inherit;
    }

    /* Hero Banner */
    .jhs-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #1a5276 60%, #0e6655 100%);
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 260px;
        padding: 2rem 2.5rem;
        gap: 1.5rem;
        position: relative;
    }

    .jhs-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url('{{ asset("images/background.png") }}') center/cover no-repeat;
        opacity: 0.08;
    }

    .jhs-hero-text {
        position: relative;
        z-index: 1;
        color: #fff;
    }

    .jhs-hero-text .enrol-label {
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: inline-block;
        padding: 0.2rem 0.9rem;
        border-radius: 4px;
        margin-bottom: 0.5rem;
    }

    .jhs-hero-text h2 {
        font-size: clamp(1.8rem, 4vw, 2.8rem);
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
    }

    .jhs-hero-text h2 span {
        color: #f1c40f;
    }

    .jhs-hero-text .grade-badge {
        font-size: 0.95rem;
        font-weight: 600;
        background: rgba(241, 196, 15, 0.2);
        border: 1px solid #f1c40f;
        color: #f1c40f;
        display: inline-block;
        padding: 0.25rem 1rem;
        border-radius: 6px;
        margin-bottom: 1.25rem;
    }

    .jhs-enrol-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #f1c40f;
        color: #1e3a5f;
        font-weight: 800;
        font-size: 0.95rem;
        padding: 0.6rem 1.6rem;
        border-radius: 8px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: background 0.2s, transform 0.15s;
        box-shadow: 0 4px 14px rgba(241, 196, 15, 0.35);
    }

    .jhs-enrol-btn:hover {
        background: #d4ac0d;
        color: #1e3a5f;
        transform: translateY(-1px);
    }

    .jhs-hero-image {
        position: relative;
        z-index: 1;
        flex-shrink: 0;
        display: flex;
        align-items: flex-end;
    }

    .jhs-hero-image img {
        height: 240px;
        object-fit: contain;
        filter: drop-shadow(0 4px 16px rgba(0, 0, 0, 0.3));
    }

    /* Section Dividers & Titles */
    .jhs-section-title {
        text-align: center;
        font-size: clamp(1.1rem, 2vw, 1.4rem);
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #ffffff;
        padding: 1.5rem 0;
        position: relative;
    }

    .jhs-section-title::after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background: #f1c40f;
        margin: 0.5rem auto 0;
        border-radius: 2px;
    }

    /* Why Enroll section */
    .jhs-why {
        background: #eaf6fb;
        border-radius: 12px;
        padding: 2rem 2.5rem;
    }

    .jhs-why-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1rem;
        margin-top: 1.25rem;
    }

    .jhs-why-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        background: #fff;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        box-shadow: 0 2px 8px rgba(30, 58, 95, 0.07);
        font-size: 0.92rem;
        color: #374151;
        line-height: 1.5;
    }

    .jhs-why-item .why-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #1e3a5f, #1a5276);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #f1c40f;
        font-size: 1rem;
    }

    /* Video Section */
    .jhs-video-section {
        display: flex;
        gap: 2rem;
        align-items: center;
        background: #fff;
        border-radius: 12px;
        padding: 2rem 2.5rem;
        box-shadow: 0 2px 12px rgba(30, 58, 95, 0.07);
    }

    .jhs-video-wrapper {
        flex: 1;
        min-width: 0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        aspect-ratio: 16/9;
    }

    .jhs-video-wrapper iframe {
        width: 100%;
        height: 100%;
        border: none;
        display: block;
    }

    .jhs-video-text {
        flex: 1;
        min-width: 0;
    }

    .jhs-video-text h3 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e3a5f;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    .jhs-video-text p {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.65;
    }

    /* Features dark section */
    .jhs-features {
        background: linear-gradient(135deg, #1e3a5f 0%, #0d2137 100%);
        border-radius: 12px;
        padding: 2.5rem 3rem;
        color: #fff;
    }

    .jhs-features h3 {
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #f1c40f;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .jhs-features ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 0.85rem;
    }

    .jhs-features ul li {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        font-size: 0.92rem;
        line-height: 1.55;
        color: rgba(255, 255, 255, 0.88);
    }

    .jhs-features ul li i {
        color: #f1c40f;
        margin-top: 0.2rem;
        flex-shrink: 0;
    }

    /* CTA bottom */
    .jhs-cta-bottom {
        text-align: center;
        padding: 2rem 1rem;
    }

    /* Requirements Section */
    .jhs-requirements {
        background: linear-gradient(135deg, #0d2137 0%, #1e3a5f 100%);
        border-radius: 12px;
        padding: 2.5rem 3rem;
        color: #fff;
        margin-bottom: 1.5rem;
        margin-top: 1.5rem;
    }

    .jhs-requirements h2 {
        font-size: clamp(1.4rem, 3vw, 2rem);
        font-weight: 900;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .jhs-requirements hr {
        border-color: rgba(255, 255, 255, 0.2);
        margin-bottom: 1.75rem;
    }

    .jhs-req-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .jhs-req-col h4 {
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #e74c3c;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .jhs-req-col h4 i {
        color: #e74c3c;
        font-size: 0.9rem;
    }

    .jhs-req-col ul {
        list-style: disc;
        padding-left: 1.25rem;
        margin: 0;
    }

    .jhs-req-col ul li {
        font-size: 0.92rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.88);
        margin-bottom: 0.4rem;
    }

    .jhs-req-col ul li strong {
        color: #fff;
        font-weight: 700;
    }

    @media (max-width: 600px) {
        .jhs-req-grid {
            grid-template-columns: 1fr;
        }

        .jhs-requirements {
            padding: 1.75rem 1.5rem;
        }
    }

    .jhs-cta-bottom h3 {
        font-size: clamp(1.2rem, 2.5vw, 1.6rem);
        font-weight: 800;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .jhs-cta-bottom p {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    /* Image Display */
    .jhs-image-display {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18);
        margin-bottom: 1.5rem;
        width: 100%;
        margin-top: 1.5rem;
    }

    .jhs-image-display img {
        width: 100%;
        display: block;
    }
</style>
@endpush

@section('content')
<div class="jhs-page">

    <!-- Page Title -->
    <h2 class="jhs-section-title" style="padding-top:2rem;">Junior High School <span style="color:#f1c40f;">(Grade 7 to 10)</span></h2>
    <hr style="border-color:rgba(255,255,255,0.2); margin-bottom:1.5rem;">

    <!-- Hero Banner -->
    <div class="jhs-hero mb-4">
        <div class="jhs-hero-text">
            <div class="enrol-label">Now Enrolling</div>
            <h2>High School<br><span>Enrolment</span></h2>
            <div class="grade-badge"><i class="fas fa-graduation-cap me-1"></i> Grade 7 to 10</div>
            <br>
            <a href="{{ route('admission.apply') }}" class="jhs-enrol-btn">
                <i class="fas fa-pen-to-square"></i> Enroll Now
            </a>
        </div>
        <div class="jhs-hero-text" style="text-align:right; flex-shrink:0;">
            <p style="font-size:clamp(1.4rem,3vw,2rem); font-weight:900; text-transform:uppercase; line-height:1.2; color:#fff;">
                Junior<br><span style="color:#f1c40f;">High School</span>
            </p>
            <p style="color:rgba(255,255,255,0.7); font-size:0.95rem; margin-top:0.25rem;">Samuel Christian College G.T.I.</p>
        </div>
    </div>

    <!-- Campus Image -->
    <div class="jhs-image-display">
        <img src="{{ asset('images/highschool_step_enrollment.webp') }}" alt="SCC Campus">
    </div>

    <!-- Why Enroll Section -->
    <div class="jhs-section-title">Why Enroll at SCCGTI?</div>
    <div class="jhs-why mb-4">
        <div class="jhs-why-grid">
            <div class="jhs-why-item">
                <div class="why-icon"><i class="fas fa-book-open"></i></div>
                <div>Enhanced Curriculum that exceeds the minimum standard of the Department of Education, aligned with the New Normal Education System.</div>
            </div>
            <div class="jhs-why-item">
                <div class="why-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div>Highly Competent Teachers and Administrators with eligibility, master's and doctorate degrees, professional licenses, and recognitions.</div>
            </div>
            <div class="jhs-why-item">
                <div class="why-icon"><i class="fas fa-wifi"></i></div>
                <div>High-Speed Internet Connection to ensure the smooth flow of classes.</div>
            </div>
            <div class="jhs-why-item">
                <div class="why-icon"><i class="fas fa-laptop"></i></div>
                <div>Interactive Online Class wherein students are engaged during the teaching and learning process.</div>
            </div>
            <div class="jhs-why-item">
                <div class="why-icon"><i class="fas fa-heart"></i></div>
                <div>Practical Guidance and Counselling Services such as mental health activities and counseling.</div>
            </div>
            <div class="jhs-why-item">
                <div class="why-icon"><i class="fas fa-peso-sign"></i></div>
                <div>Affordable Tuition Fees and reasonable miscellaneous.</div>
            </div>
            <div class="jhs-why-item">
                <div class="why-icon"><i class="fas fa-people-group"></i></div>
                <div>Family Atmosphere of love, respect, and harmony.</div>
            </div>
            <div class="jhs-why-item">
                <div class="why-icon"><i class="fas fa-shield-heart"></i></div>
                <div>Health Protocol Implementation as mandated by the Department of Health.</div>
            </div>
        </div>
    </div>

    <!-- What is SCC Video Section -->
    <div class="jhs-section-title">What is SCC?</div>
    <div class="jhs-video-section mb-4">
        <div class="jhs-video-wrapper">
            <iframe src="https://sccportal.com/junior_promotion.mp4"
                allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title="What is SCC?">
            </iframe>
        </div>
        <div class="jhs-video-text">
            <h3>Samuel Christian College<br>of General Trias, Inc.</h3>
            <p>
                SCC is established on 2012 and located at Navarro, City of General Trias, Cavite.
                It provides quality, affordable, and values-based education for Junior High School,
                Senior High School, and College students.
            </p>
            <p class="mt-3">
                Guided by Christian values and a commitment to academic excellence, SCC continuously
                evolves its programs to meet the demands of the 21st Century learner.
            </p>
            <a href="{{ route('admission.apply') }}" class="jhs-enrol-btn mt-3 d-inline-flex">
                <i class="fas fa-pen-to-square"></i> Apply Now
            </a>
        </div>
    </div>

    <!-- Features Dark Section -->
    <div class="jhs-features mb-4">
        <h3><i class="fas fa-star me-2"></i>What SCCGTI Offers for Junior High School</h3>
        <ul>
            <li><i class="fas fa-check-circle"></i> Complete Grade 7 to Grade 10 program following DepEd standards</li>
            <li><i class="fas fa-check-circle"></i> Homeroom Guidance and Values Formation classes</li>
            <li><i class="fas fa-check-circle"></i> TLE (Technology and Livelihood Education) specialization tracks</li>
            <li><i class="fas fa-check-circle"></i> Active student organizations and clubs for holistic development</li>
            <li><i class="fas fa-check-circle"></i> Regular sports, arts, and academic competitions</li>
            <li><i class="fas fa-check-circle"></i> Modern classrooms with projectors and learning tools</li>
            <li><i class="fas fa-check-circle"></i> Safe and nurturing campus environment</li>
            <li><i class="fas fa-check-circle"></i> Online and face-to-face learning modalities available</li>
        </ul>
    </div>

    <!-- Requirements Section -->
    <div class="jhs-requirements">
        <h2>Requirements:</h2>
        <hr>
        <div class="jhs-req-grid">
            <!-- New Student -->
            <div class="jhs-req-col">
                <h4><i class="fas fa-thumbtack"></i> New Student</h4>
                <ul>
                    <li><strong>Accomplished Pre-Enrolment Form</strong></li>
                    <li><strong>Accomplished Student Health Form</strong> (with Parent/Guardian's Signature)</li>
                    <li><strong>Original Report Card (SF9)</strong></li>
                    <li><strong>1x1 ID Picture</strong></li>
                </ul>
            </div>
            <!-- Old Student -->
            <div class="jhs-req-col">
                <h4><i class="fas fa-thumbtack"></i> Old Student</h4>
                <ul>
                    <li><strong>Report Card (SF9)</strong></li>
                    <li><strong>Accomplished Pre-Enrolment Form</strong></li>
                    <li><strong>Accomplished Health Consent Form</strong> (with Parent/Guardian's Signature)</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="jhs-image-display">
        <img src="{{ asset('images/g7_esc_requirements.webp') }}" alt="SCC Campus">
    </div>

    <!-- Requirements Note -->
    <div class="mb-4" style="background:#f0f0f0; border-radius:12px; padding:1.75rem 2.25rem; margin-bottom:1.5rem;">
        <p style="color:#c0392b; font-size:0.95rem; line-height:1.7; margin-bottom:1rem;">
            Download and print the attached <strong>Student Health Form or Consent Form</strong>.
            Fill out (accomplish) the form completely, then have your <strong>parent or guardian sign it.</strong>
        </p>
        <p style="color:#c0392b; font-size:0.95rem; line-height:1.7; margin-bottom:0;">
            <strong>*INCOMPLETE REQUIREMENTS</strong> will not be accommodated during the enrolment.*
        </p>
    </div>

    <!-- CTA Bottom -->
    <div class="jhs-features mb-4" style="text-align:center; padding:2.5rem;">
        <h3 style="font-size:1.4rem; margin-bottom:0.5rem;"><i class="fas fa-graduation-cap me-2"></i>Ready to Join SCCGTI?</h3>
        <p style="color:rgba(255,255,255,0.75); margin-bottom:1.5rem; font-size:0.95rem;">
            Start your application today and be part of the Samuelian family.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('admission.apply') }}" class="jhs-enrol-btn">
                <i class="fas fa-pen-to-square"></i> Apply for Admission
            </a>
            <a href="{{ route('admission.track') }}" class="jhs-enrol-btn" style="background:transparent; border:2px solid #f1c40f; color:#f1c40f; box-shadow:none;">
                <i class="fas fa-magnifying-glass"></i> Track Application
            </a>
        </div>
    </div>

</div>
@endsection