<?php

declare(strict_types=1);

use App\Enums\TaskPriority;

it('returns correct label for each priority', function (TaskPriority $priority, string $expectedLabel): void {
    expect($priority->label())->toBe($expectedLabel);
})->with([
    [TaskPriority::Low, 'Low'],
    [TaskPriority::Medium, 'Medium'],
    [TaskPriority::High, 'High'],
    [TaskPriority::Urgent, 'Urgent'],
]);

it('returns correct color for each priority', function (TaskPriority $priority, string $expectedColor): void {
    expect($priority->color())->toBe($expectedColor);
})->with([
    [TaskPriority::Low, 'gray'],
    [TaskPriority::Medium, 'blue'],
    [TaskPriority::High, 'orange'],
    [TaskPriority::Urgent, 'red'],
]);
