<?php

namespace Despark\Cms\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            return $this->unauthenticatedResponse($request);
        }

        if (!Auth::guard($guard)->user()->is_admin) {
            return $this->unauthenticatedResponse($request);
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticatedResponse($request)
    {
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        } else {
            return redirect()->guest('admin/login');
        }
    }
}
