<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Login </title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #f72585;
            --light-bg: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --input-focus-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        body {
            background-color: var(--light-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(67, 97, 238, 0.1), rgba(58, 12, 163, 0.05));
            z-index: -1;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(247, 37, 133, 0.1), rgba(67, 97, 238, 0.05));
            z-index: -1;
        }

        .login-container {
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .logo-container {
            margin-bottom: 30px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .logo-container:hover {
            transform: translateY(-5px);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-bottom: none;
            padding: 20px 25px;
            position: relative;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), rgba(247, 37, 133, 0.5));
        }

        .card-body {
            padding: 30px;
        }

        .form-title {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 24px;
        }

        .form-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group-text {
            background-color: white;
            border-right: none;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
            color: var(--primary-color);
        }

        .form-control {
            border-left: none;
            padding-left: 5px;
            height: 45px;
            border-radius: 0 8px 8px 0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: var(--input-focus-shadow);
            border-color: #ced4da;
        }

        .form-control:focus + .input-group-append .input-group-text {
            border-color: #ced4da;
        }

        .btn-login {
            background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            height: 45px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .developer-credit {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 14px;
        }

        .developer-link {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .developer-link:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .spinner-border {
            width: 20px;
            height: 20px;
            margin-left: 8px;
        }

        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }

            .card-body {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
<div class="login-container">
    <div class="logo-container">
        <img width="180" src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid">
    </div>

    @if(Session::has('account_deactivated'))
        <div class="alert alert-danger mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ Session::get('account_deactivated') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h1 class="form-title">Welcome Back</h1>
            <p class="form-subtitle">Sign in to continue to your account</p>
        </div>
        <div class="card-body">
            <form id="login" method="post" action="{{ url('/login') }}">
                @csrf
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                    </div>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}"
                           placeholder="Email address">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                    </div>
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Password" name="password">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button id="submit" class="btn btn-login px-4 d-flex align-items-center text-white"
                            type="submit">
                        Sign In
                        <div id="spinner" class="spinner-border text-light" role="status" style="display: none;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>

                    <a class="forgot-link" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                </div>
            </form>
        </div>
    </div>

    <p class="developer-credit mt-4">
        Developed by <a href="https://playon24.com.bd" class="developer-link">PlayOn 24</a>
    </p>
</div>

<!-- CoreUI -->
<script src="{{ mix('js/app.js') }}" defer></script>
<script>
    let login = document.getElementById('login');
    let submit = document.getElementById('submit');
    let email = document.getElementById('email');
    let password = document.getElementById('password');
    let spinner = document.getElementById('spinner')

    login.addEventListener('submit', (e) => {
        submit.disabled = true;
        email.readonly = true;
        password.readonly = true;

        spinner.style.display = 'inline-block';

        // Change button text while loading
        submit.innerHTML = 'Signing In ' + spinner.outerHTML;

        login.submit();
    });

    // Fallback in case form submission fails
    setTimeout(() => {
        submit.disabled = false;
        email.readonly = false;
        password.readonly = false;

        submit.innerHTML = 'Sign In';
        spinner.style.display = 'none';
    }, 5000);
</script>

</body>
</html>
