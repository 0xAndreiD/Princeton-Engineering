<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\SessionHistory;

class TwoFactor
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
        $user = auth()->user();

        if(auth()->check() && Session::get('blocked') == 1){
            return redirect()->route('verify.blocked');
        }
        if(auth()->check() && Session::get('mySessionId')){
            $mySession = SessionHistory::where('id', Session::get('mySessionId'))->first();
            if($mySession){
                $mySession->last_accessed = gmdate("Y-m-d\TH:i:s", time());
                $mySession->save();
            }
        }
        if(auth()->check() && $user->two_factor_code)
        {
            if($user->two_factor_expires_at && $user->two_factor_expires_at->lt(now()))
            {
                $user->resetTwoFactorCode();
                auth()->logout();

                return redirect()->route('login')
                    ->withErrors(['username' => 'The two factor code has expired. Please login again.']);
            }

            if(!$request->is('verify*'))
            {
                return redirect()->route('verify.index');
            }
        }

        return $next($request);
    }
}
