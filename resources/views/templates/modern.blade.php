<aside class="modern-sidebar">
    @if ($profilePictureUrl)
        <img class="profile-image" src="{{ $profilePictureUrl }}" alt="{{ $portfolio->full_name }} profile picture">
    @else
        <div class="profile-initial" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($portfolio->full_name, 0, 1)) }}</div>
    @endif
    <p class="eyebrow">HELLO, I'M</p>
    <h1>{{ $portfolio->full_name }}</h1>
    <p class="modern-role">Portfolio &amp; experience</p>

    <div class="modern-contact">
        <h2>Get in touch</h2>
        <a href="mailto:{{ $portfolio->email }}">{{ $portfolio->email }}</a>
        @if ($portfolio->contact_number)<p>{{ $portfolio->contact_number }}</p>@endif
        @if ($portfolio->address)<p>{{ $portfolio->address }}</p>@endif
    </div>
    @if ($portfolio->skills)
        <div class="modern-skills">
            <h2>Skills</h2>
            <p>{{ $portfolio->skills }}</p>
        </div>
    @endif
</aside>

<div class="modern-main">
    <header class="modern-welcome">
        <p class="eyebrow">A LITTLE ABOUT ME</p>
        <p>{{ $portfolio->about_me ?: 'Welcome to my portfolio. Explore my work, education, and experience below.' }}</p>
    </header>
    @include('portfolios.partials.section-list', ['sectionClass' => 'modern-section'])
    @if (count($sections) === 0)
        <p class="portfolio-empty-note">More portfolio details will appear here when they are added.</p>
    @endif
</div>
