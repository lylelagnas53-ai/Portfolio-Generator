
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose a Template | Portivagen</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="template-legacy">
    <main class="template-page">
        <header class="template-header">
            <h1>Choose Your Portfolio Template</h1>
            <p>Select a design for your personal portfolio before entering your information.</p>
        </header>

        <form action="{{ route('templates.select', [], false) }}" method="POST">
            @csrf

            <div class="template-grid">
                <article class="template-card">
                    <label>
                        <input type="radio" name="template" value="simple"
                            {{ old('template', session('selected_template')) === 'simple' ? 'checked' : '' }}>
                        Simple
                    </label>

                    <div class="template-preview simple-preview">
                        <div class="preview-bar"></div>
                        <div class="preview-line short"></div>
                        <div class="preview-line"></div>
                        <div class="preview-line"></div>
                        <div class="preview-bar preview-bar-secondary"></div>
                        <div class="preview-line"></div>
                    </div>

                    <p class="template-description">
                        A clean, simple, and professional layout that focuses on your information.
                    </p>
                </article>

                <article class="template-card">
                    <label>
                        <input type="radio" name="template" value="modern"
                            {{ old('template', session('selected_template')) === 'modern' ? 'checked' : '' }}>
                        Modern
                    </label>

                    <div class="template-preview modern-preview">
                        <div class="preview-bar"></div>
                        <div class="preview-line short"></div>
                        <div class="preview-boxes">
                            <span></span><span></span>
                        </div>
                        <div class="preview-bar preview-bar-secondary"></div>
                        <div class="preview-line"></div>
                    </div>

                    <p class="template-description">
                        A contemporary design with cards, organized sections, and visual elements.
                    </p>
                </article>

                <article class="template-card">
                    <label>
                        <input type="radio" name="template" value="creative"
                            {{ old('template', session('selected_template')) === 'creative' ? 'checked' : '' }}>
                        Creative
                    </label>

                    <div class="template-preview creative-preview">
                        <div class="preview-bar"></div>
                        <div class="preview-line short"></div>
                        <div class="preview-boxes">
                            <span></span><span></span>
                        </div>
                        <div class="preview-line preview-line-spaced"></div>
                        <div class="preview-line short"></div>
                    </div>

                    <p class="template-description">
                        A more expressive layout with distinctive colors and an eye-catching arrangement.
                    </p>
                </article>
            </div>

            @error('template')
                <p class="template-error">
                    {{ $message }}
                </p>
            @enderror

            <button type="submit" class="template-submit">
                Continue to Portfolio Information →
            </button>
        </form>

        <a href="{{ route('home', [], false) }}" class="template-back">← Back to Home</a>
    </main>
</body>
</html>
