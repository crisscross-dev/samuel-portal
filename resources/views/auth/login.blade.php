<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SCC Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/scc_logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: url('{{ asset("images/background.png") }}') no-repeat center center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
                "Helvetica Neue", Arial, sans-serif !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/css/login.css'])
</head>

<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <!-- Default Login Branding -->
            <div class="branding-content">
                <div class="clinic-logo">
                    <img src="{{ asset('images/scc_logo.png') }}" alt="SCC Logo" width="75" height="75">
                </div>
                <h1>Samuel Christian College General Trias Inc.</h1>
                <p>School Management System</p>
                <p>Secure access to academic records, enrollment, grades, and school administration.</p>
            </div>
            <!-- Forgot Password Branding -->
            <div class="forgot-branding">
                <div class="clinic-logo">
                    <i class="bi bi-key-fill" style="font-size: 1.5rem;"></i>
                </div>
                <h1>Reset Password</h1>
                <p>Account Recovery</p>
                <p>Enter your email and we'll send you a link to reset your password.</p>
            </div>
        </div>

        <!-- Right Side - Forms -->
        <div class="login-right">
            <div class="form-container">
                <!-- Login Form -->
                <form class="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <h2>Welcome Samuelians</h2>
                    <p class="login-subtitle">Sign in with your email address</p>

                    @if ($errors->any())
                    <div class="alert alert-danger" id="login-error-alert" style="display: flex; flex-direction: column; gap: 0.3rem; margin-bottom: 1rem; padding: 0.75rem 1rem; border-radius: 8px; background: #fef2f2; border: 1px solid #fca5a5; color: #b91c1c; font-size: 0.875rem;">
                        @foreach ($errors->all() as $error)
                        <div style="display: flex; align-items: center;">
                            <i class="bi bi-exclamation-triangle-fill" style="color: #b71c1c; margin-right: 0.5rem;"></i>
                            <span>{{ $error }}</span>
                        </div>
                        @endforeach
                    </div>
                    <script>
                        setTimeout(function() {
                            var alert = document.getElementById('login-error-alert');
                            if (alert) {
                                alert.style.transition = 'opacity 0.5s ease';
                                alert.style.opacity = 0;
                                setTimeout(function() {
                                    alert.remove();
                                }, 600);
                            }
                        }, 5000);
                    </script>
                    @endif

                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="">
                            <i class="bi bi-envelope-fill input-icon"></i>
                        </div>
                        <label for="email">Email Address</label>
                    </div>

                    <div class="form-group">
                        <div class="input-wrapper password-field-wrapper">
                            <input type="password" id="password" name="password" class="form-control"
                                required autocomplete="current-password" placeholder="">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                        <label for="password">Password</label>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right" style="margin-right: 0.5rem;"></i>
                        Sign In
                    </button>

                    <div class="forgot-password">
                        <a href="#" id="forgot-password-link">
                            <i class="bi bi-key" style="margin-right: 0.25rem; font-size: 0.8rem;"></i>
                            Forgot your password?
                        </a>
                    </div>

                    <div class="back-to-home">
                        <a href="{{ url('/') }}">
                            <i class="bi bi-arrow-left" style="margin-right: 0.25rem; font-size: 0.8rem;"></i>
                            Back to Home
                        </a>
                    </div>
                </form>

                <!-- Forgot Password Form -->
                <form class="forgot-form" method="POST" action="#">
                    @csrf
                    <h2>Reset Password</h2>
                    <p class="login-subtitle">Enter your email to receive reset instructions</p>

                    <div id="forgot-msg" style="display:none; margin-bottom:1rem;"></div>

                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="email" id="forgot-email" name="email" class="form-control"
                                required autocomplete="email" placeholder="">
                            <i class="bi bi-envelope-fill input-icon"></i>
                        </div>
                        <label for="forgot-email">Email Address</label>
                    </div>

                    <button type="submit" class="btn-login" id="forgot-submit-btn">
                        <span class="btn-text">
                            <i class="bi bi-send" style="margin-right: 0.5rem;"></i>
                            Send Reset Link
                        </span>
                    </button>

                    <div class="processing-message" id="processing-msg">
                        <i class="bi bi-hourglass-split" style="margin-right: 0.25rem;"></i>
                        Processing your request...
                    </div>

                    <div class="back-to-login">
                        <a href="#" id="back-to-login-link">
                            <i class="bi bi-arrow-left" style="margin-right: 0.25rem; font-size: 0.8rem;"></i>
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @vite(['resources/js/login.js'])
</body>

</html>