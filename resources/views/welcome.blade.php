@extends('layouts.app-index')

@section('title', 'SCC Portal')

@push('styles')

<style>
    /* ══════════════════════════════════════════
   Homepage Sections
   ══════════════════════════════════════════ */

    /* ── Info Sections (Commitment / Academics) ── */
    .hp-info-section {
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .hp-info-inner {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 320px;
    }

    .hp-info-img-side {
        overflow: hidden;
    }

    .hp-info-img-side img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .hp-info-text-side {
        padding: clamp(1.5rem, 3vw, 2.5rem);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .hp-info-text-side--light {
        background: #ffffff;
    }

    .hp-info-text-side--dark {
        background: #0d1f3c;
    }

    .hp-info-title {
        font-size: clamp(1.25rem, 2.4vw, 1.75rem);
        font-weight: 700;
        color: #0d1f3c;
        margin-bottom: 0.9rem;
        line-height: 1.25;
    }

    .hp-info-title--white {
        color: #ffffff;
    }

    .hp-info-desc {
        font-size: clamp(0.88rem, 1.35vw, 0.98rem);
        color: #374151;
        line-height: 1.7;
        margin-bottom: 0;
    }

    .hp-info-desc--white {
        color: rgba(255, 255, 255, 0.85);
    }

    .hp-btn-outline {
        margin-top: 1.25rem;
        align-self: flex-start;
        display: inline-block;
        padding: 0.6rem 1.4rem;
        border: 2px solid #ffffff;
        border-radius: 10px;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: background 0.2s ease;
    }

    .hp-btn-outline:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
    }

    /* ── Shared Card Section Shell ── */
    .hp-card-section {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }

    .hp-section-heading {
        font-size: clamp(0.95rem, 1.8vw, 1.15rem);
        font-weight: 700;
        color: #0d1f3c;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.4rem;
    }

    .hp-section-divider {
        border: none;
        border-top: 2px solid #0d1f3c;
        margin: 0 0 1.25rem;
    }

    /* ── Facebook Embed ── */
    .hp-fb-embed {
        display: flex;
        justify-content: center;
    }

    .hp-fb-embed iframe {
        border: none;
        display: block;
    }

    /* ── News Center Carousel ── */
    .hp-news-carousel-wrapper {
        position: relative;
        overflow: hidden;
        padding: 16px 0;
    }

    .hp-news-carousel {
        overflow: visible;
    }

    .hp-news-track {
        display: flex;
        gap: 1.25rem;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
        align-items: center;
    }

    .hp-news-card {
        flex-shrink: 0;
        width: 400px;
        background: #f9fafb;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition:
            transform 0.5s cubic-bezier(0.4, 0, 0.2, 1),
            opacity 0.5s ease,
            box-shadow 0.5s ease;
        opacity: 0.45;
        transform: scale(0.88);
    }

    .hp-news-card.active {
        opacity: 1;
        transform: scale(1);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18);
        z-index: 2;
    }

    .hp-news-card:hover {
        opacity: 0.75;
    }

    .hp-news-card.active:hover {
        opacity: 1;
    }

    .hp-carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        color: #1e5799;
        font-size: 0.9rem;
        transition: background 0.2s ease, box-shadow 0.2s ease;
    }

    .hp-carousel-btn:hover {
        background: #f0f6ff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .hp-carousel-btn--prev {
        left: 6px;
    }

    .hp-carousel-btn--next {
        right: 6px;
    }

    .hp-news-dots {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-top: 1rem;
    }

    .hp-news-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #cbd5e1;
        border: none;
        cursor: pointer;
        padding: 0;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .hp-news-dot.active {
        background: #1e5799;
        transform: scale(1.3);
    }

    .hp-news-img {
        position: relative;
        height: 300px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .hp-news-tag {
        position: absolute;
        top: 0.5rem;
        left: 0.5rem;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding: 0.2rem 0.55rem;
        border-radius: 4px;
    }

    .hp-news-tag--sports {
        background: #fde047;
        color: #713f12;
    }

    .hp-news-tag--lathalain {
        background: #fde047;
        color: #713f12;
    }

    .hp-news-tag--nstp {
        background: #34d399;
        color: #064e3b;
    }

    .hp-news-tag--programs {
        background: #60a5fa;
        color: #1e3a8a;
    }

    /* ── HED Center Carousel ── */
    .hp-hed-section {
        padding: 1.75rem 2rem 1.25rem;
    }

    .hp-hed-wrapper {
        position: relative;
        overflow: hidden;
        padding: 16px 0;
    }

    .hp-hed-track {
        display: flex;
        gap: 24px;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
        align-items: center;
    }

    .hp-hed-slide {
        flex-shrink: 0;
        width: 520px;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        transition:
            transform 0.5s cubic-bezier(0.4, 0, 0.2, 1),
            opacity 0.5s ease,
            box-shadow 0.5s ease;
        opacity: 0.45;
        transform: scale(0.88);
    }

    .hp-hed-slide.active {
        opacity: 1;
        transform: scale(1);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.22);
        z-index: 2;
    }

    .hp-hed-slide:hover {
        opacity: 0.7;
    }

    .hp-hed-slide.active:hover {
        opacity: 1;
    }

    .hp-hed-slide img {
        width: 100%;
        height: 320px;
        display: block;
        object-fit: cover;
        object-position: center;
    }

    .hp-hed-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.65));
        padding: 1.25rem 1.25rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .hp-hed-caption p {
        margin: 0;
        font-size: 0.9rem;
        color: #fff;
        line-height: 1.4;
    }

    .hp-hed-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.92);
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        color: #1e5799;
        font-size: 0.95rem;
        transition: background 0.2s ease, box-shadow 0.2s ease;
    }

    .hp-hed-btn:hover {
        background: #fff;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
    }

    .hp-hed-btn--prev {
        left: 8px;
    }

    .hp-hed-btn--next {
        right: 8px;
    }

    .hp-hed-dots {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-top: 1rem;
    }

    .hp-hed-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #cbd5e1;
        border: none;
        cursor: pointer;
        padding: 0;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .hp-hed-dot.active {
        background: #1e5799;
        transform: scale(1.3);
    }

    .hp-news-body {
        padding: 0.85rem;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .hp-news-body h3 {
        font-size: 0.9rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.35;
        margin: 0;
    }

    .hp-news-body p {
        font-size: 0.78rem;
        color: #6b7280;
        line-height: 1.55;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .hp-news-author {
        font-size: 0.7rem;
        color: #9ca3af;
        margin-top: 0.15rem;
    }

    .hp-news-more-bar {
        margin-top: 0.85rem;
        padding-top: 0.75rem;
        border-top: 1px solid #e5e7eb;
        text-align: center;
    }

    .hp-news-more-link {
        font-size: 0.85rem;
        color: #1e5799;
        text-underline-offset: 3px;
    }

    .hp-recog-col {
        padding: 1.75rem;
    }

    .hp-recog-col--left {
        background: #0d1f3c;
        color: #fff;
    }

    .hp-recog-col--right {
        background: #0d2a5e;
        color: #fff;
    }

    .hp-recog-col-header {
        display: flex;
        justify-content: space-between;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: rgba(255, 255, 255, 0.5);
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        padding-bottom: 0.6rem;
        margin-bottom: 1rem;
    }

    .hp-recog-links {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-bottom: 1.5rem;
    }

    .hp-recog-link {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        padding: 0.3rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        transition: color 0.15s ease;
    }

    .hp-recog-link:hover {
        color: #7eb7ff;
    }

    .hp-recog-contact {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.65);
        line-height: 1.85;
    }

    .hp-recog-contact i {
        width: 14px;
        opacity: 0.7;
    }

    .hp-programs-list {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .hp-program-link {
        font-size: 0.9rem;
        font-weight: 500;
        color: #7eb7ff;
        text-decoration: none;
        padding: 0.3rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        transition: color 0.15s ease;
    }

    .hp-program-link:hover {
        color: #ffffff;
    }

    /* ── Quote Banner ── */
    .hp-quote-banner {
        text-align: center;
        padding: 3.5rem 2rem;
        background: linear-gradient(135deg, #0d1f3c 0%, #1e5799 100%);
        border-radius: 16px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .hp-quote-text {
        font-size: clamp(1.4rem, 3vw, 2.1rem);
        font-weight: 700;
        font-style: italic;
        color: #ffffff;
        line-height: 1.3;
        margin: 0 0 0.75rem;
        quotes: none;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
    }

    .hp-quote-author {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.7);
        font-style: normal;
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {

        .hp-info-inner,
        .hp-recog-section {
            grid-template-columns: 1fr;
        }

        .hp-info-text-side--dark .hp-info-img-side {
            order: -1;
        }

        .hp-news-card {
            width: 280px;
        }

        .hp-card-section {
            padding: 1.25rem 1rem;
        }
    }
</style>

@endpush

@section('content')

<div class="main-content">
    <!-- CTA Section -->
    <div class="cta-section">
        <div class="cta-content">
            <h2 class="cta-title">Welcome to SCC Portal</h2>
            <p class="cta-subtitle">
                Access your enrollment, grades, applications, and student information all in one place.
            </p>
            <div class="cta-buttons">
                <a href="#" class="btn-cta btn-primary-cta">
                    <i class="fas fa-pen-to-square"></i>
                    Apply for Admission
                    <!-- {{ route('admission.apply') }} -->
                </a>
                <a href="{{ route('admission.track') }}" class="btn-cta btn-secondary-cta">
                    <i class="fas fa-magnifying-glass"></i>
                    Track Your Application
                    <!-- {{ route('admission.track') }} -->
                </a>
                <a href="#services" onclick="scrollToServices()" class="btn-cta btn-secondary-cta">
                    <i class="fas fa-info-circle"></i>
                    Learn About Our Services
                </a>
            </div>
        </div>
    </div>

    <!-- Feature Badges -->
    <div class="features-row">
        <div class="feature-badge">
            <i class="fas fa-graduation-cap"></i>
            <span>Quality Education</span>
        </div>
        <div class="feature-badge">
            <i class="fas fa-lock"></i>
            <span>Secure Records</span>
        </div>
        <div class="feature-badge">
            <i class="fas fa-bolt"></i>
            <span>Fast Access</span>
        </div>
    </div>
</div>

<!-- ── Our Commitment to Quality Education ── -->
<div class="hp-info-section">
    <div class="hp-info-inner">
        <div class="hp-info-img-side">
            <img src="{{ asset('images/homepage/commitment_to_quality_educ.webp') }}" alt="Commitment to Quality Education">
        </div>
        <div class="hp-info-text-side hp-info-text-side--light">
            <h2 class="hp-info-title">Our Commitment to<br>Quality Education</h2>
            <p class="hp-info-desc">At Samuel Christian College of General Trias Inc., we strive to provide the best possible education to our students by creating an environment that fosters learning, growth, and personal development.</p>
        </div>
    </div>
</div>

<!-- ── Academics ── -->
<div class="hp-info-section">
    <div class="hp-info-inner">
        <div class="hp-info-text-side hp-info-text-side--dark">
            <h2 class="hp-info-title hp-info-title--white">Academics</h2>
            <p class="hp-info-desc hp-info-desc--white">Samuel Christian College of General Trias INC. is dedicated to fostering a holistic approach to education, nurturing the intellectual, emotional, and social development of our students. Our curriculum is designed to inspire critical thinking, creativity, and a lifelong love for learning.</p>
            <a href="{{ route('landing.school_profile') }}" class="hp-btn-outline">Find out more!</a>
        </div>
        <div class="hp-info-img-side">
            <img src="{{ asset('images/homepage/academics.webp') }}" alt="Academics">
        </div>
    </div>
</div>

<!-- ── Facebook Page Latest Post ── -->
<div class="hp-card-section">
    <h2 class="hp-section-heading">Facebook Page Latest Post</h2>
    <hr class="hp-section-divider">
    <div class="hp-fb-embed">
        <iframe
            src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fsamuelchristiancollege&tabs=timeline&width=500&height=500&small_header=false&adapt_container_width=false&hide_cover=false&show_facepile=true"
            width="500" height="500"
            style="border:none;overflow:hidden;"
            scrolling="no" frameborder="0"
            allowfullscreen="true"
            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
        </iframe>
    </div>
</div>

@php
$newsImg1 = asset('images/homepage/serbisyo ng puso.webp');
$newsImg2 = asset('images/homepage/scce-sports.webp');
$newsImg3 = asset('images/homepage/sweet16.webp');
$hedImg1 = asset('images/homepage/ntsp-nsrc.webp');
$hedImg2 = asset('images/homepage/program offering.webp');
@endphp
<!-- ── Latest News: Highschool Department ── -->
<div class="hp-card-section">
    <h2 class="hp-section-heading">LATEST NEWS: Highschool Department</h2>
    <hr class="hp-section-divider">
    <div class="hp-news-carousel-wrapper">
        <button class="hp-carousel-btn hp-carousel-btn--prev" id="newsPrev" aria-label="Previous">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="hp-news-carousel">
            <div class="hp-news-track" id="newsTrack">

                <div class="hp-news-card">
                    <div class="hp-news-img" style="background-image:url('{{ $newsImg1 }}')">
                        <span class="hp-news-tag hp-news-tag--lathalain">LATHALAIN</span>
                    </div>
                </div>

                <div class="hp-news-card">
                    <div class="hp-news-img" style="background-image:url('{{ $newsImg2 }}')">
                        <span class="hp-news-tag hp-news-tag--sports">SPORTS</span>
                    </div>
                </div>

                <div class="hp-news-card">
                    <div class="hp-news-img" style="background-image:url('{{ $newsImg3 }}')">
                        <span class="hp-news-tag hp-news-tag--sports">SPORTS</span>
                    </div>
                </div>

            </div>
        </div>
        <button class="hp-carousel-btn hp-carousel-btn--next" id="newsNext" aria-label="Next">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    <div class="hp-news-dots" id="newsDots"></div>
    <div class="hp-news-more-bar">
        <a href="#" class="hp-news-more-link">SPORTS | SCC Levelled Up Their Students' Gaming Experience</a>
    </div>
</div>

<!-- ── Latest News: Higher Education Department ── -->
<div class="hp-card-section hp-hed-section">
    <h2 class="hp-section-heading">LATEST NEWS: Higher Education Department</h2>
    <hr class="hp-section-divider">
    <div class="hp-hed-wrapper">
        <button class="hp-hed-btn hp-hed-btn--prev" id="hedPrev" aria-label="Previous">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="hp-hed-track" id="hedTrack">
            <div class="hp-hed-slide">
                <img src="{{ $hedImg1 }}" alt="NSTP-NSRC Congratulations">
                <div class="hp-hed-caption">
                    <span class="hp-news-tag hp-news-tag--nstp">NSTP</span>
                    <p>National Service Reserve Corps of SCC secures Rank 3 in three provincial categories during the 3rd Cavite Pawid Awards.</p>
                </div>
            </div>
            <div class="hp-hed-slide">
                <img src="{{ $hedImg2 }}" alt="Program Offerings">
                <div class="hp-hed-caption">
                    <span class="hp-news-tag hp-news-tag--programs">PROGRAMS</span>
                    <p>Samuel Christian College — Higher Education Department Program Offerings.</p>
                </div>
            </div>
        </div>
        <button class="hp-hed-btn hp-hed-btn--next" id="hedNext" aria-label="Next">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    <div class="hp-hed-dots" id="hedDots"></div>
</div>

<!-- ── Quote Banner ── -->
<div class="hp-quote-banner">
    <blockquote class="hp-quote-text">"In SCC, learning never stops!"</blockquote>
    <cite class="hp-quote-author">— Samuelians</cite>
</div>

@endsection

@push('scripts')
<script>
    (function() {
        const track = document.getElementById('newsTrack');
        const prevBtn = document.getElementById('newsPrev');
        const nextBtn = document.getElementById('newsNext');
        const dotsEl = document.getElementById('newsDots');
        if (!track || !prevBtn || !nextBtn) return;

        const cards = Array.from(track.querySelectorAll('.hp-news-card'));
        const total = cards.length;
        const CARD_W = 400;
        const GAP = 20;
        let current = 0;

        // Build dots
        cards.forEach((_, i) => {
            const d = document.createElement('button');
            d.className = 'hp-news-dot' + (i === 0 ? ' active' : '');
            d.addEventListener('click', () => goTo(i));
            dotsEl.appendChild(d);
        });

        function goTo(n) {
            current = ((n % total) + total) % total;
            cards.forEach((c, i) => c.classList.toggle('active', i === current));

            // Math-based centering: shift track so active card center aligns with wrapper center
            const wrapper = track.closest('.hp-news-carousel-wrapper');
            const wrapperCenter = wrapper.clientWidth / 2;
            const cardCenter = current * (CARD_W + GAP) + CARD_W / 2;
            const offset = cardCenter - wrapperCenter;
            track.style.transform = `translateX(${-offset}px)`;

            dotsEl.querySelectorAll('.hp-news-dot').forEach((d, i) =>
                d.classList.toggle('active', i === current));
        }

        prevBtn.addEventListener('click', () => goTo(current - 1));
        nextBtn.addEventListener('click', () => goTo(current + 1));

        // Click on adjacent cards to navigate
        cards.forEach((card, i) => card.addEventListener('click', () => goTo(i)));

        let timer = setInterval(() => goTo(current + 1), 4000);
        track.parentElement.addEventListener('mouseenter', () => clearInterval(timer));
        track.parentElement.addEventListener('mouseleave', () => {
            timer = setInterval(() => goTo(current + 1), 4000);
        });

        window.addEventListener('resize', () => goTo(current));
        goTo(0);
    })();

    // ── HED Center Carousel ──
    (function() {
        const track = document.getElementById('hedTrack');
        const prevBtn = document.getElementById('hedPrev');
        const nextBtn = document.getElementById('hedNext');
        const dotsEl = document.getElementById('hedDots');
        if (!track || !prevBtn || !nextBtn) return;

        const slides = Array.from(track.querySelectorAll('.hp-hed-slide'));
        const total = slides.length;
        const CARD_W = 520;
        const GAP = 24;
        let current = 0;

        slides.forEach((_, i) => {
            const d = document.createElement('button');
            d.className = 'hp-hed-dot' + (i === 0 ? ' active' : '');
            d.addEventListener('click', () => goTo(i));
            dotsEl.appendChild(d);
        });

        function goTo(n) {
            current = ((n % total) + total) % total;
            slides.forEach((s, i) => s.classList.toggle('active', i === current));
            const wrapper = track.closest('.hp-hed-wrapper');
            const wrapperCenter = wrapper.clientWidth / 2;
            const cardCenter = current * (CARD_W + GAP) + CARD_W / 2;
            const offset = cardCenter - wrapperCenter;
            track.style.transform = `translateX(${-offset}px)`;
            dotsEl.querySelectorAll('.hp-hed-dot').forEach((d, i) =>
                d.classList.toggle('active', i === current));
        }

        prevBtn.addEventListener('click', () => goTo(current - 1));
        nextBtn.addEventListener('click', () => goTo(current + 1));
        slides.forEach((slide, i) => slide.addEventListener('click', () => goTo(i)));

        let hedTimer = setInterval(() => goTo(current + 1), 4500);
        track.addEventListener('mouseenter', () => clearInterval(hedTimer));
        track.addEventListener('mouseleave', () => {
            hedTimer = setInterval(() => goTo(current + 1), 4500);
        });

        window.addEventListener('resize', () => goTo(current));
        goTo(0);
    })();
</script>
@endpush

@if(session('success'))
@push('scripts')
<script>
    window.showFormOnLoad = true;
</script>
@endpush
@endif