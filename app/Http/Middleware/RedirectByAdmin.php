<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectByAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type): Response
    {
       $user = auth()->user();
       
       if(!$user) {
        return redirect()->route('login');
       }

       if($type === 'user' && $user->is_admin){
            return redirect()->route('login');
       }

       if($type === 'admin' && !$user->is_admin){
            return redirect()->route('login');
       }

       return $next($request);
    }
}
