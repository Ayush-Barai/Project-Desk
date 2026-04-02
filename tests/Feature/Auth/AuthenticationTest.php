<?php

declare(strict_types=1);

/**
 * AuthenticationTest
 *
 * Feature tests for user authentication functionality.
 * Tests login screen rendering, user authentication flow, error handling,
 * navigation menu display, and logout functionality using Volt components.
 */

use App\Models\User;
use Livewire\Volt\Volt;

// Test: Verify login screen renders successfully
// Description: Ensures the login page is accessible and loads without errors,
// validating the basic authentication UI is available to users
test('login screen can be rendered', function (): void {
    $response = $this->get('/login');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.login');
});

// Test: Verify user can successfully authenticate with valid credentials
// Description: Ensures the login flow works correctly with valid email and password,
// authenticating the user and redirecting to the dashboard
test('users can authenticate using the login screen', function (): void {
    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

// Test: Verify login fails with invalid password
// Description: Ensures authentication fails when an incorrect password is provided,
// preventing unauthorized access and maintaining security
test('users can not authenticate with invalid password', function (): void {
    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'wrong-password');

    $component->call('login');

    $component
        ->assertHasErrors()
        ->assertNoRedirect();

    $this->assertGuest();
});

test('navigation menu can be rendered', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response
        ->assertOk()
        ->assertSeeVolt('layout.navigation');
});

test('users can logout', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Volt::test('layout.navigation');

    $component->call('logout');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
});
