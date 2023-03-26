<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        //$role recebe o valor passado para o middleware (pode ser qualquer nome)
        if($role === 'admin'){
            dd('checkToken ' . $role);
        }else if($role === 'editor'){
            dd('checkToken ' . $role);
        }

        //Validando um token
        if($request->input('token') !== 'abc'){
            return redirect('welcome');
        };
        
        return $next($request);
    }
}
