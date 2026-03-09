@extends('layouts.app-index')

@section('title', 'Educational Philosophy')

@push('styles')
<style>
    .phil-page {
        font-family: inherit;
        padding: 2.5rem 0;
    }

    .phil-row {
        display: flex;
        align-items: center;
        gap: 2.5rem;
        flex-wrap: wrap;
    }

    .phil-row img {
        width: 45%;
        max-width: 480px;
        min-width: 240px;
        flex-shrink: 0;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 6px 24px rgba(30, 58, 95, 0.18);
    }

    .phil-text {
        flex: 1;
        min-width: 220px;
    }

    .phil-text .phil-label {
        font-size: clamp(1.6rem, 4vw, 2.4rem);
        font-weight: 900;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #e6eaee;
        margin-bottom: 1rem;
    }

    .phil-text .phil-body {
        font-size: 1.05rem;
        color: #e1e4e9;
        line-height: 1.85;
    }

    @media (max-width: 640px) {
        .phil-row {
            flex-direction: column;
        }

        .phil-row img {
            width: 100%;
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="phil-page">

    <div class="phil-row">
        <img src="{{ asset('images/philosophy.webp') }}" alt="Samuel Christian College Campus">
        <div class="phil-text">
            <div class="phil-label">Philosophy</div>
            <div class="phil-body">Samuel Christian College (SCC) believes that every learner is an individual with a God-centered purpose that is honed by a positive school culture upholding Christian faith and values, excellence in character and work, and commitment to serve God and men through life education in global perspectives with strong dignity and pride as Filipinos.</div>
        </div>
    </div>

</div>
@endsection