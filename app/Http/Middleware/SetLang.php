<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App;

class SetLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

/*
        if(empty($request->has('lan'))) {
            if (Auth::user()) {
              Auth::user()->language = $request->input('lan');
              Auth::user()->save(); // this will do database UPDATE only when language was changed
            }
            App::setLocale($request->input('lan'));
          } else if (Auth::user()) {
            App::setLocale(Auth::user()->language);
          }
*/

          return $next($request);
    }
}


