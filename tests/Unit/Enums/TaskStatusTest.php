<?php

declare(strict_types=1);

/**
 * TaskStatusTest
 *
 * Tests for the TaskStatus enum.
 * Validates all valid task statuses and their definitions for task workflow management.
 */

use App\Enums\TaskStatus;

it('returns correct label for each status', function (TaskStatus $status, string $expectedLabel): void {
    expect($status->label())->toBe($expectedLabel);
})->with([
    [TaskStatus::Backlog, 'Backlog'],
    [TaskStatus::Todo, 'To Do'],
    [TaskStatus::InProgress, 'In Progress'],
    [TaskStatus::InReview, 'In Review'],
    [TaskStatus::Done, 'Done'],
    [TaskStatus::Cancelled, 'Cancelled'],
]);

it('returns correct color for each status', function (TaskStatus $status, string $expectedColor): void {
    expect($status->color())->toBe($expectedColor);
})->with([
    [TaskStatus::Backlog, 'gray'],
    [TaskStatus::Todo, 'blue'],
    [TaskStatus::InProgress, 'indigo'],
    [TaskStatus::InReview, 'purple'],
    [TaskStatus::Done, 'green'],
    [TaskStatus::Cancelled, 'red'],
]);
