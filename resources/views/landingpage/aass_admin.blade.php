@extends('layouts.app-index')

@section('title', 'AASS Administration')

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

    /* Unit Divider */
    .unit-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 2rem 0 1.5rem;
    }

    .unit-divider::before,
    .unit-divider::after {
        content: '';
        flex: 1;
        height: 1.5px;
        background: #cbd5e1;
    }

    .unit-divider span {
        font-size: 1.05rem;
        font-weight: 800;
        color: #f2f4f7;
        letter-spacing: 1px;
        white-space: nowrap;
    }

    /* Leadership Row */
    .admin-leadership-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.5rem 0;
    }

    .admin-leadership-row.right {
        flex-direction: row-reverse;
    }

    .admin-leadership-row img,
    .admin-leadership-row .admin-photo-placeholder {
        width: 220px;
        height: 260px;
        object-fit: cover;
        object-position: top;
        flex-shrink: 0;
    }

    .admin-leadership-text .admin-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 0.25rem;
    }

    .admin-leadership-text .admin-role {
        font-size: 0.88rem;
        color: #e6e8ee;
        text-align: left;
        max-width: 320px;
        line-height: 1.5;
    }

    /* Admin Cards */
    .admin-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .admin-card img,
    .admin-card .admin-photo-placeholder {
        width: 150px;
        height: 150px;
        object-fit: cover;
        object-position: top;
        border-radius: 50%;
        border: 3px solid #e2e8f0;
        box-shadow: 0 4px 14px rgba(30, 58, 95, 0.12);
        margin-bottom: 0.75rem;
    }

    .admin-photo-placeholder {
        background: #dce8f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7a9ec0;
        font-size: 3rem;
    }

    .admin-card .admin-name {
        font-size: 0.95rem;
        font-weight: 800;
        color: #f7f8fa;
        margin-bottom: 0.2rem;
        line-height: 1.3;
    }

    .admin-card .admin-role {
        font-size: 0.78rem;
        color: #c7cdd8;
        line-height: 1.45;
        max-width: 180px;
        text-align: center;
    }

    /* No-photo text-only card */
    .admin-card-text {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1rem 0;
    }

    .admin-card-text .admin-name {
        font-size: 0.95rem;
        font-weight: 800;
        color: #1e3a5f;
        margin-bottom: 0.2rem;
        line-height: 1.3;
    }

    .admin-card-text .admin-role {
        font-size: 0.78rem;
        color: #6b7280;
        line-height: 1.45;
        max-width: 200px;
        text-align: center;
    }

    /* Admin Grid */
    .admin-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 2rem 1.5rem;
        padding: 1rem 0 2rem;
    }

    @media (max-width: 600px) {

        .admin-leadership-row,
        .admin-leadership-row.right {
            flex-direction: column;
            text-align: center;
        }

        .admin-leadership-text .admin-role {
            text-align: center;
        }

        .admin-leadership-row img,
        .admin-leadership-row .admin-photo-placeholder {
            width: 160px;
            height: 190px;
        }
    }
</style>
@endpush

@section('content')
<div class="shs-page">

    <!-- Page Title -->
    <div class="admin-section-title" style="margin-top:2rem;">AASS Administration</div>

    <!-- Header: Mrs. Sarah O. Magsino -->
    <div class="admin-leadership-row">
        <img src="{{ asset('images/admins/mrs.sarah.webp') }}" alt="Mrs. Sarah O. Magsino">
        <div class="admin-leadership-text">
            <div class="admin-name">Mrs. Sarah O. Magsino</div>
            <div class="admin-role">Administrative and Academic Support Services Administrator</div>
        </div>
    </div>

    <!-- Finance Unit -->
    <div class="unit-divider"><span>Finance Unit</span></div>
    <div class="admin-grid">

        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Ms. Rona Oldem, CPA</div>
            <div class="admin-role">School Accountant</div>
        </div>

        <div class="admin-card">
            <img src="{{ asset('images/admins/ms.krissy.webp') }}" alt="Ms. Krissy Jill M. Gatdula">
            <div class="admin-name">Ms. Krissy Jill M. Gatdula</div>
            <div class="admin-role">SHS Cashier Staff</div>
        </div>

        <div class="admin-card">
            <img src="{{ asset('images/admins/ms.kim.webp') }}" alt="Ms. Kimberly Jean M. Villanueva">
            <div class="admin-name">Ms. Kimberly Jean M. Villanueva</div>
            <div class="admin-role"></div>
        </div>

        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Ms. Zedina M. Colina</div>
            <div class="admin-role">JHS Cashier Staff</div>
        </div>

    </div>

    <!-- Administrative Support Unit -->
    <div class="unit-divider"><span>Administrative Support Unit</span></div>
    <div class="admin-grid">

        <div class="admin-card">
            <img src="{{ asset('images/admins/mrs.hana.webp') }}" alt="Mrs. Hana Ruth M. Arbues, LPT">
            <div class="admin-name">Mrs. Hana Ruth M. Arbues, LPT</div>
            <div class="admin-role">Vice President Secretary</div>
        </div>

        <div class="admin-card">
            <img src="{{ asset('images/admins/mr.jimmy.webp') }}" alt="Mr. Jimmy M. Taruc">
            <div class="admin-name">Mr. Jimmy M. Taruc</div>
            <div class="admin-role">School Liaison / LIS Coordinator</div>
        </div>

    </div>

    <!-- PPSICTS -->
    <div class="unit-divider"><span style="font-size:0.85rem;">Physical Plant, Security, Information, Communications and Technology Services</span></div>
    <div class="admin-grid">

        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Engr. John Paul V. Medina, CRS</div>
            <div class="admin-role">PPSICTS Coordinator</div>
        </div>

        <div class="admin-card">
            <img src="{{ asset('images/admins/mr.franz.webp') }}" alt="Mr. Franz Jehroo C. Frojas">
            <div class="admin-name">Mr. Franz Jehroo C. Frojas</div>
            <div class="admin-role">Higher Education Department ICT Officer</div>
        </div>

        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Maintenance Staff</div>
            <div class="admin-role"></div>
        </div>

        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Security Officer</div>
            <div class="admin-role"></div>
        </div>

    </div>

    <!-- Auxiliary Services Unit -->
    <div class="unit-divider"><span>Auxiliary Services Unit</span></div>
    <div class="admin-grid">

        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Mr. Noel V. Orcullo</div>
            <div class="admin-role">Cafeteria Manager</div>
        </div>

        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Head Cook Cafeteria Staff</div>
            <div class="admin-role"></div>
        </div>

        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Mr. John Michael H. Opeda</div>
            <div class="admin-role">In-Charge, Bookstore and Supply Services</div>
        </div>

    </div>

</div>
@endsection