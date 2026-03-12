@extends('layouts.app-index')

@section('title', 'College')

@push('styles')
<style>
    .shs-page {
        font-family: inherit;
    }

    /* Admin Section Title */
    .admin-section-title {
        text-align: center;
        font-size: clamp(1.3rem, 2.5vw, 1.8rem);
        font-weight: 900;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #1e3a5f;
        padding: 0.25rem 2rem;
        margin: 1.5rem auto 0.5rem;
        position: relative;
        display: inline-block;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.65);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(30, 58, 95, 0.12);
        border-radius: 10px;
        box-shadow: 0 4px 18px rgba(30, 58, 95, 0.1);
        line-height: 1.2;
    }

    .admin-section-title::after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: #f1c40f;
        margin: 0.3rem auto -0.1rem;
        border-radius: 2px;
    }

    /* Admin Cards */
    .admin-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .admin-card img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        object-position: top;
        border-radius: 50%;
        border: 3px solid #e2e8f0;
        box-shadow: 0 4px 14px rgba(30, 58, 95, 0.12);
        margin-bottom: 0.75rem;
        background: #f0f4f8;
    }

    .admin-card .admin-name {
        font-size: 1rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 0.2rem;
        line-height: 1.3;
    }

    .admin-card .admin-role {
        font-size: 0.8rem;
        color: #c2c8d4;
        line-height: 1.45;
        max-width: 180px;
        text-align: center;
    }

    /* Admin Leadership */
    .admin-leadership {
        display: flex;
        flex-direction: row;
        justify-content: center;
        gap: 3rem;
        padding: 0.5rem 0;
        flex-wrap: wrap;
    }

    .admin-leadership-row {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        text-align: center;
    }

    .admin-leadership-row.right {
        flex-direction: column;
    }

    .admin-leadership-row img {
        width: 220px;
        height: 260px;
        border-radius: 12px;
        object-fit: cover;
        object-position: top;
        box-shadow: 0 6px 24px rgba(30, 58, 95, 0.15);
        flex-shrink: 0;
        background: linear-gradient(to bottom,
                rgba(242, 243, 245, 0.95),
                rgba(242, 243, 245, 0.70));
    }

    .admin-leadership-text .admin-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 0.25rem;
    }

    .admin-leadership-text .admin-role {
        font-size: 0.85rem;
        color: #d9dbdf;
        text-align: center;
    }

    /* Admin Grid */
    .admin-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 2rem 1.5rem;
        padding: 2rem 0;
    }

    @media (max-width: 600px) {
        .admin-leadership {
            flex-direction: column;
            align-items: center;
        }

        .admin-leadership-row img {
            width: 160px;
            height: 190px;
        }
    }
</style>
@endpush

@section('content')
<div class="shs-page">

    <!-- Page Title -->
    <div class="admin-section-title" style="padding-top:0.25rem; margin-top:2rem;">Administrative Council</div>

    <!-- Leadership: President & Vice-President -->
    <div class="admin-leadership mb-4 mt-2">

        <!-- President -->
        <div class="admin-leadership-row">
            <img src="{{ asset('images/admins/dr.manny.webp') }}" alt="Dr. Emmanuel D. Magsino, CPA" style="width:220px;height:260px;border-radius:12px;flex-shrink:0;object-fit:cover;object-position:top;box-shadow:0 6px 24px rgba(30,58,95,0.15);">
            <div class="admin-leadership-text">
                <div class="admin-name">Dr. Emmanuel D. Magsino, CPA</div>
                <div class="admin-role">School President</div>
            </div>
        </div>

        <!-- Vice-President -->
        <div class="admin-leadership-row right">
            <img src="{{ asset('images/admins/mrs.sarah.webp') }}" alt="Mrs. Sarah O. Magsino" style="width:220px;height:260px;border-radius:12px;flex-shrink:0;object-fit:cover;object-position:top;box-shadow:0 6px 24px rgba(30,58,95,0.15);">
            <div class="admin-leadership-text">
                <div class="admin-name">Mrs. Sarah O. Magsino</div>
                <div class="admin-role">School Vice-President</div>
            </div>
        </div>

    </div>

    <!-- Administrators Grid -->

    <div class="admin-grid mb-4">

        <div class="admin-card">
            <img src="{{ asset('images/admins/mr.jez.webp') }}" alt="Mr. Jezreel James M. Colina, LPT">
            <div class="admin-name">Mr. Jezreel James M. Colina, LPT</div>
            <div class="admin-role">Basic Education Department Administrator<br>High School Principal</div>
        </div>

        <div class="admin-card">
            <img src="{{ asset('images/admins/mr.jerick.webp') }}" alt="Mr. Jerickson C. Bautista">
            <div class="admin-name">Mr. Jerickson C. Bautista</div>
            <div class="admin-role">Higher Education Department Administrator</div>
        </div>

        <div class="admin-card">
            <img src="{{ asset('images/admins/mrs.beth.webp') }}" alt="Mrs. Elizabeth N. Arriesgado">
            <div class="admin-name">Mrs. Elizabeth N. Arriesgado</div>
            <div class="admin-role">Student Affairs &amp; Services Administrator</div>
        </div>

        <div class="admin-card">
            <img src="{{ asset('images/admins/mrs.evangeline.webp') }}" alt="Mrs. Evangeline M. Salud">
            <div class="admin-name">Mrs. Evangeline M. Salud</div>
            <div class="admin-role">Human Resource Management &amp; Development Coordinator</div>
        </div>

        <div class="admin-card">
            <img src="{{ asset('images/admins/ms.joe.webp') }}" alt="Ms. Joeliza T. Pablo, LPT">
            <div class="admin-name">Ms. Joeliza T. Pablo, LPT</div>
            <div class="admin-role">High School Vice Principal</div>
        </div>

        <div class="admin-card">
            <img src="{{ asset('images/admins/mrs.ella.webp') }}" alt="Mrs. Ella Mae H. Gimao, MPA">
            <div class="admin-name">Mrs. Ella Mae H. Gimao, MPA</div>
            <div class="admin-role">Samuelian Ministry, Research, Extension and Community Affairs Director</div>
        </div>

    </div>

</div>
@endsection