<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Company;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if( Auth::user()->userrole == 2 )
            return view('admin.home');
        else if( Auth::user()->userrole == 1 )
            return view('clientadmin.home');
        else if( Auth::user()->userrole == 0 )
            return view('user.home');
    }

    /**
     * Get the same company member data
     *
     * @return JSON
     */
    public function getUserData(Request $request){
        if($request['userId'])
        {
            $user = User::where('companyid', Auth::user()->companyid)->where('id', $request['userId'])->first();
            if( $user )
                return response()->json($user);
            else
                return response()->json([]);
        }
        else
            return response()->json([]);
    }

}
