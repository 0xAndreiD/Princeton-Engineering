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
use App\SessionHistory;
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
    protected $redirectTo = '/statistics';

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
        // $user = auth()->user();
        // if($user && $user->two_factor_code)
        //     $user->resetTwoFactorCode();
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

    /**
     * Calculate the distance between two longitude/latitude points.
     *
     * @return DOUBLE
     */
    protected function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
      
        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
          } else {
              return $miles;
            }
    }

    protected function authenticated(Request $request, $user)
    {
        // Logo link session set
        $company = Company::where('id', $user->companyid)->first();
        if($company && $company->company_logo != ''){
            Session::put('company_logo', asset('logos') . '/' .$company->company_logo);
        }

        $ip = $request->ip();
        $checkGuard = LoginGuard::where('userId', $user->id)->where('ipAddress', $ip)->where('identity', $request['identity'])->first();
        if($checkGuard && $checkGuard->blocked == 1){ // Check if blocked
            Session::put('blocked', 1);
        } else if($user->ask_two_factor){
            if(!$checkGuard) {
                $userGuard = LoginGuard::where('userId', $user->id)->get();
                if(count($userGuard) > 0){ // There are some passed histories
                    $user->generateTwoFactorCode();
                    $data = ['ip' => $ip, 'code' => $user->two_factor_code];
                    Mail::send('mail.verifycode', $data, function ($m) use ($user) {
                        $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($user->email)->subject('Please verify the iRoof access code.');
                    });
                    //$user->notify(new TwoFactorCode());
                } else { // Automatic pass the first login
                    LoginGuard::create([
                        'userId' => $user->id,
                        'ipAddress' => $ip,
                        'identity' => $request['identity'],
                        'allowed' => 0
                    ]);
                    if($user->userrole == 1 || $user->userrole == 6) // Client Admin company update
                    {
                        // $company = Company::where('id', $user->companyid)->first();
                        if($company){
                            $usergeo = unserialize( file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip) );
                            $company['company_ip'] = $ip;
                            $company['longitude'] = $usergeo['geoplugin_longitude'];
                            $company['latitude'] = $usergeo['geoplugin_latitude'];
                            $company->save();
                        }
                    }
                }
            } else if($checkGuard->allowed != 0){ // The info shows it's not allowed login
                $user->generateTwoFactorCode();
                $data = ['ip' => $ip, 'code' => $user->two_factor_code];
                Mail::send('mail.verifycode', $data, function ($m) use ($user) {
                    $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($user->email)->subject('Please verify the iRoof access code.');
                });
            }
        }

        // if(!$checkGuard || $checkGuard->allowed != 2){
            // IP address check with company IP
            // $company = Company::where('id', $user->companyid)->first();
            if($company && $company->company_ip && $company->company_ip != $ip){
                $usergeo = unserialize( file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip) );
                if($usergeo['geoplugin_latitude'] && $usergeo['geoplugin_longitude'] ){
                    $distance = $this->distance($usergeo['geoplugin_latitude'], $usergeo['geoplugin_longitude'], $company->latitude, $company->longitude, "M");
                    if($distance > $user->distance_limit){
                        //$this->performLogout($request);
                        $supers = User::where('userrole', 2)->get();
                        $data = ['ip' => $ip, 'device' => $request['identity'], 'username' => $user->username, 'company' => $company->company_name, 'longitude' => $usergeo['geoplugin_longitude'], 'latitude' => $usergeo['geoplugin_latitude'], 
                        'distance' => $distance, 'location' => $usergeo['geoplugin_city'] . " " . $usergeo['geoplugin_regionName'] . " " . $usergeo['geoplugin_countryName']];
                        foreach($supers as $super){
                            Mail::send('mail.geonotification', $data, function ($m) use ($super) {
                                $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($super->email)->subject('iRoof Notification.');
                            });
                        }
                        //return redirect()->route('verify.geolocation');
                    }
                }
            }
        //}

        $mySession = SessionHistory::create([
            'userId' => $user->id,
            'ipAddress' => $ip,
            'device' => $request['identity'],
            'created_at' => gmdate("Y-m-d\TH:i:s", time()),
            'last_accessed' => gmdate("Y-m-d\TH:i:s", time()),
        ]);
        Session::put('mySessionId', $mySession->id);
    }
}
