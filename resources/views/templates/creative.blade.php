<header class="creative-profile">
    <span class="creative-spark creative-spark-one" aria-hidden="true">✳</span>
    <span class="creative-spark creative-spark-two" aria-hidden="true">✦</span>
    <div class="creative-identity">
        <p class="eyebrow">THE PORTFOLIO OF</p>
        <h1>{{ $portfolio->full_name }}<span>.</span></h1>
        <p class="creative-intro">{{ $portfolio->about_me ?: 'Curious mind. Thoughtful work. Always building what comes next.' }}</p>
        <a class="creative-email" href="mailto:{{ $portfolio->email }}">{{ $portfolio->email }}</a>
    </div>
    @if ($profilePictureUrl)
        <img class="profile-image" src="{{ $profilePictureUrl }}" alt="{{ $portfolio->full_name }} profile picture">
    @else
        <div class="creative-portrait-fallback" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($portfolio->full_name, 0, 1)) }}</div>
    @endif
</header>

<div class="creative-contact">
    @if ($portfolio->contact_number)<span>{{ $portfolio->contact_number }}</span>@endif
    @if ($portfolio->address)<span>{{ $portfolio->address }}</span>@endif
    <span>Selected work &amp; experience</span>
</div>

<div class="creative-sections">
    @include('portfolios.partials.section-list', ['sectionClass' => 'creative-section'])
    @if (count($sections) === 0)
        <p class="portfolio-empty-note">More portfolio details will appear here when they are added.</p>
    @endif
</div>
