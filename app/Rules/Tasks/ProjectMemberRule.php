<?php

declare(strict_types=1);

namespace App\Rules\Tasks;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

final class ProjectMemberRule implements ValidationRule
{
    public function __construct(
        private readonly Project $project
    ) {}

    /**
     * Run the validation rule.
     * ProjectMemberRule checks if the assigned user is a member of the project.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value) {
            return;
        }

        if (! $this->project->members()
            ->where('user_id', $value)
            ->exists()) {
            $fail('Selected user is not a member of this project.');
        }
    }
}
