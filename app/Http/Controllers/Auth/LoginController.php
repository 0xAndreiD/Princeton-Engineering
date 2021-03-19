<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\LandingPage;
use App\LoginGuard;
use App\Notifications\TwoFactorCode;
use Mail;

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
                $data = ['ip' => $ip, 'agent' => $request->server('HTTP_USER_AGENT'), 'code' => $user->two_factor_code];
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
            }
        }
    }
}
