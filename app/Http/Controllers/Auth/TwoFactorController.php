<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Mail;
use Session;
use App\LoginGuard;
use App\Notifications\TwoFactorCode;

class TwoFactorController extends Controller
{
    public function index() 
    {
        if(auth()->user()){
            if(auth()->user()->two_factor_expires_at->lt(now()))
                return redirect()->route('login')->withErrors(['username' => 'The two factor code has expired. Please login again.']);
            else
                return view('auth.twofactor');
        }
        else
            return redirect()->route('login');
    }

    public function store(Request $request)
    {
        if(auth()->user()){
            $request->validate([
                'two_factor_code' => 'integer|required',
            ]);
    
            $user = auth()->user();
    
            if(auth()->check() && $request->input('two_factor_code') == $user->two_factor_code)
            {
                $user->resetTwoFactorCode();

                LoginGuard::create([
                    'userId' => $user->id,
                    'ipAddress' => $request->ip(),
                    'identity' => $request['identity'],
                    'allowed' => 1
                ]);
    
                return redirect()->route('home');
            }
    
            return redirect()->back()
                ->withErrors(['two_factor_code' => 
                    'The two factor code you have entered does not match.']);
        } else {
            return redirect()->route('login');
        }
    }

    public function resend(Request $request)
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();

        $data = ['ip' => $request->ip(), 'code' => $user->two_factor_code];
        Mail::send('mail.verifycode', $data, function ($m) use ($user) {
            $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($user->email)->subject('Please verify the iRoof access code.');
        });

        //$user->notify(new TwoFactorCode());

        return redirect()->back()->withMessage('The two factor code has been sent again.');
    }

    public function blocked(){
        return view('auth.unavailable')->with(['reason' => 'Your IP / Device is blocked. Please contact admin.']);
    }

    public function geolocation(){
        return view('auth.unavailablegeo')->with(['reason' => 'Your location is too far away from your company. Please contact admin.']);
    }
}