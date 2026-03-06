@extends('layouts.app-index')

@section('title', 'College')

@push('styles')
<style>
    /* ── JHS Page ─────────────────────────────────────── */
    .shs-page {
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
        font-size: clamp(1.3rem, 2.5vw, 1.8rem);
        font-weight: 900;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #ffffff;
        padding: 1.5rem 0;
        position: relative;
        text-shadow: 0 1px 2px rgba(30, 58, 95, 0.12);
    }

    .jhs-section-title::after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: #f1c40f;
        margin: 0.6rem auto 0;
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
    .shs-features {
        background: linear-gradient(135deg, #1e3a5f 0%, #0d2137 100%);
        border-radius: 12px;
        padding: 2.5rem 3rem;
        color: #fff;
    }

    .shs-features h3 {
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #f1c40f;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .shs-features ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 0.85rem;
    }

    .shs-features ul li {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        font-size: 0.92rem;
        line-height: 1.55;
        color: rgba(255, 255, 255, 0.88);
    }

    .shs-features ul li i {
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
    .shs-requirements {
        background: linear-gradient(135deg, #0d2137 0%, #1e3a5f 100%);
        border-radius: 12px;
        padding: 2.5rem 3rem;
        color: #fff;
        margin-bottom: 1.5rem;
        margin-top: 1.5rem;
    }

    .shs-requirements h2 {
        font-size: clamp(1.4rem, 3vw, 2rem);
        font-weight: 900;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .shs-requirements hr {
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

        .shs-requirements {
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
<div class="shs-page">





</div>
@endsection