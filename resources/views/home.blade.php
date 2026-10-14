<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4fff7">
    <title>Portivagen | Portfolio Builder</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <header class="site-header">
        <a href="{{ route('home', [], false) }}" class="brand" aria-label="Portivagen home">
            <span class="brand-mark" aria-hidden="true">P</span>
            <span class="brand-copy">
                <span class="brand-name">PORTIVAGEN</span>
                <span class="brand-subtitle">PORTFOLIO BUILDER</span>
            </span>
        </a>
        <nav class="site-nav" aria-label="Main navigation">
            <a href="{{ route('portfolios.index', [], false) }}">Manage portfolios</a>
            <a href="{{ route('portfolios.create', [], false) }}" class="button button-small">Get started</a>
        </nav>
    </header>

    <main class="home-hero">
        <div class="hero-orb hero-orb-one" aria-hidden="true"></div>
        <div class="hero-orb hero-orb-two" aria-hidden="true"></div>
        <div class="hero-content">
            <p class="eyebrow"><span class="eyebrow-dot"></span> YOUR NEXT CHAPTER STARTS HERE</p>
            <h1>Your work deserves<br>a <span>beautiful portfolio.</span></h1>
            <p class="hero-description">
                Bring your experience, skills, and projects together in one professional space.
                Add your details once, then choose a style that feels like you.
            </p>
            <div class="hero-actions">
                <a href="{{ route('portfolios.create', [], false) }}" class="button">
                    <span aria-hidden="true">✦</span>
                    Generate a Portfolio
                    <span aria-hidden="true">→</span>
                </a>
                <a href="{{ route('portfolios.index', [], false) }}" class="text-link">Manage saved portfolios</a>
            </div>
        </div>
        <div class="hero-card" aria-label="Portfolio design preview">
            <div class="hero-card-top"><span></span><span></span><span></span></div>
            <div class="hero-card-content">
                <div class="hero-avatar">P</div>
                <div class="hero-card-title"></div>
                <div class="hero-card-subtitle"></div>
                <div class="hero-card-rule"></div>
                <div class="hero-card-label"></div>
                <div class="hero-card-line"></div>
                <div class="hero-card-line hero-card-line-short"></div>
                <div class="hero-card-grid"><span></span><span></span><span></span></div>
            </div>
            <span class="floating-sparkle sparkle-one" aria-hidden="true">✦</span>
            <span class="floating-sparkle sparkle-two" aria-hidden="true">✧</span>
        </div>
    </main>

    <footer class="site-footer">
        <span>© {{ date('Y') }} Portivagen</span>
        <span>Make your next move memorable.</span>
    </footer>
</body>
</html>
