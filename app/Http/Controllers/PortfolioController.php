<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use stdClass;
use Throwable;

class PortfolioController extends Controller
{
    private const array TEMPLATES = ['simple', 'modern', 'creative'];

    private const array PORTFOLIO_FIELDS = [
        'full_name',
        'email',
        'contact_number',
        'address',
        'about_me',
        'education',
        'skills',
        'projects',
        'work_experience',
        'social_links',
    ];

    public function index(): View
    {
        $portfolios = DB::table('portfolios')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('portfolios.index', compact('portfolios'));
    }

    public function create(): View
    {
        return view('portfolios.create', ['portfolio' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $profilePicture = $this->storeProfilePicture($request);
        $now = now();
        $data = $this->portfolioData($validated);
        $data['profile_picture'] = $profilePicture;
        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        try {
            $portfolioId = DB::table('portfolios')->insertGetId($data, 'id');
        } catch (Throwable $exception) {
            if ($profilePicture !== null) {
                $this->deleteProfilePicture($profilePicture, null);
            }

            throw $exception;
        }

        $request->session()->put('portfolio_id', $portfolioId);

        return redirect()
            ->away(route('templates.index', $portfolioId, false))
            ->with('success', 'Your information was saved. Choose a portfolio design.');
    }

    public function templates(Request $request, int $portfolio): View|RedirectResponse
    {
        $record = $this->findPortfolio($portfolio);
        $selectedTemplate = $request->session()->get("selected_templates.$portfolio", 'simple');

        return view('templates.index', [
            'portfolio' => $record,
            'selectedTemplate' => $selectedTemplate,
        ]);
    }

    public function selectTemplate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'portfolio_id' => ['required', 'integer', 'min:1'],
            'template' => ['required', Rule::in(self::TEMPLATES)],
        ]);

        $portfolio = $this->findPortfolio((int) $validated['portfolio_id']);
        $request->session()->put(
            "selected_templates.{$portfolio->id}",
            $validated['template']
        );

        return redirect()
            ->away(route('portfolios.preview', [
                'portfolio' => $portfolio->id,
                'template' => $validated['template'],
            ], false))
            ->with('success', 'Your portfolio is ready to preview.');
    }

    public function show(Request $request, int $portfolio): RedirectResponse
    {
        $template = $request->session()->get("selected_templates.$portfolio", 'simple');

        return redirect()->away(route('portfolios.preview', [
            'portfolio' => $portfolio,
            'template' => $template,
        ], false));
    }

    public function preview(Request $request, int $portfolio): View
    {
        $record = $this->findPortfolio($portfolio);
        $template = $request->query(
            'template',
            $request->session()->get("selected_templates.$portfolio", 'simple')
        );

        abort_unless(in_array($template, self::TEMPLATES, true), 404);

        $sections = array_filter([
            'About Me' => $record->about_me,
            'Education' => $record->education,
            'Skills' => $record->skills,
            'Projects' => $record->projects,
            'Work Experience' => $record->work_experience,
            'Social Links' => $record->social_links,
        ], static fn (?string $value): bool => $value !== null && $value !== '');

        $profilePictureUrl = $record->profile_picture
            && Storage::disk('public')->exists($record->profile_picture)
                ? Storage::disk('public')->url($record->profile_picture)
                : null;

        return view('portfolios.preview', [
            'portfolio' => $record,
            'profilePictureUrl' => $profilePictureUrl,
            'sections' => $sections,
            'template' => $template,
        ]);
    }

    public function edit(int $portfolio): View
    {
        return view('portfolios.create', [
            'portfolio' => $this->findPortfolio($portfolio),
        ]);
    }

    public function update(Request $request, int $portfolio): RedirectResponse
    {
        $record = $this->findPortfolio($portfolio);
        $validated = $request->validate($this->rules());
        $newProfilePicture = $this->storeProfilePicture($request);
        $data = $this->portfolioData($validated);
        $data['profile_picture'] = $newProfilePicture ?? $record->profile_picture;
        $data['updated_at'] = now();

        try {
            DB::table('portfolios')->where('id', $portfolio)->update($data);
        } catch (Throwable $exception) {
            if ($newProfilePicture !== null) {
                $this->deleteProfilePicture($newProfilePicture, $portfolio);
            }

            throw $exception;
        }

        if ($newProfilePicture !== null && $record->profile_picture !== $newProfilePicture) {
            $this->deleteProfilePicture($record->profile_picture, $portfolio);
        }

        return redirect()
            ->away(route('templates.index', $portfolio, false))
            ->with('success', 'Your portfolio was updated. Choose a design to preview it.');
    }

    public function destroy(int $portfolio): RedirectResponse
    {
        $record = $this->findPortfolio($portfolio);
        DB::table('portfolios')->where('id', $portfolio)->delete();
        $this->deleteProfilePicture($record->profile_picture, $portfolio);

        return redirect()
            ->away(route('portfolios.index', [], false))
            ->with('success', 'The portfolio was deleted.');
    }

    private function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'email' => ['required', 'email', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'about_me' => ['nullable', 'string', 'max:10000'],
            'education' => ['nullable', 'string', 'max:10000'],
            'skills' => ['nullable', 'string', 'max:10000'],
            'projects' => ['nullable', 'string', 'max:10000'],
            'work_experience' => ['nullable', 'string', 'max:10000'],
            'social_links' => ['nullable', 'string', 'max:10000'],
        ];
    }

    private function portfolioData(array $validated): array
    {
        return array_intersect_key($validated, array_flip(self::PORTFOLIO_FIELDS));
    }

    private function storeProfilePicture(Request $request): ?string
    {
        if (! $request->hasFile('profile_picture')) {
            return null;
        }

        $path = $request->file('profile_picture')->storePublicly('profile-pictures', 'public');

        if (! is_string($path) || $path === '') {
            throw new RuntimeException('The profile picture could not be stored.');
        }

        return $path;
    }

    private function deleteProfilePicture(?string $path, ?int $portfolioId): void
    {
        if ($path === null || ! str_starts_with($path, 'profile-pictures/')) {
            return;
        }

        if (! Storage::disk('public')->delete($path)) {
            Log::warning('A portfolio profile picture could not be removed.', [
                'portfolio_id' => $portfolioId,
            ]);
        }
    }

    private function findPortfolio(int $portfolio): stdClass
    {
        $record = DB::table('portfolios')->where('id', $portfolio)->first();

        abort_unless($record instanceof stdClass, 404);

        return $record;
    }
}
