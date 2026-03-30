<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpKernel\Exception\HttpException;

// Test: when user is already verified
it('redirects if email is already verified', function (): void {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $request = mock(EmailVerificationRequest::class);
    $request->shouldReceive('user')->andReturn($user);

    $controller = new VerifyEmailController();
    $response = $controller($request);

    expect($response->getTargetUrl())->toContain('dashboard?verified=1');
});

// Test: user gets verified and event is fired
it('verifies email and fires event', function (): void {
    Event::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $request = mock(EmailVerificationRequest::class);
    $request->shouldReceive('user')->andReturn($user);

    $controller = new VerifyEmailController();
    $response = $controller($request);

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    Event::assertDispatched(Verified::class);
    expect($response->getTargetUrl())->toContain('dashboard?verified=1');
});

// Test: Ensure the Verified event is dispatched even if markEmailAsVerified fails
it('dispatches the Verified event regardless of markEmailAsVerified return', function (): void {
    Event::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $mockUser = Mockery::mock($user)->makePartial();
    $mockUser->shouldReceive('markEmailAsVerified')->andReturn(false);
    $mockUser->shouldReceive('hasVerifiedEmail')->andReturn(false);

    $request = Mockery::mock(EmailVerificationRequest::class);
    $request->shouldReceive('user')->andReturn($mockUser);

    $controller = new VerifyEmailController();
    $response = $controller($request);

    Event::assertDispatched(Verified::class, fn ($event) => $event->user->is($mockUser));
    expect($response->getTargetUrl())->toContain('dashboard?verified=1');
});

// ✅ Test: user is null → aborts with 403
it('aborts with 403 if user is null', function (): void {
    $request = mock(EmailVerificationRequest::class);
    $request->shouldReceive('user')->andReturn(null);

    $controller = new VerifyEmailController();

    // Laravel's abort throws HttpResponseException
    $this->expectException(HttpException::class);
    $this->expectExceptionMessage('Forbidden');

    $controller($request);
});
