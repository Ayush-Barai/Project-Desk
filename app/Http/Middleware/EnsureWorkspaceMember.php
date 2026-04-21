<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureWorkspaceMember
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $workspaceId = $request->workspace->id ?? session('workspace_id');
        abort_unless((bool) $user, 403, 'User not found');
        $isMember = $user
            ->workspaces()
            ->where('workspace_id', $workspaceId)
            ->exists();

        abort_unless((bool) $isMember, 403, 'Unauthorized workspace access');

        return $next($request);
    }
}
