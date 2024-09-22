<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user() -> payment == "paid" || Auth::user() -> role == 'admin'){
            return $next($request);
        }elseif(Auth::user()->payment == 'notpaid' && Auth::user() -> role == 'user'){
            session() -> flash("notpaid", "Not Eligible to Access Post Jobs Page");
            return redirect()->route('account.profile');
        }
    }
}
