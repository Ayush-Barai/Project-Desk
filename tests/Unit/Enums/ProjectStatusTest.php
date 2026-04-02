<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;

it('returns correct label for each status', function (ProjectStatus $status, string $expectedLabel): void {
    expect($status->label())->toBe($expectedLabel);
})->with([
    [ProjectStatus::Planning, 'Planning'],
    [ProjectStatus::Active, 'Active'],
    [ProjectStatus::OnHold, 'On Hold'],
    [ProjectStatus::Completed, 'Completed'],
    [ProjectStatus::Archived, 'Archived'],
]);

it('returns correct color for each status', function (ProjectStatus $status, string $expectedColor): void {
    expect($status->color())->toBe($expectedColor);
})->with([
    [ProjectStatus::Planning, 'gray'],
    [ProjectStatus::Active, 'blue'],
    [ProjectStatus::OnHold, 'yellow'],
    [ProjectStatus::Completed, 'green'],
    [ProjectStatus::Archived, 'dark'],
]);
