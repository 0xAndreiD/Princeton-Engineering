<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Mail;
use Session;
use App\LoginGuard;
use App\Company;
use App\User;
use App\Notifications\TwoFactorCode;

class TwoFactorController extends Controller
{
    public function index() 
    {
        if(auth()->user()){
            if(auth()->user()->two_factor_expires_at && auth()->user()->two_factor_expires_at->lt(now()))
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

                $ip = $request->ip();
                // Send code check notifications to supers
                $company = Company::where('id', $user->companyid)->first();
                if($company){
                    $usergeo = unserialize( file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip) );
                    $supers = User::where('userrole', 2)->get();
                    $data = ['ip' => $ip, 'device' => $request['identity'], 'username' => $user->username, 'company' => $company->company_name, 'longitude' => $usergeo['geoplugin_longitude'], 'latitude' => $usergeo['geoplugin_latitude'], 
                    'location' => $usergeo['geoplugin_city'] . " " . $usergeo['geoplugin_regionName'] . " " . $usergeo['geoplugin_countryName']];
                    foreach($supers as $super){
                        Mail::send('mail.codenotification', $data, function ($m) use ($super) {
                            $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($super->email)->subject('iRoof Notification.');
                        });
                    }
                }

                $checkGuard = LoginGuard::where('userId', $user->id)->where('ipAddress', $request->ip())->where('identity', $request['identity'])->first();
                if(!$checkGuard){
                    $checkGuard = LoginGuard::create([
                        'userId' => $user->id,
                        'ipAddress' => $request->ip(),
                        'identity' => $request['identity'],
                        'allowed' => 1
                    ]);
                } 
                // else {
                    // $checkGuard->attempts = $checkGuard->attempts + 1;
                    // $checkGuard->save();
                    // if($checkGuard->attempts >= 4){
                        $usergeo = unserialize( file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip) );
                        $data = ['username' => $user->username, 'location' => $usergeo['geoplugin_city'] . " " . $usergeo['geoplugin_regionName'] . " " . $usergeo['geoplugin_countryName'], 
                        'link' => 'https://www.princeton.engineering/iRoof/storeLocation/' . base64_encode($checkGuard->id)];
                        Mail::send('mail.storelocation', $data, function ($m) use ($user) {
                            $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($user->email)->subject('iRoof Notification.');
                        });
                    // }
                // }
    
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

    public function storeLocation($id){
        if($id){
            $guard = LoginGuard::where('id', base64_decode($id))->first();
            if($guard){
                // $checks = LoginGuard::where('userId', $guard->userId)->get();
                // foreach($checks as $check){
                //     if($guard->identity == $check->identity)
                //         $check->allowed = 0;
                //     else
                //         $check->allowed = 1;
                //     $check->save();
                // }
                $guard->allowed = 0;
                $guard->save();
                return redirect('https://www.princeton.engineering/iRoof/home?notify=' . base64_encode('guardset'));
            } else
                return redirect('https://www.princeton.engineering/iRoof/home');
        } else
            return redirect('https://www.princeton.engineering/iRoof/home');
    }
}