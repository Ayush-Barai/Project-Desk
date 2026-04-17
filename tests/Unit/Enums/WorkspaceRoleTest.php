<?php

declare(strict_types=1);
use App\Enums\WorkspaceRole;

it('has correct enum values and labels', function (): void {
    expect(WorkspaceRole::Owner->value)->toBe('owner');
    expect(WorkspaceRole::Admin->value)->toBe('admin');
    expect(WorkspaceRole::Member->value)->toBe('member');
    expect(WorkspaceRole::Owner->label())->toBe('Owner');
    expect(WorkspaceRole::Admin->label())->toBe('Admin');
    expect(WorkspaceRole::Member->label())->toBe('Member');
});

it('correctly identifies admin roles', function (): void {
    expect(WorkspaceRole::Owner->isAdmin())->toBeTrue();
    expect(WorkspaceRole::Admin->isAdmin())->toBeTrue();
    expect(WorkspaceRole::Member->isAdmin())->toBeFalse();
});
