<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Project;

final class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $projectId = $this->input('project_id');

        $project = Project::find($projectId);

        return [
            'project_id' => ['required', 'exists:projects,id'],

            'title' => ['required', 'string', 'min:3', 'max:255'],

            'description' => ['nullable', 'string'],

            'status' => ['required', Rule::in([
                'Backlog', 'Todo', 'InProgress', 'InReview', 'Done', 'Cancelled'
            ])],

            'priority' => ['required', Rule::in([
                'Low', 'Medium', 'High', 'Urgent'
            ])],

            'due_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($project) {
                    if ($project && $project->start_date && $project->end_date) {
                        if ($value < $project->start_date || $value > $project->end_date) {
                            $fail('Due date must be within project duration');
                        }
                    }
                }
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($project) {
                    if ($value && $project) {
                        $isMember = $project->members()
                            ->where('user_id', $value)
                            ->exists();

                        if (!$isMember) {
                            $fail('Assignee must be a project member');
                        }
                    }
                }
            ],

            'estimated_hours' => [
                'nullable',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($project) {
                    if ($project && $value) {
                        $total = $project->tasks()->sum('estimated_hours');

                        if (($total + $value) > $project->budget_hours) {
                            $fail('Total estimated hours exceed project budget');
                        }
                    }
                }
            ],
        ];
    }
}