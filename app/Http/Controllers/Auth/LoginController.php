<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\LandingPage;
use App\LoginGuard;
use App\Company;
use App\Notifications\TwoFactorCode;
use Mail;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function login(Request $request)
    {   
        $input = $request->all();
  
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
  
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($fieldType, '=', $input['username'])->where('password', '=', $input['password'])->first();

        if($user)
        {
            auth()->loginUsingId($user->id);
            return $this->sendLoginResponse($request);
            //return redirect()->route('home');
        }else{
            $validator->errors()->add('username', 'These credentials do not match our records.');
            return redirect()->route('login')->withErrors($validator)->withInput();
        }
    }

    public function logout(Request $request)
    {
        $this->performLogout($request);
        $target = LandingPage::where('active_lp', '=', '1')->first();
        if($target)
            return redirect($target->landingPage);
        else
            return redirect('https://www.princeton-engineering.com/framinganalysis.html');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    protected function authenticated(Request $request, $user)
    {
        $ip = $request->ip();
        $checkGuard = LoginGuard::where('userId', $user->id)->where('ipAddress', $ip)->where('identity', $request['identity'])->first();
        if(!$checkGuard) {
            $userGuard = LoginGuard::where('userId', $user->id)->get();
            if(count($userGuard) > 0){
                $user->generateTwoFactorCode();
                $data = ['ip' => $ip, 'code' => $user->two_factor_code];
                Mail::send('mail.verifycode', $data, function ($m) use ($user) {
                    $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($user->email)->subject('Please verify the iRoof access code.');
                });
                //$user->notify(new TwoFactorCode());
            } else {
                LoginGuard::create([
                    'userId' => $user->id,
                    'ipAddress' => $ip,
                    'identity' => $request['identity'],
                    'allowed' => 0
                ]);
                if($user->userrole == 1) // Client Admin company update
                {
                    $company = Company::where('id', $user->companyid)->first();
                    if($company){
                        $usergeo = unserialize( file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip) );
                        $company['company_ip'] = $ip;
                        $company['country_name'] = $usergeo['geoplugin_countryName'];
                        $company['region_name'] = $usergeo['geoplugin_regionName'];
                        $company['city'] = $usergeo['geoplugin_city'];
                        $company->save();
                    }
                }
            }
        } else{
            if($checkGuard->blocked == 1){
                Session::put('blocked', 1);
            }
        } 

        if(!$checkGuard || $checkGuard->allowed != 2){
            // IP address check with company IP
            $company = Company::where('id', $user->companyid)->first();
            if($company && $company->company_ip && $company->company_ip != $ip){
                $usergeo = unserialize( file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip) );
                if($usergeo['geoplugin_countryName'] != $company['country_name'] || $usergeo['geoplugin_regionName'] != $company['region_name'] || $usergeo['geoplugin_city'] != $company['city']){
                    $this->performLogout($request);
                    $supers = User::where('userrole', 2)->get();
                    $data = ['ip' => $ip, 'device' => $request['identity'], 'username' => $user->username, 'company' => $company->company_name];
                    foreach($supers as $super){
                        Mail::send('mail.geonotification', $data, function ($m) use ($super) {
                            $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($super->email)->subject('iRoof Notification.');
                        });
                    }
                    return redirect()->route('verify.geolocation');
                }
            }
        }
    }
}
