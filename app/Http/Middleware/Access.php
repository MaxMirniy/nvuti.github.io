<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;

class Access
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if ($this->auth->check())
        {
            if ($this->auth->user()->is_admin == 1)
            {
                return $next($request);
            }
			else
			{
				abort(404);
			}
        }
		else
		{
			abort(404);
		}

        return new RedirectResponse(url('/'));

    }
}
