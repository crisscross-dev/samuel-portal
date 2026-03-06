@extends('layouts.app-index')

@section('title', 'SCC Portal')

@section('content')

<!-- CTA Section -->
<div class="cta-section">
    <div class="cta-content">
        <h2 class="cta-title">Welcome to SCC Portal</h2>
        <p class="cta-subtitle">
            Access your enrollment, grades, applications, and student information all in one place.
        </p>
        <div class="cta-buttons">
            <a href="{{ route('admission.apply') }}" class="btn-cta btn-primary-cta">
                <i class="fas fa-pen-to-square"></i>
                Apply for Admission
            </a>
            <a href="{{ route('admission.track') }}" class="btn-cta btn-secondary-cta">
                <i class="fas fa-magnifying-glass"></i>
                Track Your Application
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

@endsection

@if(session('success'))
@push('scripts')
<script>
    window.showFormOnLoad = true;
</script>
@endpush
@endif