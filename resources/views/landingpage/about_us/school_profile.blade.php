@extends('layouts.app-index')

@section('title', 'School Profile')

@push('styles')
<style>
    .profile-page {
        font-family: segoe-ui, sans-serif;
    }

    /* Section blocks */
    .profile-section {
        padding: 2.5rem 0 1.5rem;
    }

    .profile-section-dark {
        background: #1e3a5f;
        color: #f0f4f8;
        padding: 2.5rem 2rem;
        margin: 0 -1rem;
    }

    /* Section heading */
    .profile-heading {
        font-size: clamp(1.4rem, 3vw, 2rem);
        font-weight: 900;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #f0f4f8;
        margin-bottom: 0.75rem;
        padding-bottom: 0.6rem;
        border-bottom: 2px solid rgba(255, 255, 255, 0.25);
    }

    .profile-heading-light {
        font-size: clamp(1.4rem, 3vw, 2rem);
        font-weight: 900;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #1e3a5f;
        margin-bottom: 0.75rem;
        padding-bottom: 0.6rem;
        border-bottom: 2px solid #cbd5e1;
    }

    /* Sub-heading */
    .profile-subheading {
        font-size: 1rem;
        font-weight: 700;
        color: #f1c40f;
        margin-bottom: 1rem;
    }

    /* History paragraphs */
    .profile-body {
        font-size: 0.97rem;
        color: #dce8f5;
        line-height: 1.85;
        max-width: 860px;
        margin: 0 auto;
    }

    .profile-body p {
        margin-bottom: 1.25rem;
        text-indent: 2.5rem;
    }

    .profile-body p:last-child {
        margin-bottom: 0;
        font-style: italic;
        font-weight: 700;
        text-indent: 0;
        text-align: center;
        color: #f1c40f;
    }

    /* Logo section */
    .logo-section {
        display: flex;
        align-items: center;
        gap: 2.5rem;
        padding: 1.5rem 0;
        flex-wrap: wrap;
    }

    .logo-section img {
        width: 220px;
        flex-shrink: 0;
    }

    .logo-description {
        flex: 1;
        min-width: 240px;
    }

    .logo-description p {
        font-size: 0.97rem;
        color: #dce8f5;
        font-weight: 600;
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .logo-description ol {
        padding-left: 1.25rem;
        color: #dce8f5;
        font-size: 0.95rem;
        line-height: 1.8;
    }

    .logo-description ol li {
        margin-bottom: 0.4rem;
    }

    .logo-description ol li b {
        color: #f1c40f;
    }

    @media (max-width: 600px) {
        .logo-section {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .logo-description ol {
            text-align: left;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-page">

    <!-- THE SEED OF THE PAST -->
    <div class="profile-section-dark">
        <div class="profile-heading">The Seed of the Past</div>

        <div class="profile-subheading">The Samuel Christian College of General Trias, Inc. History</div>

        <div class="profile-body">
            <p>Several decades ago, God planted the seed of SCC in the heart of a little boy whose challenging life ignited and kept his passion to pursue education to better his family. With his faith, intelligence, talents, and family support, the little boy grew up and was blessed to become a very successful man in his field. He was an accountant by profession but God shaped and equipped him to be a conscientious educator. His life flowed in two streams which both watered the seed within him and kept it alive. The time came when finally, the dormant seed was awakened, as his heart melted, upon hearing the plights of high school graduates from public schools. In 2011, moved with this great burden and passion, Dr. Emmanuel D. Magsino, our school president and Ms. Sarah O. Magsino, his wife, took the challenge of starting a school.</p>

            <p>Stirred by the vision, propelled by the mission and empowered by their great faith in God, the two begun the preparation phase. Everything was brought to God in prayer with much expectation of his intervention and provision. In July 25, 2011, the Articles of Incorporation was registered with Securities and Exchange Commission, where the school's name, SAMUEL CHRISTIAN COLLEGE OF GEN. TRIAS INC was approved.</p>

            <p>School year 2012-2013 was SCC's blessed first. It had a good number of students with cooperative and supportive parents, committed teachers and pro-active administrator and support personnel. In Academics, SCC abided with guidelines recommended by the Department of Education. For the succeeding years, our enrollment has been increasing.</p>

            <p>Samuel Christian College opened its Junior High School program on June 2012. It started with 5 sections in the Grade 7 level with a total of 131 students. On May 21, 2014, SCC officially granted a Government Recognition No. s-262, s. 2014. In 2015-2016, SCC applied for Educational Service Contracting (ESC) and was granted in 2016-2017. SCC Family is continuously working hand-in-hand to realize the school's vision and achieve its mission.</p>

            <p>We are truly grateful to God for the past years of wonderful memories and amazing achievements. SCC family will continue its growth and development in the coming years. May the seed God planted in the heart of SCC continually grow and become a source and cradle of life to everyone who receives it. Glory and honor to God as we uphold and pursue our vision of being a mark of excellence, a testimony of faith, and a heart of service.</p>

            <p>To God be the Highest Glory!</p>
        </div>
    </div>

    <!-- SCHOOL LOGO -->
    <div class="profile-section-dark" style="margin-top: 2px;">
        <div class="profile-heading">School Logo</div>

        <div class="logo-section">
            <img src="{{ asset('images/scc_logo.png') }}" alt="SCC School Logo">
            <div class="logo-description">
                <p>The SCC Logo is divided into four major components that the school upholds. They are as follows:</p>
                <ol>
                    <li>The <b>Bible</b>, the word of God symbolizes the truth that Samuel Christian College strongly upholds.</li>
                    <li>The <b>Torch</b>, the enlightening flame represents the holistic education our institution is committed to provide.</li>
                    <li>The <b>Technology</b> symbolizes the life and work skills we equipped our students with to meet the demands of the 21st century.</li>
                    <li>and the <b>Globe</b> which symbolizes the global competence that every SCC graduate should possess.</li>
                </ol>
            </div>
        </div>
    </div>

</div>
@endsection