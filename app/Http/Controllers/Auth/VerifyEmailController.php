<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

final class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        /** @var MustVerifyEmail|null $user */
        $user = $request->user();

        if (! $user) {
            abort(403, 'Forbidden'); // safety if user is null
        }

        // Already verified → redirect
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        // Mark as verified
        $user->markEmailAsVerified();

        // Fire the verified event
        event(new Verified($user));

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
