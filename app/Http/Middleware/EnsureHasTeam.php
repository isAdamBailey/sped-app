<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class EnsureHasTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->allTeams()->count() === 0) {
            return redirect()->route('create-first-team');
        }

        $this->ensureOneOfTheTeamsIsCurrent($user);

        return $next($request);
    }

    protected function ensureOneOfTheTeamsIsCurrent(Authenticatable $user): void
    {
        if (! is_null($user->current_team_id)) {
            return;
        }

        $user->switchTeam($user->allTeams()->first());
    }
}
