<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_registration_pages_are_public_and_render_forms(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSee('<form method="POST" action="/login"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('type="submit"', false)
            ->assertSee('Forgot password?')
            ->assertDontSee('Google');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSee('<form method="POST" action="/register"', false)
            ->assertSee('Confirm password')
            ->assertDontSee('Google');

        $this->get('/auth/google/redirect')->assertNotFound();
        $this->get('/auth/google/callback')->assertNotFound();

        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Send reset link')
            ->assertSee('class="auth-shell"', false);

        $this->get(route('password.reset', [
            'token' => 'test-token',
            'email' => 'jordan@example.test',
        ]))
            ->assertOk()
            ->assertSee('Choose a new password')
            ->assertSee('name="token"', false);

        $this->get('/')
            ->assertOk()
            ->assertSee('Log in to generate a portfolio')
            ->assertSee('href="/portfolios/create"', false)
            ->assertSee('>LOG IN</a>', false)
            ->assertSee('>SIGN UP</a>', false)
            ->assertDontSee('Get started')
            ->assertDontSee('Manage portfolios')
            ->assertDontSee('Manage saved portfolios');

        $this->get(route('portfolios.index'))
            ->assertRedirect('/login');
    }

    public function test_auth_redirects_keep_the_forwarded_https_host(): void
    {
        $user = User::factory()->create([
            'email' => 'jordan@example.test',
            'password' => Hash::make('correct-password'),
        ]);

        $headers = [
            'X-Forwarded-Host' => 'portmode.onrender.com',
            'X-Forwarded-Proto' => 'https',
        ];

        $this->withHeaders($headers)
            ->get('http://127.0.0.1:8000/portfolios/create')
            ->assertRedirect('/login');

        $this->post('http://127.0.0.1:8000/login', [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertRedirect('https://portmode.onrender.com/portfolios/create');

        $this->assertAuthenticatedAs($user);
    }

    public function test_homepage_and_auth_forms_use_relative_internal_urls(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('href="/login"', false)
            ->assertSee('href="/register"', false)
            ->assertSee('href="/portfolios/create"', false);

        $this->get('/login')
            ->assertOk()
            ->assertSee('<form method="POST" action="/login"', false)
            ->assertSee('href="/register"', false);

        $this->get('/register')
            ->assertOk()
            ->assertSee('<form method="POST" action="/register"', false)
            ->assertSee('href="/login"', false);
    }

    public function test_authenticated_homepage_hides_management_and_greeting_links(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertOk()
            ->assertSee('Sign out')
            ->assertDontSee('Manage portfolios')
            ->assertDontSee('Get started')
            ->assertDontSee('Hi,');
    }

    public function test_portfolio_generation_requires_login_and_returns_to_the_creation_form_after_login(): void
    {
        $this->get(route('portfolios.create'))
            ->assertRedirect('/login');

        $user = User::factory()->create([
            'email' => 'jordan@example.test',
            'password' => Hash::make('correct-password'),
        ]);

        $this->post(route('login.submit'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('portfolios.create'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('portfolios.create'))
            ->assertOk()
            ->assertSee('action="/portfolios"', false);

        auth()->logout();

        $this->post(route('portfolios.store'), [
            'full_name' => 'Guest Visitor',
            'email' => 'guest@example.test',
        ])->assertRedirect('/login');

        $this->assertDatabaseCount('portfolios', 0);
    }

    public function test_user_can_register_with_a_unique_email_and_is_authenticated(): void
    {
        $this->post(route('register.submit'), [
            'name' => 'Jordan Rivera',
            'email' => 'jordan@example.test',
            'password' => 'correct-horse-battery',
            'password_confirmation' => 'correct-horse-battery',
        ])
            ->assertRedirect('/')
            ->assertSessionHas('status', 'Your account has been created.');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Jordan Rivera',
            'email' => 'jordan@example.test',
        ]);
        $this->assertTrue(Hash::check('correct-horse-battery', User::query()->firstOrFail()->password));
    }

    public function test_registration_rejects_duplicate_emails_short_passwords_and_mismatched_confirmation(): void
    {
        User::factory()->create(['email' => 'jordan@example.test']);

        $this->from(route('register'))
            ->post(route('register.submit'), [
                'name' => 'Jordan Rivera',
                'email' => 'jordan@example.test',
                'password' => 'short',
                'password_confirmation' => 'different',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['email', 'password']);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_user_can_log_in_and_invalid_credentials_are_rejected(): void
    {
        $user = User::factory()->create([
            'email' => 'jordan@example.test',
            'password' => Hash::make('correct-password'),
        ]);

        $this->from(route('login'))
            ->post(route('login.submit'), [
                'email' => $user->email,
                'password' => 'incorrect-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->post(route('login.submit'), [
            'email' => $user->email,
            'password' => 'correct-password',
            'remember' => '1',
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function test_logout_is_post_only_invalidates_authentication_and_keeps_home_public(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['_token' => 'token-before-logout'])
            ->post(route('logout'))
            ->assertRedirect('/')
            ->assertSessionHas('status', 'You have been logged out.');

        $this->assertGuest();
        $this->assertNotSame('token-before-logout', session()->token());
        $this->get('/')->assertOk();
        $this->get('/logout')->assertStatus(405);
    }

    public function test_user_can_request_and_complete_a_password_reset(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'jordan@example.test']);

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('status', 'If an account exists for that email, a reset link has been sent.');

        $token = null;
        Notification::assertSentTo(
            $user,
            ResetPassword::class,
            function (ResetPassword $notification) use (&$token): bool {
                $token = $notification->token;

                return true;
            }
        );
        $this->assertIsString($token);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ])
            ->assertRedirect('/login')
            ->assertSessionHas('status', 'Your password has been reset. You can now sign in.');

        $this->assertTrue(Hash::check('new-secure-password', $user->fresh()->password));
    }
}
