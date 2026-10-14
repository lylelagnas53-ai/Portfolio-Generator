<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $portfolio->full_name }} | Portfolio</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="portfolio-page portfolio-{{ $template }}">
    <header class="site-header preview-nav">
        <a href="{{ route('home', [], false) }}" class="brand">
            <span class="brand-mark" aria-hidden="true">P</span>
            <span class="brand-copy"><span class="brand-name">PORTIVAGEN</span><span class="brand-subtitle">PORTFOLIO BUILDER</span></span>
        </a>
        <a href="{{ route('portfolios.index', [], false) }}" class="text-link no-print">Manage portfolios</a>
    </header>

    <main class="portfolio-canvas {{ $template }}-canvas">
        @include("templates.$template")
    </main>

    <nav class="preview-actions no-print" aria-label="Portfolio actions">
        <a href="{{ route('portfolios.edit', $portfolio->id, false) }}" class="button button-secondary">Edit information</a>
        <a href="{{ route('templates.index', $portfolio->id, false) }}" class="button button-secondary">Change design</a>
        <button type="button" class="button" onclick="window.print()">Print / Save as PDF</button>
    </nav>
</body>
</html>
