<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin(): View
    {
        return view('auth', ['page' => 'login']);
    }

    /**
     * Show the registration page.
     */
    public function showRegister(): View
    {
        return view('auth', ['page' => 'register']);
    }

    /**
     * Register a new user.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', PasswordRule::min(8), 'confirmed'],
        ]);

        $user = User::create($validated);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->to(route('home', [], false))
            ->with('status', 'Your account has been created.');
    }

    /**
     * Log in an existing user.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'The email or password is incorrect.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home', [], false));
    }

    /**
     * Log out the current user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to(route('home', [], false))
            ->with('status', 'You have been logged out.');
    }

    /**
     * Show the forgot-password page.
     */
    public function showForgotPassword(): View
    {
        return view('auth', ['page' => 'forgot-password']);
    }

    /**
     * Send a password-reset link.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_THROTTLED) {
            throw ValidationException::withMessages([
                'email' => 'Please wait before requesting another reset link.',
            ]);
        }

        return back()->with(
            'status',
            'If an account exists for that email, a reset link has been sent.'
        );
    }

    /**
     * Show the password-reset page.
     */
    public function showResetPassword(
        Request $request,
        string $token
    ): View {
        return view('auth', [
            'page' => 'reset-password',
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Reset a user's password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'string',
                PasswordRule::min(8),
                'confirmed',
            ],
        ]);

        $status = Password::reset(
            $validated,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->to(route('login', [], false))
                ->with(
                    'status',
                    'Your password has been reset. You can now sign in.'
                );
        }

        throw ValidationException::withMessages([
            'email' => __($status),
        ]);
    }
}
