@extends('layouts.app-index')

@section('title', 'Organizations & Clubs')

@push('styles')
<style>
    .clubs-page {
        font-family: inherit;
        padding-bottom: 3rem;
        background: transparent;
    }

    /* Section heading */
    .clubs-section-title {
        text-align: center;
        font-size: clamp(1.9rem, 4vw, 2.8rem);
        font-weight: 900;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #f0f4f8;
        margin: 2.5rem 0 0.3rem;
    }

    .clubs-section-sub {
        text-align: center;
        font-size: 1rem;
        color: #c8d3df;
        margin-bottom: 2.5rem;
    }

    /* Grid */
    .clubs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 3rem 2.5rem;
        padding: 0.5rem 0 1rem;
    }

    /* Card */
    .club-card {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .club-card img {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 1rem;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        background: #fff;
    }

    .club-card .club-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: #f0f4f8;
        line-height: 1.4;
        margin-bottom: 0.4rem;
    }

    .club-card .club-desc {
        font-size: 0.88rem;
        color: #c8d3df;
        line-height: 1.65;
    }

    /* Divider between sections */
    .clubs-divider {
        height: 1.5px;
        background: rgba(255, 255, 255, 0.2);
        margin: 3rem 0 0;
    }
</style>
@endpush

@section('content')
<div class="clubs-page">

    <!-- ORGANIZATIONS / ASSOCIATIONS -->
    <div class="clubs-section-title">Organizations/Associations</div>
    <div class="clubs-section-sub">Junior &amp; Senior Organizations/Associations of Samuel Christian College</div>

    <div class="clubs-grid">

        <div class="club-card">
            <img src="{{ asset('images/clubs/ssg.webp') }}" alt="Supreme Student Government">
            <div class="club-name">Supreme Student Government</div>
            <div class="club-desc">An organization for the Junior High Students of SCCGTI.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/csb.webp') }}" alt="Central Student Body">
            <div class="club-name">Central Student Body</div>
            <div class="club-desc">An organization for the Senior High Students of SCCGTI.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/system.webp') }}" alt="Society of Young Scientists, Technologists, Engineers, and Mathematicians">
            <div class="club-name">Society of Young Scientists, Technologists, Engineers, and Mathematicians</div>
            <div class="club-desc">An organization for the Science, Technology, Engineering, and Mathematics (STEM) students of SCCGTI.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/uo.webp') }}" alt="Utopia Organization">
            <div class="club-name">Utopia Organization</div>
            <div class="club-desc">An organization for the Humanities and Social Sciences (HUMSS) students of SCCGTI.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/fyblea.webp') }}" alt="Federation of Young Business Leaders, Entrepreneurs and Accountants">
            <div class="club-name">Federation of Young Business Leaders, Entrepreneurs and Accountants</div>
            <div class="club-desc">An organization for the Accountancy, Business and Management (ABM) students of SCCGTI.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/juniorICT.webp') }}" alt="Junior Information and Communications Technologists Organization">
            <div class="club-name">Junior Information and Communications Technologists Organization</div>
            <div class="club-desc">An organization for the Information and Communications Technology (ICT) students of SCCGTI.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/csc.webp') }}" alt="Central Student Council">
            <div class="club-name">Central Student Council</div>
            <div class="club-desc">An organization for the College Students of SCCGTI.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/juniorPIA.webp') }}" alt="Junior Philippine Institute of Accountants">
            <div class="club-name">Junior Philippine Institute of Accountants</div>
            <div class="club-desc">An association for the Accountant Students in SCCGTI.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/afa.webp') }}" alt="Association of Future Administrators">
            <div class="club-name">Association of Future Administrators</div>
            <div class="club-desc">An association for the Office Administration Students in SCCGTI.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/acces.webp') }}" alt="Association of Certified Computer Engineering Students">
            <div class="club-name">Association of Certified Computer Engineering Students</div>
            <div class="club-desc">An association for the Computer Engineering Students of SCCGTI.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/psa.webp') }}" alt="Psychological Student Association">
            <div class="club-name">Psychological Student Association</div>
            <div class="club-desc">An association for the Psychology Students of SCCGTI.</div>
        </div>

    </div>

    <div class="clubs-divider"></div>

    <!-- CLUBS -->
    <div class="clubs-section-title">Clubs</div>
    <div class="clubs-section-sub">High School &amp; College Clubs of Samuel Christian College</div>

    <div class="clubs-grid">

        <div class="club-card">
            <img src="{{ asset('images/clubs/Note-tify.webp') }}" alt="Note-tify">
            <div class="club-name">Note-tify</div>
            <div class="club-desc">A club here to inspire, create, and connect through music and passion.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/Tech-Connect.webp') }}" alt="Tech-Connect">
            <div class="club-name">Tech-Connect</div>
            <div class="club-desc">A club for every online gamers, digital art enthusiast and internet savvy.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/You and M.E..webp') }}" alt="You and M.E.">
            <div class="club-name">You and M.E.</div>
            <div class="club-desc">A club that makes SCCGTI greener, cleaner and better than ever.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/El Guardian.webp') }}" alt="El Guardian">
            <div class="club-name">El Guardian</div>
            <div class="club-desc">The Official High School Publication of Samuel Christian College Campus.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/Teatro De Samuelian.webp') }}" alt="Teatro De Samuelian">
            <div class="club-name">Teatro De Samuelian</div>
            <div class="club-desc">A club that encourages other Samuelians to show off, express their talents, have fun, and make glorious memories.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/Kalinangan Dance Troupe.webp') }}" alt="Kalinangan Dance Troupe">
            <div class="club-name">Kalinangan Dance Troupe</div>
            <div class="club-desc">A club aims to boost up the confidence of the students and to show the talents of each and every one.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/Voice Spectrum.webp') }}" alt="Voice Spectrum">
            <div class="club-name">Voice Spectrum</div>
            <div class="club-desc">A clubs with a purpose in empowering voices, building confidence, and creating a spectrum of possibilities.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/SamueLikha.webp') }}" alt="SamueLikha">
            <div class="club-name">SamueLikha</div>
            <div class="club-desc">A club of dreamers, creatives, and adventurers.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/The Lighthouse.webp') }}" alt="The Lighthouse">
            <div class="club-name">The Lighthouse</div>
            <div class="club-desc">A club specializing in Inter-School Christian Fellowship.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/GNTIX Dance Company.webp') }}" alt="GNTIX Dance Company">
            <div class="club-name">GNTIX Dance Company</div>
            <div class="club-desc">A club dedicated to celebrate the art of dance and fostering meaningful connections among dancers in SCCGTI College Department.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/Health Emergency Assistance and Response Team.webp') }}" alt="Health Emergency Assistance and Response Team">
            <div class="club-name">Health Emergency Assistance and Response Team</div>
            <div class="club-desc">The Official First A.I.D.E.R of SCC Heart is here in SCCGTI, ready to serve you with purpose.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/National Service Reserve Corps.webp') }}" alt="National Service Reserve Corps">
            <div class="club-name">National Service Reserve Corps</div>
            <div class="club-desc">A club led by passionate youths, students, volunteers, and officers fuels the spirit of service.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/El Gazette.webp') }}" alt="El Gazette">
            <div class="club-name">El Gazette</div>
            <div class="club-desc">The College Publication of SCCGTI.</div>
        </div>

        <div class="club-card">
            <img src="{{ asset('images/clubs/PsalMuelians.webp') }}" alt="PsalMuelians">
            <div class="club-name">PsalMuelians</div>
            <div class="club-desc">A club at SCCGTI for College Student with passion for music.</div>
        </div>

    </div>

</div>
@endsection