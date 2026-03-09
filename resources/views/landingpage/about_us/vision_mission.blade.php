@extends('layouts.app-index')

@section('title', 'Vision & Mission')

@push('styles')
<style>
    .vm-page {
        font-family: inherit;
    }

    /* Vision block */
    .vm-row {
        display: flex;
        align-items: center;
        gap: 2.5rem;
        padding: 2.5rem 0;
        flex-wrap: wrap;
    }

    .vm-row.reverse {
        flex-direction: row-reverse;
    }

    .vm-row img {
        width: 45%;
        max-width: 500px;
        min-width: 240px;
        flex-shrink: 0;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 6px 24px rgba(30, 58, 95, 0.18);
    }

    .vm-text {
        flex: 1;
        min-width: 220px;
    }

    .vm-text .vm-label {
        font-size: clamp(1.6rem, 4vw, 2.4rem);
        font-weight: 900;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #ceddf1;
        margin-bottom: 1rem;
    }

    .vm-text .vm-body {
        font-size: 1.5rem;
        color: #dbe0e6;
        line-height: 1.75;
    }

    .vm-divider {
        height: 1.5px;
        background: #cbd5e1;
        margin: 0;
    }

    @media (max-width: 640px) {

        .vm-row,
        .vm-row.reverse {
            flex-direction: column;
        }

        .vm-row img {
            width: 100%;
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="vm-page">

    <!-- Vision -->
    <div class="vm-row">
        <img src="{{ asset('images/vision_image.webp') }}" alt="Samuel Christian College Building">
        <div class="vm-text">
            <div class="vm-label">Vision</div>
            <div class="vm-body">A Mark of Excellence, A Testimony of Faith and A Heart of Service.</div>
        </div>
    </div>

    <div class="vm-divider"></div>

    <!-- Mission -->
    <div class="vm-row reverse">
        <img src="{{ asset('images/mission_image.webp') }}" alt="Samuel Christian College Campus">
        <div class="vm-text">
            <div class="vm-label">Mission</div>
            <div class="vm-body">Samuel Christian College is a learning institution committed to provide holistic life education of excellence for the service of God and men.</div>
        </div>
    </div>

</div>
@endsection