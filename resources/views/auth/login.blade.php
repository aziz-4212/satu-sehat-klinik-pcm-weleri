<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Satu Sehat - Login</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
        <!-- Custom CSS -->
        <style>
            body {
                background: linear-gradient(120deg, #52c997 0%, #1e90ff 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: 'Source Sans Pro', sans-serif;
            }
            .login-container {
                display: flex;
                background: #fff;
                border-radius: 18px;
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
                overflow: hidden;
                max-width: 950px;
                width: 100%;
                min-height: 540px;
            }
            .login-image {
                flex: 1;
                background: #eafaf3;
                display: flex;
                justify-content: center;
                align-items: center;
                min-width: 350px;
            }
            .login-image video {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 0 18px 18px 0;
            }
            .login-form {
                flex: 1;
                padding: 48px 40px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                background: #fff;
            }
            .login-form .brand {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 1.5rem;
            }
            .login-form .brand-logo {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                background: #52c997;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 2rem;
                font-weight: bold;
            }
            .login-form h1 {
                font-size: 2.1rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
                color: #1e90ff;
            }
            .login-form .desc {
                color: #555;
                font-size: 1.1rem;
                margin-bottom: 2rem;
            }
            .login-form .form-group {
                margin-bottom: 1.2rem;
            }
            .login-form .form-control {
                border-radius: 8px;
                border: 1px solid #d1e7dd;
                padding: 0.75rem 1rem;
                font-size: 1rem;
                background: #f8fafc;
                transition: border-color 0.2s;
            }
            .login-form .form-control:focus {
                border-color: #52c997;
                box-shadow: 0 0 0 2px #52c99733;
            }
            .login-form .btn {
                background: linear-gradient(90deg, #52c997 0%, #1e90ff 100%);
                color: #fff;
                font-weight: 600;
                border: none;
                border-radius: 8px;
                padding: 0.75rem 0;
                font-size: 1.1rem;
                transition: background 0.2s;
                box-shadow: 0 2px 8px 0 rgba(31, 38, 135, 0.08);
            }
            .login-form .btn:hover {
                background: linear-gradient(90deg, #1e90ff 0%, #52c997 100%);
            }
            .login-form .register-link {
                text-align: center;
                margin-top: 1.5rem;
                color: #888;
            }
            .login-form .register-link a {
                color: #1e90ff;
                text-decoration: underline;
            }
            @media (max-width: 900px) {
                .login-container {
                    flex-direction: column;
                    min-height: 0;
                }
                .login-image {
                    min-width: 0;
                    height: 180px;
                }
                .login-image video {
                    border-radius: 18px 18px 0 0;
                }
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="login-image">
                <video autoplay loop muted>
                    <source src="{{ asset('video/satset-video-ilustration.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="login-form">
                <div class="brand">
                    <div class="brand-logo">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <span style="font-size:1.5rem;font-weight:700;color:#222;">Satu Sehat</span>
                </div>
                <h1>Selamat Datang 👋</h1>
                <div class="desc">
                    Tempat input data rekam medis ke platform <b>Satu Sehat</b> Kementerian Kesehatan RI.<br>
                    Silakan login untuk melanjutkan.
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>{{ session('error') }}</strong>
                    </div>
                @endif
                <form action="{{ route('login') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Username" id="username" name="username" required autofocus>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-block mt-2">Log In</button>
                </form>
                <div class="register-link">
                    <small>© {{ date('Y') }} Satu Sehat - Kementerian Kesehatan RI</small>
                </div>
            </div>
        </div>
    </body>
</html>
