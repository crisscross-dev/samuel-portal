@extends('layouts.app-index')

@section('title', 'Core Values & Goals')

@push('styles')
<style>
    .cvg-page {
        font-family: inherit;
    }

    /* ── Section headings ── */
    .cvg-section-heading {
        font-size: clamp(1.6rem, 3.5vw, 2.4rem);
        font-weight: 900;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #e9ecf0;
        margin-bottom: 0.6rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #cbd5e1;
    }

    .cvg-section-heading-light {
        font-size: clamp(1.6rem, 3.5vw, 2.4rem);
        font-weight: 900;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #f0f4f8;
        margin-bottom: 0.6rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    /* ── Core Values ── */
    .core-values-section {
        padding: 2.5rem 0 2rem;
    }

    .cv-list {
        list-style: none;
        padding: 1rem 0 0;
        margin: 0;
    }

    .cv-list li {
        font-size: 1.15rem;
        color: #fffffb;
        line-height: 2;
        letter-spacing: 0.3px;
    }

    .cv-list li .cv-letter {
        font-size: 1.25rem;
        font-weight: 900;
        color: #f1c40f;
        display: inline-block;
        width: 1.4rem;
    }

    /* ── Goals ── */
    .goals-section {
        background: #1e3a5f;
        color: #f0f4f8;
        padding: 2.5rem 2rem;
        margin: 2rem -1rem 0;
    }

    .goal-item {
        margin-bottom: 2rem;
    }

    .goal-item:last-child {
        margin-bottom: 0;
    }

    .goal-title {
        font-size: 0.95rem;
        font-weight: 900;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #f1c40f;
        margin-bottom: 0.5rem;
    }

    .goal-body {
        font-size: 1rem;
        color: #dce8f5;
        line-height: 1.8;
        max-width: 820px;
    }
</style>
@endpush

@section('content')
<div class="cvg-page">

    <!-- CORE VALUES -->
    <div class="core-values-section">
        <div class="cvg-section-heading">Core Values</div>
        <ul class="cv-list">
            <li><span class="cv-letter">S</span>trong faith in God</li>
            <li><span class="cv-letter">A</span>ccountability</li>
            <li><span class="cv-letter">M</span>oral Integrity</li>
            <li><span class="cv-letter">U</span>nity</li>
            <li><span class="cv-letter">E</span>ffectiveness and Efficiency</li>
            <li><span class="cv-letter">L</span>eadership</li>
            <li><span class="cv-letter">I</span>nnovativeness</li>
            <li><span class="cv-letter">A</span>rtistry</li>
            <li><span class="cv-letter">N</span>obility</li>
            <li><span class="cv-letter">S</span>elf-discipline</li>
        </ul>
    </div>

    <!-- GOALS -->
    <div class="goals-section">
        <div class="cvg-section-heading-light">Goals of Samuel Christian College</div>

        <div style="padding-top: 1.5rem;">

            <div class="goal-item">
                <div class="goal-title">Positive School Culture of Excellence</div>
                <div class="goal-body">A proactive learning community with positive school culture exemplifying excellence in thinking, behavior and action evident in life and work manifested in the communal cohesive interaction which is focused on the school's continuous growth and development.</div>
            </div>

            <div class="goal-item">
                <div class="goal-title">Effective and Standard-Based School System</div>
                <div class="goal-body">A technologically-driven institution with an articulated and effective system of administration and governance, instructional leadership, strong team of faculty and staff, with adequate provision on school budget and finances, physical plant and instructional support facilities, academic support and student development services and proactive institutional planning and development programs.</div>
            </div>

            <div class="goal-item">
                <div class="goal-title">Growing Christians</div>
                <div class="goal-body">Committed Christians living in a growing personal relationship with God that reflects His goodness and grace.</div>
            </div>

            <div class="goal-item">
                <div class="goal-title">Holistically Developed Individuals</div>
                <div class="goal-body">Individuals who are spiritually, mentally, emotionally, socially and physically developed with grit in adopting to challenges on self, family, school, community and to the demands of time.</div>
            </div>

            <div class="goal-item">
                <div class="goal-title">Equipt Learners</div>
                <div class="goal-body">Capacitated learners of human dignity and purpose driven life with commitment to personal development being equipped for lifelong learning, smart skills, entrepreneurship, and employment, competitiveness with glocal standards.</div>
            </div>

            <div class="goal-item">
                <div class="goal-title">21st Century Skilled Graduates</div>
                <div class="goal-body">Competent learners equipped with life and career skills, learning and innovative skills, information, media and technology skills and effective communication skills.</div>
            </div>

            <div class="goal-item">
                <div class="goal-title">Responsible and Culture-Smart Filipinos</div>
                <div class="goal-body">Patriotic and responsible citizens who respect, appreciate and celebrate our diverse Filipino culture and participate in local economic, social and environmental development and sustainability programs.</div>
            </div>

        </div>
    </div>

</div>
@endsection