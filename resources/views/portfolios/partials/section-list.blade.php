@foreach ($sections as $heading => $content)
    <article class="portfolio-section-card {{ $sectionClass ?? '' }}">
        <p class="portfolio-section-kicker">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
        <h2>{{ $heading }}</h2>
        <p class="portfolio-section-content">{{ $content }}</p>
    </article>
@endforeach
