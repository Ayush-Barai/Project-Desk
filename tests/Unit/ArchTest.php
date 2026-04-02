<?php

declare(strict_types=1);

/**
 * ArchTest
 *
 * Architecture and code quality tests for the entire application.
 * Validates coding standards, inheritance patterns, and structural integrity.
 * Uses Pest architecture testing to ensure code follows best practices.
 */
arch()->preset()->php();
arch()->preset()->strict();
arch()->preset()->laravel();
arch()->preset()->security()->ignoring([
    'assert',
]);

arch('controllers')
    ->expect('App\Http\Controllers')
    ->not->toBeUsed();

//
