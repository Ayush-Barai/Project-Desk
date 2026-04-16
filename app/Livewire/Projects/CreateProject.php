<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

// use App\Http\Requests\ProjectRequest;
// use App\Models\Project;
// use Illuminate\Contracts\Validation\ValidationRule;
use App\Livewire\Forms\ProjectForm;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

// use Livewire\Form;

final class CreateProject extends Component
{
    public ProjectForm $form;

    public function create(): Redirector|RedirectResponse
    {
        return $this->form->create();
    }

    public function render(): View
    {
        return view('livewire.projects.create');
    }
}
