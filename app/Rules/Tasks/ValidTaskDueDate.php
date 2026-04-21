<?php

declare(strict_types=1);

namespace App\Rules\Tasks;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

final class ValidTaskDueDate implements ValidationRule
{
    public function __construct(
        private readonly Project $project
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (! $this->project || ! $value) {
            return;
        }

        if ($value < $this->project->start_date || $value > $this->project->end_date) {
            $fail('Task due date must be within project date range.');
        }
    }
}
