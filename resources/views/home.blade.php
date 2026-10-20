<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4fff7">
    <title>Portivagen | Portfolio Builder</title>

    <link rel="stylesheet" href="/css/app.css">

    <style>
        /* ========================================
           DARK MODE TOGGLE
        ======================================== */
        .theme-toggle {
            width: 42px;
            height: 42px;
            display: inline-grid;
            place-items: center;
            flex-shrink: 0;
            border: 1px solid #cbdccc;
            border-radius: 50%;
            background: #ffffff;
            color: #173d28;
            cursor: pointer;
            font-size: 19px;
            transition: background .2s, transform .2s;
        }

        .theme-toggle:hover {
            transform: translateY(-2px);
        }

        .site-nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        /* ========================================
           DARK THEME
        ======================================== */
        html[data-theme="dark"] body {
            background: #101a14;
            color: #edf5ee;
        }

        html[data-theme="dark"] .site-header,
        html[data-theme="dark"] .site-footer {
            background: #101a14;
            color: #edf5ee;
            border-color: #2b3b30;
        }

        html[data-theme="dark"] .brand-name,
        html[data-theme="dark"] .site-nav a:not(.button),
        html[data-theme="dark"] .site-nav button {
            color: #edf5ee;
        }

        html[data-theme="dark"] .home-hero {
            background: #101a14;
            color: #edf5ee;
        }

        html[data-theme="dark"] .hero-description {
            color: #bdcdbf;
        }

        html[data-theme="dark"] .hero-card {
            background: #1c2a20;
            border-color: #344a39;
        }

        html[data-theme="dark"] .hero-card-content {
            background: #1c2a20;
        }

        html[data-theme="dark"] .theme-toggle {
            background: #263b2c;
            border-color: #47654d;
            color: #ffffff;
        }

        html[data-theme="dark"] .button {
            color: #ffffff;
        }

        html[data-theme="dark"] .alert-success {
            background: #1d3a27;
            color: #d7f5df;
        }

        /* Respect smaller screens */
        @media (max-width: 480px) {
            .site-nav {
                gap: 8px;
            }

            .theme-toggle {
                width: 38px;
                height: 38px;
            }
        }
    </style>

    <script>
        /* Apply the saved theme before displaying the page */
        (function () {
            let theme = 'light';

            try {
                theme = localStorage.getItem('portivagen-theme') || 'light';
            } catch (error) {}

            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>

<body>
    <header class="site-header">
        <a href="{{ route('home', [], false) }}"
           class="brand"
           aria-label="Portivagen home">

            <span class="brand-mark" aria-hidden="true">P</span>

            <span class="brand-copy">
                <span class="brand-name">PORTIVAGEN</span>
                <span class="brand-subtitle">PORTFOLIO BUILDER</span>
            </span>
        </a>

        <nav class="site-nav" aria-label="Main navigation">

            <!-- Dark Mode Toggle replaces LOG IN -->
            <button
                type="button"
                class="theme-toggle"
                id="themeToggle"
                aria-label="Switch to dark mode"
                title="Switch theme">

                <span id="themeIcon" aria-hidden="true">🌙</span>
            </button>

            @guest
                <a href="{{ route('register', [], false) }}"
                   class="button button-small">
                    SIGN UP
                </a>
            @else
                <form method="POST"
                      action="{{ route('logout', [], false) }}">
                    @csrf

                    <button type="submit">Sign out</button>
                </form>
            @endguest

        </nav>
    </header>

    @if (session('status'))
        <div class="alert alert-success home-flash" role="status">
            {{ session('status') }}
        </div>
    @endif

    <main class="home-hero">
        <div class="hero-orb hero-orb-one" aria-hidden="true"></div>
        <div class="hero-orb hero-orb-two" aria-hidden="true"></div>

        <div class="hero-content">
            <p class="eyebrow">
                <span class="eyebrow-dot"></span>
                YOUR NEXT CHAPTER STARTS HERE
            </p>

            <h1>
                Your work deserves<br>
                a <span>beautiful portfolio.</span>
            </h1>

            <p class="hero-description">
                Bring your experience, skills, and projects together
                in one professional space. Add your details once,
                then choose a style that feels like you.
            </p>

            <div class="hero-actions">
                <a href="{{ route('portfolios.create', [], false) }}"
                   class="button">

                    <span aria-hidden="true">✦</span>

                    @guest
                        Get Started
                    @else
                        Generate a Portfolio
                    @endguest

                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>

        <div class="hero-card" aria-label="Portfolio design preview">
            <div class="hero-card-top">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="hero-card-content">
                <div class="hero-avatar">P</div>
                <div class="hero-card-title"></div>
                <div class="hero-card-subtitle"></div>
                <div class="hero-card-rule"></div>
                <div class="hero-card-label"></div>
                <div class="hero-card-line"></div>
                <div class="hero-card-line hero-card-line-short"></div>

                <div class="hero-card-grid">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <span class="floating-sparkle sparkle-one"
                  aria-hidden="true">✦</span>

            <span class="floating-sparkle sparkle-two"
                  aria-hidden="true">✧</span>
        </div>
    </main>

    <footer class="site-footer">
        <span>© {{ date('Y') }} Portivagen</span>
        <span>Make your next move memorable.</span>
    </footer>

    <script>
        (function () {
            const root = document.documentElement;
            const toggle = document.getElementById('themeToggle');
            const icon = document.getElementById('themeIcon');
            const themeColor = document.querySelector(
                'meta[name="theme-color"]'
            );

            function applyTheme(theme) {
                root.setAttribute('data-theme', theme);

                if (icon) {
                    icon.textContent = theme === 'dark' ? '☀️' : '🌙';
                }

                if (toggle) {
                    toggle.setAttribute(
                        'aria-label',
                        theme === 'dark'
                            ? 'Switch to light mode'
                            : 'Switch to dark mode'
                    );
                }

                if (themeColor) {
                    themeColor.setAttribute(
                        'content',
                        theme === 'dark' ? '#101a14' : '#f4fff7'
                    );
                }
            }

            let currentTheme = 'light';

            try {
                currentTheme =
                    localStorage.getItem('portivagen-theme') || 'light';
            } catch (error) {}

            applyTheme(currentTheme);

            toggle.addEventListener('click', function () {
                const nextTheme =
                    root.getAttribute('data-theme') === 'dark'
                        ? 'light'
                        : 'dark';

                applyTheme(nextTheme);

                try {
                    localStorage.setItem('portivagen-theme', nextTheme);
                } catch (error) {}
            });
        })();
    </script>
</body>
</html>
