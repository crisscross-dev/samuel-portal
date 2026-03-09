@extends('layouts.app-index')

@section('title', 'SCCGTI Virtual Tour')

@push('styles')
<style>
    .tour-page {
        font-family: inherit;
        padding: 2rem 0 3rem;
    }

    .tour-heading {
        font-size: clamp(1.5rem, 3.5vw, 2.2rem);
        font-weight: 900;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #f0f4f8;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .tour-video-wrapper {
        position: relative;
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        padding-bottom: min(56.25%, 450px);
        /* 16:9, capped */
        height: 0;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    }

    .tour-video-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
</style>
@endpush

@section('content')
<div class="tour-page">

    <div class="tour-heading">SCCGTI Virtual Tour</div>

    <div class="tour-video-wrapper">
        <iframe
            src="https://player.vimeo.com/video/1135578698?badge=0&byline=0&h=ebda21c6ab&portrait=0&title=0&autoplay=1&loop=1&muted=1&controls=0"
            allow="autoplay; fullscreen; picture-in-picture"
            allowfullscreen
            title="SCCGTI Virtual Tour">
        </iframe>
    </div>

</div>
@endsection