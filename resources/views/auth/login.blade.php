<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Welcome to Admin Login</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url("https://t3.ftcdn.net/jpg/04/99/59/06/360_F_499590698_EU8lygMrqcmlstDETLFpZWmQ5zvOh8kw.jpg") no-repeat center center/cover;
            position: relative;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Blurry overlay */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(8px);
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 0 20px;
        }

        .login-box {
            position: relative;
            z-index: 1;
            width: 100%;
            padding: 45px 35px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            color: #fff;
            animation: fadeIn 0.9s ease;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header img {
            width: 70px;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }

        .login-header h2 {
            margin: 0 0 8px 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .login-header p {
            font-size: 15px;
            opacity: 0.85;
            margin: 0;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            z-index: 2;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: #fff;
            height: 52px;
            padding-left: 45px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            z-index: 2;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            background: linear-gradient(135deg, #ff4b21, #ae000e);
            color: #fff;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .btn-login:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.2s ease;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: #fff;
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
            border: none;
            padding: 12px 15px;
            margin-bottom: 25px;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            color: #fff;
            backdrop-filter: blur(10px);
        }

        .developer-credit {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .developer-credit a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.2s ease;
        }

        .developer-credit a:hover {
            text-decoration: underline;
            color: #4096ff;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }

            100% {
                transform: scale(30, 30);
                opacity: 0;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .login-box {
                padding: 35px 25px;
            }

            .login-header h2 {
                font-size: 22px;
            }

            .login-header p {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img src="{{ asset('images/logo.png') }}" alt="Sales Admin Logo">
                <h2>Welcome to Admin Login</h2>
                <p>Secure access to your sales dashboard</p>
            </div>

            @if(Session::has('account_deactivated'))
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ Session::get('account_deactivated') }}
            </div>
            @endif

            <form id="login" method="post" action="{{ url('/login') }}">
                @csrf
                <div class="form-group">
                    <i class="bi bi-envelope input-icon" style="color: black;"></i>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" placeholder="Email Address" autocomplete="email" autofocus>
                    @error('email')
                    <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <i class="bi bi-lock input-icon" style="color: black;"></i>
                    <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password" name="password" autocomplete="current-password">
                    <button type="button" class="password-toggle" id="passwordToggle" style="border-left: 1px solid darkslategray;">
                        <i class="bi bi-eye" style="color: black;"></i>
                    </button>
                    @error('password')
                    <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <button id="submit" class="btn btn-login d-flex justify-content-center align-items-center" type="submit">
                    <span id="button-text">Sign In</span>
                    <div id="spinner" class="spinner-border text-light ms-2" role="status" style="display: none;">
                        <span class="visually-hidden"></span>
                    </div>
                </button>

            </form>

            <div class="developer-credit">
                Developed by <a href="https://playon24.com.bd" target="_blank" style="color: #0D50A1;">PlayOn<span style="color: orangered;">24</span></a>
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let login = document.getElementById('login');
            let submit = document.getElementById('submit');
            let email = document.getElementById('email');
            let password = document.getElementById('password');
            let spinner = document.getElementById('spinner');
            let buttonText = document.getElementById('button-text');
            let passwordToggle = document.getElementById('passwordToggle');

            // Password toggle functionality
            passwordToggle.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="bi bi-eye"style="color: black;"></i>' : '<i class="bi bi-eye-slash"style="color: black;"></i>';
            });

            // Form submission handler
            login.addEventListener('submit', function(e) {
                submit.disabled = true;
                email.readOnly = true;
                password.readOnly = true;

                spinner.style.display = 'inline-block';
                buttonText.textContent = 'Signing In';

                // Auto reset after 5 seconds in case of error
                setTimeout(() => {
                    submit.disabled = false;
                    email.readOnly = false;
                    password.readOnly = false;
                    buttonText.textContent = 'Sign In';
                    spinner.style.display = 'none';
                }, 5000);
            });
        });
    </script>
</body>

</html>
