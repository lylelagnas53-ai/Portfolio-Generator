<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choose a design | Portivagen</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="app-page">
    <header class="site-header">
        <a href="{{ route('home', [], false) }}" class="brand">
            <span class="brand-mark" aria-hidden="true">P</span>
            <span class="brand-copy"><span class="brand-name">PORTIVAGEN</span><span class="brand-subtitle">PORTFOLIO BUILDER</span></span>
        </a>
        <nav class="site-nav" aria-label="Main navigation">
            <a href="{{ route('portfolios.index', [], false) }}">Manage portfolios</a>
            <a href="{{ route('portfolios.edit', $portfolio->id, false) }}" class="text-link">Edit information</a>
        </nav>
    </header>

    <main class="content-width template-page">
        @include('partials.flash')

        <div class="page-heading">
            <p class="eyebrow">DESIGN YOUR FIRST IMPRESSION</p>
            <h1>Choose a <span>template</span></h1>
            <p>Your information for <strong>{{ $portfolio->full_name }}</strong> is saved. Pick a look to generate your portfolio.</p>
        </div>

        <form action="{{ route('templates.select', [], false) }}" method="POST">
            @csrf
            <input type="hidden" name="portfolio_id" value="{{ $portfolio->id }}">

            <div class="template-grid">
                <label class="template-card">
                    <input type="radio" name="template" value="simple" @checked(old('template', $selectedTemplate) === 'simple') required>
                    <span class="template-card-heading"><span class="template-radio" aria-hidden="true"></span><span><strong>Simple</strong><small>Clean and professional</small></span></span>
                    <span class="template-mockup mockup-simple" aria-hidden="true">
                        <span class="mockup-simple-title"></span>
                        <span class="mockup-simple-lead"></span>
                        <span class="mockup-simple-rule"></span>
                        <span class="mockup-lines"><i></i><i></i><i></i></span>
                        <span class="mockup-simple-section"></span>
                        <span class="mockup-lines"><i></i><i></i></span>
                    </span>
                    <span class="template-description">A calm, spacious layout that keeps the focus on your story and experience.</span>
                </label>

                <label class="template-card">
                    <input type="radio" name="template" value="modern" @checked(old('template', $selectedTemplate) === 'modern')>
                    <span class="template-card-heading"><span class="template-radio" aria-hidden="true"></span><span><strong>Modern</strong><small>Structured and polished</small></span></span>
                    <span class="template-mockup mockup-modern" aria-hidden="true">
                        <span class="mockup-modern-side"><i></i><b></b><b></b><b></b></span>
                        <span class="mockup-modern-main"><i></i><b></b><b></b><span><i></i><i></i></span><span><i></i><i></i></span></span>
                    </span>
                    <span class="template-description">A contemporary sidebar and card grid makes skills and projects easy to scan.</span>
                </label>

                <label class="template-card">
                    <input type="radio" name="template" value="creative" @checked(old('template', $selectedTemplate) === 'creative')>
                    <span class="template-card-heading"><span class="template-radio" aria-hidden="true"></span><span><strong>Creative</strong><small>Expressive and bold</small></span></span>
                    <span class="template-mockup mockup-creative" aria-hidden="true">
                        <span class="mockup-creative-shape"></span>
                        <span class="mockup-creative-title"></span>
                        <span class="mockup-creative-line"></span>
                        <span class="mockup-creative-cards"><i></i><i></i><i></i></span>
                        <span class="mockup-lines"><i></i><i></i></span>
                    </span>
                    <span class="template-description">A distinctive composition with oversized type, playful accents, and vibrant sections.</span>
                </label>
            </div>

            @error('template') <p class="field-error template-error">{{ $message }}</p> @enderror

            <div class="template-actions">
                <a href="{{ route('portfolios.edit', $portfolio->id, false) }}" class="button button-secondary">Back to information</a>
                <button type="submit" class="button">Generate Portfolio <span aria-hidden="true">→</span></button>
            </div>
        </form>
    </main>
</body>
</html>
