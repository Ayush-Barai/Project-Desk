<?php

declare(strict_types=1);

/**
 * LoginFormTest
 *
 * Tests for the login form component.
 * Verifies form rendering, input validation, and user interaction with login elements.
 */

use App\Livewire\Forms\LoginForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
// Import Laravel Auth facade to check authentication state
use Illuminate\Support\Facades\RateLimiter;
// Import exception class thrown when login fails
use Illuminate\Validation\ValidationException;
// Import Livewire component base class
use Livewire\Component;

/**
 * ✅ Dummy Livewire component to hold the LoginForm
 *
 * This is necessary because LoginForm extends Livewire\Form,
 * which requires a Component and a property name in its constructor.
 */
final class TestLoginComponent extends Component
{
    // The form property that will hold our LoginForm instance
    public LoginForm $form;

    // Called automatically when component is mounted
    public function mount(): void
    {
        // Inject LoginForm instance with the required arguments:
        // - 'component' is this Livewire component
        // - 'propertyName' is the property holding the form
        $this->form = resolve(LoginForm::class, [
            'component' => $this,
            'propertyName' => 'form',
        ]);
    }
}

/**
 * ✅ Test: Successful login with correct credentials
 */
it('logs in with correct credentials', function (): void {

    // Create a test user with known password
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    // Instantiate dummy Livewire component
    $component = new TestLoginComponent();
    $component->mount(); // mount to initialize the form

    // Fill the form with correct credentials
    $component->form->email = 'test@example.com';
    $component->form->password = 'password';

    // Call the authenticate() method on the form
    $component->form->authenticate();

    // Assert that the user is now logged in
    expect(Auth::check())->toBeTrue();
});

/**
 * ❌ Test: Fails with wrong credentials
 */
it('fails with wrong credentials', function (): void {

    // Create a user with known password
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    // Instantiate dummy Livewire component and mount
    $component = new TestLoginComponent();
    $component->mount();

    // Fill the form with wrong password
    $component->form->email = 'test@example.com';
    $component->form->password = 'wrong-password';

    // Expect a ValidationException to be thrown
    expect(fn () => $component->form->authenticate())
        ->toThrow(ValidationException::class);

    // Ensure the user is NOT logged in
    expect(Auth::check())->toBeFalse();
});

/**
 * 🚫 Test: Throttle / rate limit
 */
it('throws validation exception when rate limited', function (): void {

    // Instantiate dummy component and mount
    $component = new TestLoginComponent();
    $component->mount();

    // Set the form email
    $component->form->email = 'test@example.com';

    // Generate throttle key (lowercase email + IP)
    $key = mb_strtolower($component->form->email).'|'.request()->ip();

    // Hit the rate limiter multiple times (simulate too many attempts)
    for ($i = 0; $i < 6; $i++) {
        RateLimiter::hit($key);
    }

    // Expect a ValidationException when calling authenticate
    expect(fn () => $component->form->authenticate())
        ->toThrow(ValidationException::class);
});

/**
 * 🔑 Test: Check throttle key generation
 */
it('generates throttle key correctly', function (): void {

    // Instantiate dummy component and mount
    $component = new TestLoginComponent();
    $component->mount();

    // Set email in mixed case
    $component->form->email = 'TEST@Example.COM';

    // Use Reflection to access private throttleKey method
    $reflection = new ReflectionClass($component->form);
    $method = $reflection->getMethod('throttleKey');

    // Invoke the private method
    $key = $method->invoke($component->form);

    // Assert the key is normalized (lowercase email included)
    expect($key)->toContain('test@example.com');
});
