<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class IsAdmin
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    private $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       // dd($this->auth->guest(),$this->auth->check(),$this->auth->validate());
        if ($this->auth->check()==false) {
            return redirect()->to('login');
        }

        if ( $this->auth->user()->type !== 'admin' )
        {
            $this->auth->logout();

            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                return redirect()->to('login');
            }
        }
       // dd($this->auth->user()->type);

        return $next($request);
    }
}
