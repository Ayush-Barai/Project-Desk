<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;
use UnexpectedValueException;

final class ProjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Apply authorization to all resource methods
     */
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Return all projects with pagination
        // $projects = Project::paginate(10);

        $projects = Project::query()->where('workspace_id', session('workspace_id'))->paginate(10);

        return view('pages.projects.index', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Create a new project
        return view('pages.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $name = $validated['name'];

        throw_unless(is_string($name), UnexpectedValueException::class, 'Name must be a string');

        $data = $validated + [
            'workspace_id' => session('workspace_id'),
            'slug' => Str::slug($name),
            'color' => 'Blue',
        ];

        $project = Project::query()->create($data);

        $project->members()->attach(auth()->id(), [
            'role' => 'Project Manager',
        ]);

        return to_route('projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        // Show a single project
        return view('pages.projects.show', ['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        // Show the form for editing a project
        return view('pages.projects.edit', ['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProjectRequest $request, Project $project): RedirectResponse
    {
        // Update the specified project
        $project->update($request->validated());

        return to_route('projects.show', ['project' => $project]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Project $project): RedirectResponse
    {
        // Remove the specified project
        if ($request->isMethod('delete')) {
            // Remove the specified resource from storage.
            $project->forceDelete();
        } else {
            // Soft delete the project
            $project->delete();
        }

        return to_route('projects.index');
    }
}
