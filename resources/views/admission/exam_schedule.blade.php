<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Exam Schedule – SCC Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/scc_logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/index.css'])
    <style>
        body {
            background-image: url('{{ asset("images/background.png") }}');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .sched-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(13, 31, 60, 0.18);
            overflow: hidden;
            max-width: 620px;
            margin: 0 auto;
        }

        .sched-header {
            background: linear-gradient(135deg, #0d1f3c 0%, #1a5276 100%);
            padding: 1.5rem 2rem;
            color: #fff;
        }

        .sched-header .app-id-badge {
            display: inline-block;
            background: rgba(241, 196, 15, 0.18);
            border: 1px solid #f1c40f;
            color: #f1c40f;
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding: 0.2rem 0.85rem;
            border-radius: 20px;
            margin-top: 0.35rem;
        }

        /* Schedule option card */
        .sched-option {
            display: flex;
            align-items: center;
            gap: 1.1rem;
            border: 2.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            background: #fff;
            user-select: none;
        }

        .sched-option:hover {
            border-color: #1a5276;
            background: #f0f7ff;
        }

        .sched-option.selected {
            border-color: #1e3a5f;
            background: linear-gradient(135deg, #eef4fb, #dbeafe);
            box-shadow: 0 4px 18px rgba(30, 58, 95, 0.13);
        }

        .sched-option input[type="radio"] {
            display: none;
        }

        .sched-radio-circle {
            width: 26px;
            height: 26px;
            min-width: 26px;
            border-radius: 50%;
            border: 2.5px solid #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color 0.2s, background 0.2s;
            flex-shrink: 0;
        }

        .sched-option.selected .sched-radio-circle {
            border-color: #1e3a5f;
            background: #1e3a5f;
        }

        .sched-radio-circle::after {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #fff;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .sched-option.selected .sched-radio-circle::after {
            opacity: 1;
        }

        .sched-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .sched-icon.morning {
            background: linear-gradient(135deg, #fef9c3, #fde68a);
            color: #b45309;
        }

        .sched-icon.afternoon {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1d4ed8;
        }

        .sched-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0d1f3c;
            margin-bottom: 0.15rem;
        }

        .sched-sub {
            font-size: 0.82rem;
            color: #64748b;
        }

        .sched-tag {
            margin-left: auto;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 0.2rem 0.65rem;
            border-radius: 20px;
            white-space: nowrap;
        }

        .sched-tag.morning {
            background: #fef9c3;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .sched-tag.afternoon {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .sched-option.sched-full {
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="container py-3">

        <!-- Topbar -->
        <div class="topbar">
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-right-to-bracket"></i> Login
            </a>
        </div>

        <!-- Header -->
        <div class="header py-3">
            <div class="clinic-logo">
                <img src="{{ asset('images/scc_logo.png') }}" alt="SCC Logo" />
            </div>
            <h1>Samuel Christian College</h1>
            <h2>Entrance Exam Schedule</h2>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="max-width:620px; margin-left:auto; margin-right:auto;">
            @foreach($errors->all() as $error)
            <div><small><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</small></div>
            @endforeach
        </div>
        @endif

        <div class="sched-card">

            <!-- Header -->
            <div class="sched-header">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-calendar-days fa-2x opacity-75"></i>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $application->fullName() }}</h5>
                        <span class="app-id-badge">
                            <i class="fas fa-hashtag me-1"></i>{{ $application->app_id }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <p class="fw-semibold mb-1" style="color:#0d1f3c; font-size:1rem;">
                    <i class="fas fa-clock me-1"></i> Choose Your Entrance Exam Schedule
                </p>
                <p class="text-muted small mb-4">
                    Select your preferred time slot. Both schedules are held on <strong>Saturday</strong>.
                    Please arrive at least 15 minutes before your chosen time.
                </p>

                <form method="POST" action="{{ route('admission.exam-schedule.store', $application->app_id) }}" id="schedForm">
                    @csrf

                    <div class="d-flex flex-column gap-3 mb-4">

                        @forelse($schedules as $sched)
                        @php
                        $sid = $sched->id;
                        $is9am = $sched->time_slot === '9am';
                        $booked = $sched->applications_count;
                        $available = max(0, $sched->max_capacity - $booked);
                        $full = $available === 0;
                        $selected = old('exam_schedule_id') == $sid;
                        @endphp
                        <label @class(['sched-option', 'opacity-50'=> $full, 'selected' => $selected, 'sched-full' => $full])
                            id="opt-{{ $sid }}" for="sched_{{ $sid }}">
                            <input type="radio" id="sched_{{ $sid }}" name="exam_schedule_id"
                                value="{{ $sid }}"
                                @checked($selected)
                                @disabled($full)>
                            <div class="sched-radio-circle"></div>
                            <div class="sched-icon {{ $is9am ? 'morning' : 'afternoon' }}">
                                <i class="fas fa-{{ $is9am ? 'sun' : 'cloud-sun' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="sched-title">
                                    {{ $sched->exam_date->format('l, F j, Y') }}
                                </div>
                                <div class="sched-sub">
                                    {{ $is9am ? '9:00 AM – Morning session' : '1:00 PM – Afternoon session' }}
                                    &nbsp;·&nbsp;
                                    @if($full)
                                    <span style="color:#dc2626; font-weight:600;">Full</span>
                                    @elseif($available <= 5)
                                        <span style="color:#d97706; font-weight:600;">{{ $available }} slots left</span>
                                        @else
                                        {{ $available }} slots available
                                        @endif
                                </div>
                            </div>
                            <span class="sched-tag {{ $is9am ? 'morning' : 'afternoon' }}">
                                {{ $is9am ? 'Morning' : 'Afternoon' }}
                            </span>
                        </label>
                        @empty
                        <div class="alert alert-warning text-center py-3">
                            <i class="fas fa-calendar-xmark fa-lg d-block mb-2"></i>
                            <strong>No exam schedules are currently available.</strong><br>
                            <small class="text-muted">Please check back later or contact the school for assistance.</small>
                        </div>
                        @endforelse

                    </div>

                    <div class="alert alert-info small py-2 mb-4">
                        <i class="fas fa-circle-info me-1"></i>
                        Bring a valid school ID or any government-issued ID on the day of the exam.
                    </div>

                    <button type="submit" id="submitBtn"
                        class="btn btn-primary w-100 fw-semibold py-2 fs-6" {{ $schedules->isEmpty() ? 'disabled' : 'disabled' }}>
                        <i class="fas fa-check-circle me-2"></i> Confirm Schedule
                    </button>
                </form>
            </div>

        </div>

        <!-- Footer links -->
        <div class="text-center py-3">
            <a href="{{ route('admission.track') }}" class="text-decoration-none small" style="color:rgba(255,255,255,0.9);">
                <i class="fas fa-magnifying-glass me-1"></i> Track Application
            </a>
            <span class="mx-2" style="color:rgba(255,255,255,0.4);">|</span>
            <a href="{{ route('login') }}" class="text-decoration-none small" style="color:rgba(255,255,255,0.9);">
                <i class="fas fa-right-to-bracket me-1"></i> Sign In
            </a>
        </div>
        <div class="text-center pb-3">
            <small style="color:rgba(255,255,255,0.55);">&copy; {{ date('Y') }} SCC Portal. All rights reserved.</small>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const radios = document.querySelectorAll('input[name="exam_schedule_id"]');
        const options = document.querySelectorAll('.sched-option');
        const btn = document.getElementById('submitBtn');

        function refreshUI() {
            options.forEach(opt => {
                const radio = opt.querySelector('input[type="radio"]');
                if (radio) opt.classList.toggle('selected', radio.checked);
            });
            btn.disabled = ![...radios].some(r => r.checked);
        }

        radios.forEach(r => r.addEventListener('change', refreshUI));
        refreshUI();
    </script>
</body>

</html>