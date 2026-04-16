<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

final class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return all projects with pagination
        // $projects = Project::paginate(10);

        $projects = Project::query()->where('workspace_id', session('workspace_id'))->get();

        return view('pages.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Create a new project
        return view('pages.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        // Store a newly created project
        $request = $request->validated() +
            ['workspace_id' => session('workspace_id'), 'slug' => Str::slug($request->name).'-'.uniqid(), 'color' => 'gray'];
        Project::create($request);

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Show a single project
        return view('pages.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // Show the form for editing a project
        return view('pages.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProjectRequest $request, Project $project)
    {
        // Update the specified project
        $project->update($request->validated());

        return redirect()->route('projects.show', compact('project'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function archive(Project $project)
    {
        // Remove the specified project
        $project->delete();

        return redirect()->route('projects.index');

    }
    /**
     * Remove the specified resource from storage.
     */
    public function restore(Project $project)
    {
        // Remove the specified project
        $project->restore();

        return redirect()->route('projects.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Remove the specified project
        $project->forceDelete();

        return redirect()->route('projects.index');
    }
}
