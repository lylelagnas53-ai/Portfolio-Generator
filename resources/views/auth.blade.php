@php
    $page = $page ?? 'login';
    $isRegister = $page === 'register';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isRegister ? 'Create Account' : 'Sign In' }} | PortivaGen</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            color: #26352b;
            background: #f6f7f4;
        }

        .auth-layout {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 43% 57%;
        }

        .auth-side {
            padding: 42px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            background: #174c32;
        }

        .auth-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 43px;
            height: 43px;
            border: 1px solid #8db99a;
            border-radius: 12px;
            font-size: 23px;
        }

        .brand-small {
            display: block;
            margin-top: 5px;
            color: #c2ddca;
            font-size: 9px;
            letter-spacing: 1px;
        }

        .side-content { max-width: 440px; }

        .side-content .tag {
            color: #c2e5c8;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .side-content h1 {
            margin: 22px 0;
            font-size: clamp(38px, 4.4vw, 60px);
            line-height: 1.13;
            letter-spacing: -2px;
        }

        .side-content h1 span { color: #a9d8ad; }

        .side-content p {
            color: #d5e5d9;
            font-size: 14px;
            line-height: 1.9;
        }

        .preview {
            margin-top: 35px;
            padding: 23px;
            border-radius: 15px;
            background: #fff;
            color: #31583d;
            transform: rotate(-2deg);
            box-shadow: 0 20px 50px #103a2880;
        }

        .preview-top {
            padding-bottom: 13px;
            border-bottom: 1px solid #e6ece6;
            color: #7d8c7f;
            font-size: 10px;
            letter-spacing: 2px;
        }

        .preview-profile {
            display: flex;
            align-items: center;
            gap: 13px;
            margin: 20px 0;
        }

        .avatar {
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #dcefe0;
            color: #267449;
            font-size: 22px;
            font-weight: bold;
        }

        .fake-line {
            height: 8px;
            width: 140px;
            margin: 8px 0;
            border-radius: 8px;
            background: #dfe8df;
        }

        .fake-line.short { width: 90px; }

        .project-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 9px;
            margin-top: 20px;
        }

        .project-row span {
            height: 52px;
            border-radius: 6px;
            background: #dcefe0;
        }

        .side-footer {
            color: #c2ddca;
            font-size: 12px;
        }

        .auth-main {
            display: grid;
            place-items: center;
            padding: 45px 25px;
        }

        .auth-card { width: 100%; max-width: 420px; }

        .back-link {
            display: inline-block;
            margin-bottom: 36px;
            color: #55705d;
            font-size: 13px;
            text-decoration: none;
        }

        .eyebrow {
            color: #28784b;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .auth-card h2 {
            margin: 12px 0;
            font-size: 33px;
            letter-spacing: -1px;
        }

        .intro {
            margin-bottom: 28px;
            color: #7b857d;
            font-size: 14px;
            line-height: 1.7;
        }

        .form-field { margin-bottom: 19px; }

        .form-field label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        .form-field input {
            width: 100%;
            height: 48px;
            padding: 0 13px;
            border: 1px solid #dce3dc;
            border-radius: 7px;
            outline: none;
            background: white;
            font: inherit;
            font-size: 13px;
        }

        .form-field input:focus {
            border-color: #28784b;
            box-shadow: 0 0 0 3px #28784b19;
        }

        .password-wrap { position: relative; }

        .password-wrap input { padding-right: 65px; }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            border: 0;
            background: none;
            color: #28784b;
            font-weight: 700;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 4px 0 22px;
            color: #657168;
            font-size: 12px;
        }

        .remember input { accent-color: #28784b; }

        .submit-btn {
            width: 100%;
            min-height: 49px;
            border: 0;
            border-radius: 7px;
            background: #28784b;
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .submit-btn:hover { background: #195b37; }

        .switch-link {
            margin-top: 25px;
            text-align: center;
            color: #7b857d;
            font-size: 13px;
        }

        .switch-link a {
            color: #28784b;
            font-weight: 700;
            text-decoration: none;
        }

        .error {
            display: block;
            margin-top: 6px;
            color: #b42318;
            font-size: 12px;
        }

        .notice {
            margin-bottom: 18px;
            padding: 12px;
            border-radius: 7px;
            background: #e7f5e9;
            color: #21613a;
            font-size: 13px;
        }

        .error-box {
            margin-bottom: 18px;
            padding: 12px;
            border-radius: 7px;
            background: #fff0ef;
            color: #a52821;
            font-size: 13px;
        }

        @media (max-width: 800px) {
            .auth-layout { grid-template-columns: 1fr; }
            .auth-side { display: none; }
            .auth-main { min-height: 100vh; padding: 30px 22px; }
            .back-link { margin-bottom: 28px; }
            .auth-card h2 { font-size: 29px; }
        }
    </style>
</head>

<body>
<div class="auth-layout">

    <aside class="auth-side">
        <a href="{{ route('home', [], false) }}" class="auth-brand">
            <span class="brand-icon">P</span>
            <span>
                PORTIVAGEN
                <span class="brand-small">PORTFOLIO BUILDER</span>
            </span>
        </a>

        <div class="side-content">
            <span class="tag">YOUR NEXT CHAPTER STARTS HERE</span>

            <h1>Your work.<br>Your story.<br><span>Your future.</span></h1>

            <p>
                Organize your skills, experience, and projects in one
                professional portfolio. Create something that represents you.
            </p>

            <div class="preview">
                <div class="preview-top">PORTFOLIO PREVIEW</div>
                <div class="preview-profile">
                    <div class="avatar">P</div>
                    <div>
                        <div class="fake-line"></div>
                        <div class="fake-line short"></div>
                    </div>
                </div>
                <strong>ABOUT ME</strong>
                <div class="fake-line"></div>
                <div class="fake-line short"></div>
                <div class="project-row">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>

        <div class="side-footer">A portfolio that opens doors.</div>
    </aside>

    <main class="auth-main">
        <section class="auth-card">

            <a href="{{ route('home', [], false) }}" class="back-link">← Back to homepage</a>

            @if (session('success'))
                <div class="notice" role="status">{{ session('success') }}</div>
            @endif

            @if (session('status'))
                <div class="notice" role="status">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="error-box" role="alert">
                    Please correct the errors below.
                </div>
            @endif

            <div class="eyebrow">
                {{ $isRegister ? 'GET STARTED' : 'WELCOME BACK' }}
            </div>

            <h2>{{ $isRegister ? 'Create your account' : 'Sign in to your account' }}</h2>

            <p class="intro">
                {{ $isRegister
                    ? 'Enter your details to start building your portfolio.'
                    : 'Welcome back! Enter your details to continue.' }}
            </p>

            @if ($isRegister)
                <form method="POST" action="{{ route('register.submit', [], false) }}">
                    @csrf

                    <div class="form-field">
                        <label for="name">Full name</label>
                        <input id="name" name="name" type="text"
                               value="{{ old('name') }}" autocomplete="name"
                               maxlength="255" required autofocus>
                        @error('name') <small class="error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-field">
                        <label for="email">Email address</label>
                        <input id="email" name="email" type="email"
                               value="{{ old('email') }}" autocomplete="email" required>
                        @error('email') <small class="error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-field">
                        <label for="password">Password</label>
                        <div class="password-wrap">
                            <input id="password" name="password" type="password"
                                   minlength="8" autocomplete="new-password" required>
                            <button class="toggle-password" type="button"
                                    data-toggle="password">Show</button>
                        </div>
                        @error('password') <small class="error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-field">
                        <label for="password_confirmation">Confirm password</label>
                        <div class="password-wrap">
                            <input id="password_confirmation" name="password_confirmation"
                                   type="password" minlength="8"
                                   autocomplete="new-password" required>
                            <button class="toggle-password" type="button"
                                    data-toggle="password_confirmation">Show</button>
                        </div>
                    </div>

                    <button class="submit-btn" type="submit">Create account →</button>
                </form>

                <p class="switch-link">
                    Already have an account?
                    <a href="{{ route('login', [], false) }}">Sign in</a>
                </p>
            @else
                <form method="POST" action="{{ route('login.submit', [], false) }}">
                    @csrf

                    <div class="form-field">
                        <label for="email">Email address</label>
                        <input id="email" name="email" type="email"
                               value="{{ old('email') }}" autocomplete="email"
                               required autofocus>
                        @error('email') <small class="error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-field">
                        <label for="password">Password</label>
                        <div class="password-wrap">
                            <input id="password" name="password" type="password"
                                   autocomplete="current-password" required>
                            <button class="toggle-password" type="button"
                                    data-toggle="password">Show</button>
                        </div>
                        @error('password') <small class="error">{{ $message }}</small> @enderror
                    </div>

                    <label class="remember">
                        <input type="checkbox" name="remember" value="1"
                               @checked(old('remember'))>
                        Remember me
                    </label>

                    <button class="submit-btn" type="submit">Sign in →</button>
                </form>

                <p class="switch-link">
                    Don't have an account?
                    <a href="{{ route('register', [], false) }}">Create an account</a>
                </p>
            @endif
        </section>
    </main>
</div>

<script>
    document.querySelectorAll('[data-toggle]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.toggle);
            if (!input) return;

            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.textContent = show ? 'Hide' : 'Show';
            button.setAttribute('aria-pressed', String(show));
        });
    });
</script>
</body>
</html>