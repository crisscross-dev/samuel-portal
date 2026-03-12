@extends('layouts.app-index')

@section('title', 'Higher Education Department')

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
        background: linear-gradient(to bottom,
                rgba(242, 243, 245, 0.95),
                rgba(242, 243, 245, 0.70));
        border-radius: 12px;
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
        color: #f7f8fa;
        margin-bottom: 0.2rem;
        line-height: 1.3;
    }

    .admin-card-text .admin-role {
        font-size: 0.78rem;
        color: #c7cdd8;
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
    <div class="admin-section-title" style="margin-top:2rem;">Higher Education Department</div>

    <!-- Header: Mr. Jerickson C. Bautista -->
    <div class="admin-leadership-row">
        <img src="{{ asset('images/admins/mr.jerick.webp') }}" alt="Mr. Jerickson C. Bautista">
        <div class="admin-leadership-text">
            <div class="admin-name">Mr. Jerickson C. Bautista</div>
            <div class="admin-role">Higher Education Department Administrator</div>
        </div>
    </div>

    <!-- Academic Affairs Unit -->
    <div class="unit-divider"><span>Academic Affairs Unit</span></div>

    <!-- OIC (text-only, centered) -->
    <div style="text-align:center; padding: 0.5rem 0 1.5rem;">
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Mr. Roland C. Helmo</div>
            <div class="admin-role">Officer In-Charge, Academic Affairs Unit</div>
        </div>
    </div>

    <!-- Department Heads & Program Leaders -->
    <div class="admin-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Dr. Catherine O. Aquino, CPA</div>
            <div class="admin-role">Department Head, Department of Accountancy<br>Program Leader, BS Accountancy and BS Management Accounting</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Mr. Roland C. Helmo</div>
            <div class="admin-role">Department Head, Department of Business and Management Studies<br>Program Leader, BS Office Administration</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Engr. Daniel A. Villanueva, CCPE, MEP</div>
            <div class="admin-role">Department Head, Department of Engineering</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Mr. Charles Jay S. Lamadrid, RPm, CHRA</div>
            <div class="admin-role">Department Head, Department of Psychology</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Engr. Raymart B. Esguerra</div>
            <div class="admin-role">Program Leader, BS Computer Engineering</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Dr. Alyssa Kae A. Lavadia, RPm, RPsy, ICAPIl</div>
            <div class="admin-role">Program Leader, BS Psychology</div>
        </div>
    </div>

    <!-- Samuelian Ministry, Research Extension and Community Affairs -->
    <div class="unit-divider"><span style="font-size:0.85rem;">Samuelian Ministry, Research Extension and Community Affairs</span></div>

    <div class="admin-leadership-row right">
        <img src="{{ asset('images/admins/mrs.ella.webp') }}" alt="Ms. Ella Mae H. Gimao, MPA">
        <div class="admin-leadership-text">
            <div class="admin-name">Ms. Ella Mae H. Gimao, MPA</div>
            <div class="admin-role">Director, Samuelian Ministry, Research, Extension and Community Affairs</div>
        </div>
    </div>

    <!-- Administrative Services Unit -->
    <div class="unit-divider"><span>Administrative Services Unit</span></div>

    <!-- OIC (text-only, centered) -->
    <div style="text-align:center; padding: 0.5rem 0 1.5rem;">
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Mr. Jullian Paul A. Gimena</div>
            <div class="admin-role">Officer In-Charge, Administrative Services Unit</div>
        </div>
    </div>

    <div class="admin-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Ms. Mariela Carla E. Jimenez, LPT</div>
            <div class="admin-role">College Registrar</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Mr. Jullian Paul A. Gimena</div>
            <div class="admin-role">College Secretary<br>Coordinator, Records and Data Management</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Mr. Jerickson C. Bautista</div>
            <div class="admin-role">Coordinator, Accreditation, Recognition, and Certification<br>Coordinator, Monitoring and Evaluation</div>
        </div>
        <div class="admin-card">
            <div class="admin-photo-placeholder"><i class="fas fa-user"></i></div>
            <div class="admin-name">Ms. Reeka Mae B. Villaluz, RPh, RPm</div>
            <div class="admin-role">Coordinator, Science Laboratories</div>
        </div>
    </div>

    <!-- College Faculty Members -->
    <div class="unit-divider"><span>College Faculty Members</span></div>

</div>
@endsection