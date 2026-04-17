<?php

declare(strict_types=1);

/**
 * TaskStatusTest
 *
 * Tests for the TaskStatus enum.
 * Validates all valid task statuses and their definitions for task workflow management.
 */

use App\Enums\WorkspaceRole;

it('returns correct label for each status', function (WorkspaceRole $status, string $expectedLabel): void {
    expect($status->label())->toBe($expectedLabel);
})->with([
    [WorkspaceRole::Owner, 'Owner'],
    [WorkspaceRole::Admin, 'Admin'],
    [WorkspaceRole::Member, 'Member'],
]);

it('correctly identifies admin roles', function (WorkspaceRole $status, bool $isAdmin): void {
    expect($status->isAdmin())->toBe($isAdmin);
})->with([
    [WorkspaceRole::Owner, true],
    [WorkspaceRole::Admin, true],
    [WorkspaceRole::Member, false],
]);
