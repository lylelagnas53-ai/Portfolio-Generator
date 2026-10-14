<header class="simple-profile">
    @if ($profilePictureUrl)
        <img class="profile-image" src="{{ $profilePictureUrl }}" alt="{{ $portfolio->full_name }} profile picture">
    @else
        <div class="profile-initial" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($portfolio->full_name, 0, 1)) }}</div>
    @endif
    <p class="eyebrow">PORTFOLIO</p>
    <h1>{{ $portfolio->full_name }}</h1>
    <p class="simple-intro">{{ $portfolio->about_me ?: 'A professional portfolio highlighting selected experience, skills, and work.' }}</p>
    <div class="contact-line">
        <a href="mailto:{{ $portfolio->email }}">{{ $portfolio->email }}</a>
        @if ($portfolio->contact_number)<span>{{ $portfolio->contact_number }}</span>@endif
        @if ($portfolio->address)<span>{{ $portfolio->address }}</span>@endif
    </div>
</header>

<div class="simple-sections">
    @include('portfolios.partials.section-list', ['sectionClass' => 'simple-section'])
</div>
@if (count($sections) === 0)
    <p class="portfolio-empty-note">More portfolio details will appear here when they are added.</p>
@endif
