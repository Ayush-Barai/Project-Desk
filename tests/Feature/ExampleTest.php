<?php

declare(strict_types=1);

/**
 * ExampleTest
 *
 * Basic feature test demonstrating home page accessibility.
 * Tests that the application can serve requests and return successful HTTP responses.
 */
it('returns a successful response', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
});
