<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage portfolios | Portivagen</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="app-page">
    <header class="site-header">
        <a href="{{ route('home', [], false) }}" class="brand">
            <span class="brand-copy"><span class="brand-name">PORTIVAGEN</span><span class="brand-subtitle">PORTFOLIO BUILDER</span></span>
        </a>
        <nav class="site-nav" aria-label="Main navigation">
            <a href="{{ route('home', [], false) }}">Home</a>
            <a href="{{ route('portfolios.create', [], false) }}" class="button button-small">New portfolio</a>
        </nav>
    </header>

    <main class="content-width manage-page">
        <div class="manage-heading">
            <div class="page-heading page-heading-left">
                <p class="eyebrow">YOUR SAVED WORK</p>
                <h1>Manage <span>portfolios</span></h1>
                <p>Preview, update, or remove a portfolio you have saved.</p>
            </div>
            <a href="{{ route('portfolios.create', [], false) }}" class="button">Create a portfolio <span aria-hidden="true">+</span></a>
        </div>

        @include('partials.flash')

        @if ($portfolios->isEmpty())
            <section class="panel empty-state">
                <span class="empty-state-icon" aria-hidden="true">✦</span>
                <h2>Your portfolio collection starts here</h2>
                <p>Save your information once, then choose one of three designs.</p>
                <a href="{{ route('portfolios.create', [], false) }}" class="button">Generate a Portfolio <span aria-hidden="true">→</span></a>
            </section>
        @else
            <div class="portfolio-list">
                @foreach ($portfolios as $portfolio)
                    <article class="panel portfolio-row">
                        <div class="portfolio-person">
                            <div class="avatar-placeholder" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($portfolio->full_name, 0, 1)) }}</div>
                            <div>
                                <h2>{{ $portfolio->full_name }}</h2>
                                <p>{{ $portfolio->email }}</p>
                                <span class="portfolio-date">Saved {{ \Illuminate\Support\Carbon::parse($portfolio->created_at)->format('M j, Y') }}</span>
                            </div>
                        </div>
                        <div class="portfolio-row-actions">
                            <a href="{{ route('portfolios.show', $portfolio->id, false) }}" class="button button-small button-secondary">View</a>
                            <a href="{{ route('portfolios.edit', $portfolio->id, false) }}" class="button button-small button-secondary">Edit</a>
                            <a href="{{ route('templates.index', $portfolio->id, false) }}" class="button button-small">Choose design</a>
                            <form action="{{ route('portfolios.destroy', $portfolio->id, false) }}" method="POST" onsubmit="return window.confirm('Delete this portfolio and its saved profile picture? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button button-small button-danger">Delete</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="pagination-wrap">{{ $portfolios->links() }}</div>
        @endif
    </main>
</body>
</html>
