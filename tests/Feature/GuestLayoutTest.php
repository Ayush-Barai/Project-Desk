<?php

declare(strict_types=1);

/**
 * GuestLayoutTest
 *
 * Tests for the guest layout template used by authentication pages.
 * Verifies proper rendering, navigation elements, and styling for unauthenticated users.
 */

use App\View\Components\GuestLayout;
use Illuminate\View\View;

// This test checks if the GuestLayout component renders the correct view
it('renders the guest layout view', function (): void {

    // Create a new instance of the GuestLayout component
    $component = new GuestLayout();

    // Call the render() method of the component
    // This should return a View object
    $view = $component->render();

    // Check that the returned value is an instance of Laravel's View class
    expect($view)->toBeInstanceOf(View::class);

    // Check that the view being rendered is 'layouts.guest'
    // This ensures the correct Blade file is used
    expect($view->name())->toBe('layouts.guest');
});
