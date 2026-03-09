@extends('layouts.app-index')

@section('title', 'SAS Administration')

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
        color: #edeff1;
        margin-bottom: 0.2rem;
        line-height: 1.3;
    }

    .admin-card-text .admin-role {
        font-size: 0.78rem;
        color: #dce0e7;
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
    <div class="admin-section-title" style="margin-top:2rem;">Student Affairs and Services Administration</div>

    <!-- Header: Ms. Elizabeth N. Arriesgado -->
    <div class="admin-leadership-row">
        <img src="{{ asset('images/admins/mrs.beth.webp') }}" alt="Ms. Elizabeth N. Arriesgado">
        <div class="admin-leadership-text">
            <div class="admin-name">Ms. Elizabeth N. Arriesgado</div>
            <div class="admin-role">Administrator, Student Affairs and Services Unit</div>
        </div>
    </div>

    <!-- Guidance Unit -->
    <div class="unit-divider"><span>Guidance Unit</span></div>

    <!-- Guidance Heads (text-only) -->
    <div class="admin-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); padding-bottom: 0.5rem;">
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Dr. Marina G. Quila, RGC</div>
            <div class="admin-role">Guidance Counselor</div>
        </div>
        <div class="admin-card-text">
            <div class="admin-card">
                <img src="{{ asset('images/admins/ms.jessa.webp') }}" alt="Ms. Jessa Mae M. Espinoza, RPm, CMHFR">
                <div class="admin-name">Ms. Jessa Mae M. Espinoza, RPm</div>
                <div class="admin-role">Head, Guidance Unit</div>
            </div>
        </div>
    </div>

    <!-- Guidance Associates -->
    <div class="admin-grid">
        <div class="admin-card">
            <img src="{{ asset('images/admins/ms.jessa.webp') }}" alt="Ms. Jessa Mae M. Espinoza, RPm, CMHFR">
            <div class="admin-name">Ms. Jessa Mae M. Espinoza, RPm, CMHFR</div>
            <div class="admin-role">SHS Guidance Associate</div>
        </div>
        <div class="admin-card">
            <img src="{{ asset('images/admins/ms.carla.webp') }}" alt="Ms. Carla Jessie B. Bayas, RPm">
            <div class="admin-name">Ms. Carla Jessie B. Bayas, RPm</div>
            <div class="admin-role">JHS Guidance Associate</div>
        </div>
        <div class="admin-card">
            <img src="{{ asset('images/admins/ms.jhermaine.webp') }}" alt="Ms. Jhermaine Joie S. Brigola">
            <div class="admin-name">Ms. Jhermaine Joie S. Brigola</div>
            <div class="admin-role">College Guidance Associate</div>
        </div>
    </div>

    <!-- Library Services -->
    <div class="unit-divider"><span>Library Services</span></div>

    <!-- School Librarian (text-only) -->
    <div class="admin-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); padding-bottom: 0.5rem;">
        <div class="admin-card-text">
            <div class="admin-name">Dr. Evelyn Rey, RL</div>
            <div class="admin-role">School Librarian</div>
        </div>
    </div>

    <div class="admin-grid">
        <div class="admin-card">
            <img src="{{ asset('images/admins/ms.analyn.webp') }}" alt="Ms. Analyn B. Papio">
            <div class="admin-name">Ms. Analyn B. Papio</div>
            <div class="admin-role">In-Charge, College Library Services</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Ms. Edna T. Leveriza, LPT</div>
            <div class="admin-role">In-Charge, High School Library Services</div>
        </div>
    </div>

    <!-- Student Development Services -->
    <div class="unit-divider"><span>Student Development Services</span></div>
    <div class="admin-grid">
        <div class="admin-card">
            <img src="{{ asset('images/admins/ms.sam.webp') }}" alt="Ms. SamAllison D. Dayao, RPm">
            <div class="admin-name">Ms. SamAllison D. Dayao, RPm</div>
            <div class="admin-role">Coordinator, Student Development Services</div>
        </div>
        <div class="admin-card">
            <img src="{{ asset('images/admins/mrs.beth.webp') }}" alt="Ms. Elizabeth N. Arriesgado">
            <div class="admin-name">Ms. Elizabeth N. Arriesgado</div>
            <div class="admin-role">Student Discipline Officer</div>
        </div>
    </div>

    <!-- Health Services -->
    <div class="unit-divider"><span>Health Services</span></div>
    <div class="admin-grid">
        <div class="admin-card">
            <img src="{{ asset('images/admins/ms.kriza.webp') }}" alt="Ms. Kriza Mariell C. Española, RN">
            <div class="admin-name">Ms. Kriza Mariell C. Española, RN</div>
            <div class="admin-role">School Nurse<br>Insurance Officer</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Dr. Redigo M. Aguilar</div>
            <div class="admin-role">School Physician</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Dr. Grace G. Domingo</div>
            <div class="admin-role">School Dentist</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Ms. Anna Romina T. Cortez, LPT</div>
            <div class="admin-role">First Aider</div>
        </div>
    </div>

    <!-- Career and Alumni Services -->
    <div class="unit-divider"><span>Career and Alumni Services</span></div>
    <div class="admin-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, max-content)); justify-content: center;">
        <div class="admin-card">
            <img src="{{ asset('images/admins/mrs.beth.webp') }}" alt="Ms. Elizabeth N. Arriesgado">
            <div class="admin-name">Ms. Elizabeth N. Arriesgado</div>
            <div class="admin-role">Coordinator, Career and Alumni Services</div>
        </div>
    </div>

</div>
@endsection