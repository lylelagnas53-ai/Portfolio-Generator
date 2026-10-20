<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('portfolios');
        Schema::create('portfolios', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name');
            $table->string('profile_picture')->nullable();
            $table->string('email');
            $table->string('contact_number', 50)->nullable();
            $table->string('address')->nullable();
            $table->text('about_me')->nullable();
            $table->text('education')->nullable();
            $table->text('skills')->nullable();
            $table->text('projects')->nullable();
            $table->text('work_experience')->nullable();
            $table->text('social_links')->nullable();
            $table->timestamps();
        });
    }

    public function test_a_portfolio_can_be_saved_selected_and_previewed_in_all_templates(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/')
            ->assertOk()
            ->assertSee('<link rel="stylesheet" href="/css/app.css">', false)
            ->assertSee('href="/portfolios/create"', false)
            ->assertDontSee('localhost:8000', false);

        $this->get('/portfolios/create')
            ->assertOk()
            ->assertSee('<link rel="stylesheet" href="/css/app.css">', false)
            ->assertSee('action="/portfolios"', false)
            ->assertDontSee('localhost:8000', false);

        $this->post('/portfolios', [
            'full_name' => 'Jordan Rivera',
            'email' => 'jordan@example.test',
            'contact_number' => '555-0100',
            'address' => 'New York',
            'about_me' => 'I build thoughtful web experiences.',
            'education' => 'Design degree',
            'skills' => 'Laravel, PHP, design',
            'projects' => 'Portfolio Builder',
            'work_experience' => 'Web developer',
            'social_links' => 'https://example.test/jordan',
        ])->assertRedirect('/portfolios/1/templates');

        $this->assertDatabaseHas('portfolios', [
            'id' => 1,
            'full_name' => 'Jordan Rivera',
            'email' => 'jordan@example.test',
        ]);

        $this->get('/portfolios/1/templates')
            ->assertOk()
            ->assertSee('<link rel="stylesheet" href="/css/app.css">', false)
            ->assertSee('Choose a')
            ->assertSee('action="/templates/select"', false)
            ->assertDontSee('localhost:8000', false)
            ->assertSee('value="simple"', false)
            ->assertSee('value="modern"', false)
            ->assertSee('value="creative"', false)
            ->assertSee('Simple')
            ->assertSee('Modern')
            ->assertSee('Creative');

        foreach (['simple', 'modern', 'creative'] as $template) {
            $this->post('/templates/select', [
                'portfolio_id' => 1,
                'template' => $template,
            ])->assertRedirect("/portfolios/1/preview?template=$template");

            $this->get("/portfolios/1/preview?template=$template")
                ->assertOk()
                ->assertSee('<link rel="stylesheet" href="/css/app.css">', false)
                ->assertSee("portfolio-$template", false)
                ->assertSee('Jordan Rivera')
                ->assertSee('Portfolio Builder')
                ->assertSee('jordan@example.test')
                ->assertDontSee('localhost:8000', false);
        }
    }

    public function test_a_portfolio_can_be_updated_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('portfolios.store'), [
            'full_name' => 'Jordan Rivera',
            'email' => 'jordan@example.test',
        ]);

        $this->get(route('portfolios.index'))
            ->assertOk()
            ->assertSee('<link rel="stylesheet" href="/css/app.css">', false)
            ->assertSee('Jordan Rivera')
            ->assertSee('/portfolios/1/edit', false);

        $this->get(route('portfolios.edit', 1))
            ->assertOk()
            ->assertSee('<link rel="stylesheet" href="/css/app.css">', false)
            ->assertSee('value="Jordan Rivera"', false);

        $this->put(route('portfolios.update', 1), [
            'full_name' => 'Jordan R.',
            'email' => 'jordan.updated@example.test',
            'about_me' => 'Updated introduction',
        ])->assertRedirect('/portfolios/1/templates');

        $this->assertDatabaseHas('portfolios', [
            'id' => 1,
            'full_name' => 'Jordan R.',
            'email' => 'jordan.updated@example.test',
            'about_me' => 'Updated introduction',
        ]);

        $this->delete(route('portfolios.destroy', 1))
            ->assertRedirect('/portfolios');

        $this->assertDatabaseMissing('portfolios', ['id' => 1]);
    }

    public function test_portfolio_creation_validates_required_identity_fields(): void
    {
        $this->actingAs(User::factory()->create());

        $this->from(route('portfolios.create'))
            ->post(route('portfolios.store'), [
                'full_name' => '',
                'email' => 'not-an-email',
            ])
            ->assertRedirect(route('portfolios.create'))
            ->assertSessionHasErrors(['full_name', 'email']);

        $this->assertDatabaseCount('portfolios', 0);
    }

    public function test_profile_pictures_are_publicly_stored_replaced_and_removed_with_the_record(): void
    {
        $this->actingAs(User::factory()->create());
        Storage::fake('public');
        $imageContents = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/iYkAAAAASUVORK5CYII=',
            true
        );
        $this->assertIsString($imageContents);

        $this->post(route('portfolios.store'), [
            'full_name' => 'Jordan Rivera',
            'email' => 'jordan@example.test',
            'profile_picture' => UploadedFile::fake()->createWithContent('profile.png', $imageContents),
        ])->assertRedirect('/portfolios/1/templates');

        $oldPath = (string) DB::table('portfolios')
            ->where('id', 1)
            ->value('profile_picture');
        $this->assertStringStartsWith('profile-pictures/', $oldPath);
        Storage::disk('public')->assertExists($oldPath);

        foreach (['simple', 'modern', 'creative'] as $template) {
            $this->get(route('portfolios.preview', [
                'portfolio' => 1,
                'template' => $template,
            ]))
                ->assertOk()
                ->assertSee('src="/storage/'.$oldPath.'"', false)
                ->assertDontSee('http://localhost:8000/storage/', false);
        }

        $this->put(route('portfolios.update', 1), [
            'full_name' => 'Jordan Rivera',
            'email' => 'jordan@example.test',
            'profile_picture' => UploadedFile::fake()->createWithContent('replacement.png', $imageContents),
        ])->assertRedirect('/portfolios/1/templates');

        $newPath = (string) DB::table('portfolios')
            ->where('id', 1)
            ->value('profile_picture');
        $this->assertNotSame($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);

        $this->delete(route('portfolios.destroy', 1));
        Storage::disk('public')->assertMissing($newPath);
    }
}
