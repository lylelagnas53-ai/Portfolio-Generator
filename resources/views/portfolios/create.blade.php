<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $portfolio ? 'Edit' : 'Create' }} portfolio | Portivagen</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="app-page">
    <header class="site-header">
        <a href="{{ route('home', [], false) }}" class="brand">
            <span class="brand-mark" aria-hidden="true">S2-G1</span>
            <span class="brand-copy"><span class="brand-name">PORTIVAGEN</span><span class="brand-subtitle">PORTFOLIO BUILDER</span></span>
        </a>
        <nav class="site-nav" aria-label="Main navigation">
            <a href="{{ route('portfolios.index', [], false) }}">Manage portfolios</a>
            <a href="{{ route('home', [], false) }}" class="text-link">Home</a>
        </nav>
    </header>

    <main class="content-width form-page">
        <div class="page-heading">
            <p class="eyebrow">YOUR STORY, YOUR WAY</p>
            <h1>{{ $portfolio ? 'Edit your' : 'Create your' }} <span>portfolio</span></h1>
            <p>Share the details you want people to know. You can update them any time.</p>
        </div>

        @include('partials.flash')

        <form
            class="panel portfolio-form"
            action="{{ $portfolio ? route('portfolios.update', $portfolio->id, false) : route('portfolios.store', [], false) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if ($portfolio)
                @method('PUT')
            @endif

            <section class="form-section">
                <h2>Personal information</h2>
                <div class="field-grid">
                    <div class="field field-wide">
                        <label for="full_name">Full name <span aria-hidden="true">*</span></label>
                        <input id="full_name" name="full_name" type="text" value="{{ old('full_name', $portfolio->full_name ?? '') }}" maxlength="255" autocomplete="name" required>
                        @error('full_name') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="field">
                        <label for="email">Email address <span aria-hidden="true">*</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email', $portfolio->email ?? '') }}" maxlength="255" autocomplete="email" required>
                        @error('email') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="field">
                        <label for="contact_number">Contact number</label>
                        <input id="contact_number" name="contact_number" type="tel" value="{{ old('contact_number', $portfolio->contact_number ?? '') }}" maxlength="50" autocomplete="tel">
                        @error('contact_number') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="field field-wide">
                        <label for="address">Address</label>
                        <input id="address" name="address" type="text" value="{{ old('address', $portfolio->address ?? '') }}" maxlength="255" autocomplete="street-address">
                        @error('address') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="field field-wide">
                        <label for="profile_picture">Profile picture</label>
                        <input id="profile_picture" name="profile_picture" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        <p class="field-help">Optional: JPG, JPEG, PNG, or WebP. Maximum file size 2 MB.</p>
                        @if ($portfolio?->profile_picture)
                            <p class="field-help">Uploading a new image replaces the current profile picture.</p>
                        @endif
                        @error('profile_picture') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="form-section">
                <h2>About you</h2>
                <div class="field">
                    <label for="about_me">About me</label>
                    <textarea id="about_me" name="about_me" rows="4" maxlength="10000" placeholder="A short introduction, what you care about, and what you are looking for.">{{ old('about_me', $portfolio->about_me ?? '') }}</textarea>
                    @error('about_me') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </section>

            <section class="form-section">
                <h2>Education and skills</h2>
                <div class="field-grid">
                    <div class="field">
                        <label for="education">Education</label>
                        <textarea id="education" name="education" rows="4" maxlength="10000" placeholder="Schools, degrees, courses, and dates.">{{ old('education', $portfolio->education ?? '') }}</textarea>
                        @error('education') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="field">
                        <label for="skills">Skills</label>
                        <textarea id="skills" name="skills" rows="4" maxlength="10000" placeholder="List your technical and professional skills.">{{ old('skills', $portfolio->skills ?? '') }}</textarea>
                        @error('skills') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="form-section">
                <h2>Projects and experience</h2>
                <div class="field-grid">
                    <div class="field">
                        <label for="projects">Projects</label>
                        <textarea id="projects" name="projects" rows="5" maxlength="10000" placeholder="Describe what you built and the impact it made.">{{ old('projects', $portfolio->projects ?? '') }}</textarea>
                        @error('projects') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="field">
                        <label for="work_experience">Work experience</label>
                        <textarea id="work_experience" name="work_experience" rows="5" maxlength="10000" placeholder="Roles, organizations, and responsibilities.">{{ old('work_experience', $portfolio->work_experience ?? '') }}</textarea>
                        @error('work_experience') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="field field-wide">
                        <label for="social_links">Social links and website</label>
                        <textarea id="social_links" name="social_links" rows="3" maxlength="10000" placeholder="Add your LinkedIn, GitHub, and personal website links.">{{ old('social_links', $portfolio->social_links ?? '') }}</textarea>
                        @error('social_links') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <div class="form-actions">
                <button type="submit" class="button">{{ $portfolio ? 'Save changes' : 'Save and choose a design' }} <span aria-hidden="true">→</span></button>
                <a href="{{ route('portfolios.index', [], false) }}" class="text-link">Manage portfolios</a>
            </div>
        </form>
    </main>
</body>
</html>
