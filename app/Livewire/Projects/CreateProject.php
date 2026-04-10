<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

// use App\Http\Requests\ProjectRequest;
// use App\Models\Project;
// use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector ;
// use Illuminate\Support\Str;
use App\Livewire\Forms\ProjectForm;
use Illuminate\View\View;
use Livewire\Component;
// use Livewire\Form;

final class CreateProject extends Component 
{
    public ProjectForm $form ;
    
    public function create(): Redirector|RedirectResponse
    {
        return $this->form->create();
    }
    public function render(): View
    {
        return view('livewire.projects.create');
    }
}
