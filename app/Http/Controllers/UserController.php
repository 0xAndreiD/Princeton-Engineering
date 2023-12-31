<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Company;
use App\UserSetting;

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
        $this->middleware('twofactor');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $companyList = Company::orderBy('company_name', 'asc')->get();
        if(Auth::user()->userrole == 2)
            return view('admin.user.userlist')->with('companyList', $companyList);
        else if(Auth::user()->userrole == 1 || Auth::user()->userrole == 3 || Auth::user()->userrole == 6)
            return view('clientadmin.user.userlist')->with('companyList', $companyList);
        else
            return redirect('home');
    }

    /**
     * Get the All User Data
     *
     * @return JSON
     */
    public function getUserData(Request $request){
        if(Auth::user()->userrole == 2)
        {
            $columns = array( 
                0 =>'id', 
                1 =>'username',
                2 =>'email',
                3 =>'companyname',
                4 =>'userrole',
                5 =>'usernumber',
                6 => 'distance',
                7 => 'ask_two_factor',
                8 => 'allow_permit'
            );
            $handler = new User;
        }
        else if(Auth::user()->userrole == 1 || Auth::user()->userrole == 3)
        {
            $columns = array( 
                0 =>'id', 
                1 =>'username',
                2 =>'email',
                3 =>'userrole',
                4 =>'usernumber',
                5 => 'allow_permit'
            );
            $handler = User::where('companyid', Auth::user()->companyid)->where('userrole', '<', 2);
        } else if(Auth::user()->userrole == 6) {
            $columns = array( 
                0 =>'id', 
                1 =>'username',
                2 =>'email',
                3 =>'userrole',
                4 =>'usernumber'
            );
            $handler = User::where('companyid', Auth::user()->companyid)->where('userrole', '>', 5);
        }
        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $handler = $handler->leftjoin('company_info', "company_info.id", "=", "users.companyid");

        if(Auth::user()->userrole == 2){
            if(!empty($request->input("columns.1.search.value")))
                $handler = $handler->where('users.username', 'LIKE', "%{$request->input("columns.1.search.value")}%");
            if(!empty($request->input("columns.2.search.value")))
                $handler = $handler->where('users.email', 'LIKE', "%{$request->input("columns.2.search.value")}%");
            if(!empty($request->input("columns.3.search.value")))
                $handler = $handler->where('company_info.company_name', 'LIKE', "%{$request->input("columns.3.search.value")}%");
            $handler = $handler->where('users.userrole', 'LIKE', "%{$request->input("columns.4.search.value")}%");
            if(!empty($request->input("columns.5.search.value")))
                $handler = $handler->where('users.usernumber', 'LIKE', "%{$request->input("columns.5.search.value")}%");
            if(isset($request["columns.7.search.value"]))
                $handler = $handler->where('users.ask_two_factor', 'LIKE', "%{$request->input("columns.7.search.value")}%");
            if(isset($request["columns.8.search.value"]))
                $handler = $handler->where('users.allow_permit', 'LIKE', "%{$request->input("columns.8.search.value")}%");
        } else {
            if(!empty($request->input("columns.1.search.value")))
                $handler = $handler->where('users.username', 'LIKE', "%{$request->input("columns.1.search.value")}%");
            if(!empty($request->input("columns.2.search.value")))
                $handler = $handler->where('users.email', 'LIKE', "%{$request->input("columns.2.search.value")}%");
            $handler = $handler->where('users.userrole', 'LIKE', "%{$request->input("columns.3.search.value")}%");
            if(!empty($request->input("columns.4.search.value")))
                $handler = $handler->where('users.usernumber', 'LIKE', "%{$request->input("columns.4.search.value")}%");
            if(Auth::user()->userrole != 6 && isset($request["columns.5.search.value"]))
                $handler = $handler->where('users.allow_permit', 'LIKE', "%{$request->input("columns.5.search.value")}%");
        }

        if(empty($request->input('search.value')))
        {            
            $totalFiltered = $handler->count();
            $users = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array(
                        'users.id as id',
                        'users.username as username',
                        'users.email as email',
                        'company_info.company_name as companyname',
                        'users.userrole as userrole',
                        'users.usernumber as usernumber',
                        'users.distance_limit as distance',
                        'users.ask_two_factor as ask_two_factor',
                        'users.allow_permit as allow_permit'
                    )
                );
        }
        else {
            $search = $request->input('search.value'); 
            $users =  $handler->where(function ($q) use ($search) {
                        $q->where('users.id','LIKE',"%{$search}%")
                        ->orWhere('users.username', 'LIKE',"%{$search}%")
                        ->orWhere('users.email', 'LIKE',"%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array(
                                'users.id as id',
                                'users.username as username',
                                'users.email as email',
                                'company_info.company_name as companyname',
                                'users.userrole as userrole',
                                'users.usernumber as usernumber',
                                'users.distance_limit as distance',
                                'users.ask_two_factor as ask_two_factor',
                                'users.allow_permit as allow_permit'
                            )
                        );

            $totalFiltered = $handler->where(function ($q) use ($search) {
                        $q->where('users.id','LIKE',"%{$search}%")
                        ->orWhere('users.username', 'LIKE',"%{$search}%")
                        ->orWhere('users.email', 'LIKE',"%{$search}%");
                        })
                        ->count();
        }

        $data = array();

        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $nestedData['id'] = $user->id;
                $nestedData['username'] = $user->username;
                $nestedData['email'] = $user->email;
                $nestedData['companyname'] = $user->companyname;
                $nestedData['usernumber'] = $user->usernumber;
                if(Auth::user()->userrole == 2)
                    $nestedData['distance'] = $user->distance;
                
                switch ($user->userrole){
                    case 2:
                        $nestedData['userrole'] = "<span class='badge badge-danger'> Super Admin </span>";
                        break;
                    case 1:
                        $nestedData['userrole'] = "<span class='badge badge-primary'> Client </span>";
                        break;
                    case 0:
                        $nestedData['userrole'] = "<span class='badge badge-info'> User </span>";
                        break;
                    case 3:
                        $nestedData['userrole'] = "<span class='badge badge-warning'> Junior Super </span>";
                        break;
                    case 4:
                        $nestedData['userrole'] = "<span class='badge badge-secondary'> Reviewer </span>";
                        break;
                    case 5:
                        $nestedData['userrole'] = "<span class='badge badge-dark'> Printer </span>";
                        break;
                    case 6:
                        $nestedData['userrole'] = "<span class='badge badge-primary'> Consultant Admin </span>";
                        break;
                    case 7:
                        $nestedData['userrole'] = "<span class='badge badge-info'> Consultant User </span>";
                        break;
                }
                if($user->ask_two_factor == 1)
                    $nestedData['ask_two_factor'] = "<span class='badge badge-primary'> Yes </span>";
                else 
                    $nestedData['ask_two_factor'] = "<span class='badge badge-danger'> No </span>";

                if(Auth::user()->userrole != 6) {
                    if($user->allow_permit == 0)
                        $nestedData['allow_permit'] = "<span class='badge badge-danger'> No </span>";
                    else if($user->allow_permit == 1) 
                        $nestedData['allow_permit'] = "<span class='badge badge-primary'> Regular User </span>";
                    else if($user->allow_permit == 2) 
                        $nestedData['allow_permit'] = "<span class='badge badge-warning'> Only Permit </span>";
                }

                $nestedData['actions'] = "
                <div class='text-center'>
                    <button type='button' class='btn btn-primary' 
                        onclick='showEditUser(this,{$nestedData['id']})'
                        data-toggle='modal' data-target='#modal-block-normal'>
                        <i class='fa fa-pencil-alt'></i>
                    </button>
                    <button type='button' class='js-swal-confirm btn btn-danger' onclick='delUser(this,{$nestedData['id']})'>
                        <i class='fa fa-trash'></i>
                    </button>
                    
                </div>";
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
        echo json_encode($json_data);
    }

    /**
     * User Edit View
     *
     * @return JSON
     */
    function updateUser(Request $request){
        $data = $request->input('data');
        if ($data['id'] == 0){
            if(Auth::user()->userrole == 1 || Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 6){
                $isExist = User::where('username', $data['name'])->get()->first();
                if ($isExist) {
                    echo "exist";
                    return;
                }
                $idExist = User::where('companyid', isset($data['companyid']) ? $data['companyid'] : Auth::user()->companyid)->where('usernumber', $data['usernumber'])->get()->first();
                if ($idExist) {
                    echo "idexist";
                    return;
                }

                $res = new User;
                $res->username = $data['name'];
                $res->email = $data['email'];
                $res->password = $data['password'];
                if(Auth::user()->userrole == 6)
                    $res->userrole = 7;
                else
                    $res->userrole = 0;
                $res->companyid = isset($data['companyid']) ? $data['companyid'] : Auth::user()->companyid;
                if(Auth::user()->userrole == 2 || $data['userrole'] < 2 || $data['userrole'] > 5)
                    $res->userrole = $data['userrole'];
                else
                    $res->userrole = 0;
                $res->usernumber = $data['usernumber'];
                $res->membershipid = 0;
                $res->membershiprole = 0;
                $res->created_at = date('Y-m-d h:i:s',strtotime(now()));
                if (isset($data['distance_limit'])) $res->distance_limit = $data['distance_limit']; 
                if (isset($data['ask_two_factor'])) $res->ask_two_factor = $data['ask_two_factor'];
                if (isset($data['allow_permit'])) $res->allow_permit = $data['allow_permit'];
                $res->save();
                echo true;
            } else
                echo false;
        } else {
            $isExist = User::where('username', $data['name'])->where('id', '!=', $data['id'])->get()->first();
            if ($isExist) {
                echo "exist";
                return;
            }
            $idExist = User::where('companyid', isset($data['companyid']) ? $data['companyid'] : Auth::user()->companyid)->where('usernumber', $data['usernumber'])->where('id', '!=', $data['id'])->get()->first();
            if ($idExist) {
                echo "idexist";
                return;
            }
            
            $res = User::where('id', $data['id'])->get()->first();
            if(Auth::user()->userrole == 2 || ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3 || Auth::user()->userrole == 6) && Auth::user()->companyid == $res['companyid']) || Auth::user()->id == $res['id']){
                if (isset($data['name'])) $res->username = $data['name'];
                if (isset($data['email'])) $res->email = $data['email'];
                if (isset($data['password'])){
                    if(Auth::user()->userrole == 2 || $res->userrole < 2)
                        $res->password = $data['password'];
                } 
                if (isset($data['companyid'])) $res->companyid = $data['companyid'];
                if (isset($data['userrole'])){
                    if(Auth::user()->userrole == 2 || (Auth::user()->userrole == 1 && $res->userrole < 2 && $data['userrole'] < 2) || (Auth::user()->userrole == 6 && $res->userrole >= 6 && $data['userrole'] >= 6))
                        $res->userrole = $data['userrole']; 
                } 
                if (isset($data['usernumber'])) $res->usernumber = $data['usernumber'];
                $res->updated_at = date('Y-m-d h:i:s',strtotime(now()));
                if (isset($data['distance_limit'])) $res->distance_limit = $data['distance_limit']; 
                if (isset($data['ask_two_factor'])) $res->ask_two_factor = $data['ask_two_factor'];
                if (isset($data['allow_permit'])) $res->allow_permit = $data['allow_permit'];
                $res->save();
                echo true;
            } else
                echo false;
        }
    }

    /**
     * Delete User
     *
     * @return JSON
     */
    function delete(Request $request){
        $id = $request->input('data');
        $user = User::where('id', $id)->first();
        if($user){
            if(Auth::user()->userrole == 2 || ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3 || Auth::user()->userrole == 6) && Auth::user()->companyid == $user['companyid']) || Auth::user()->id == $user['id']){
                $user->delete();
                echo true;
            } else
                echo false;
        } else
            echo false;
    }

    /**
     * Get User By ID
     *
     * @return JSON
     */
    function getUser(Request $request){
        $id = $request->input('data');
        $user = User::where('id', $id)->first();
        if($user){
            if(Auth::user()->userrole == 2 || ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3 || Auth::user()->userrole == 6) && Auth::user()->companyid == $user['companyid']) || Auth::user()->id == $user['id']){
                return response()->json($user);
            } else
                return 'nopermission';
        } else
            return 'noexist';
    }

    /**
     * Return Max User Num inside a company
     *
     * @return Number
     */
    function recommendUserNum(Request $request){
        if(isset($request['companyid'])){
            $user = User::where('companyid', $request['companyid'])->orderBy('usernumber', 'desc')->first();
            if($user)
                return $user['usernumber'] + 1;
            else
                return 1;
        }
        else{
            $user = User::where('companyid', Auth::user()->companyid)->orderBy('usernumber', 'desc')->first();
            if($user)
                return $user['usernumber'] + 1;
            else
                return 1;
        }
    }

    /**
     * Show the User Configuration page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function settings()
    {
        if(Auth::user()->userrole != 4){
            $setting = UserSetting::where('userId', Auth::user()->id)->first();
            return view('user.configuration.settings')->with('setting', $setting);
        } else
            return redirect('home');
    }

    /**
     * Return the User Configuration data.
     *
     * JSON
     */
    public function getUserSetting()
    {
        $setting = UserSetting::where('userId', Auth::user()->id)->first();
        if($setting)
            return response()->json(['success' => true, 'inputFontSize' => $setting->inputFontSize, 'inputCellHeight' => $setting->inputCellHeight, 'inputFontFamily' => $setting->inputFontFamily, 'includeFolderName' => $setting->includeFolderName]);
        else
            return response()->json(['success' => false]);
    }

    /**
     * Update the User Configuration data.
     *
     * JSON
     */
    public function updateUserSetting(Request $request)
    {
        if(!empty($request['inputFontSize'])){
            $setting = UserSetting::where('userId', Auth::user()->id)->first();
            if($setting){
                $setting->inputFontSize = $request['inputFontSize'];
                $setting->inputCellHeight = $request['inputCellHeight'];
                $setting->inputFontFamily = $request['inputFontFamily'];
                $setting->includeFolderName = $request['includeFolderName'];
                $setting->save();
            } else {
                UserSetting::create([
                    'userId' => Auth::user()->id,
                    'inputFontSize' => $request['inputFontSize'],
                    'inputCellHeight' => $request['inputCellHeight'],
                    'inputFontFamily' => $request['inputFontFamily'],
                    'includeFolderName' => $request['includeFolderName']
                ]);
            }
            return response()->json(['success' => true]);
        } else 
            return response()->json(['success' => false, 'message' => 'Wrong Input Data.']);
    }

    /**
     * Show My Account page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function myaccount()
    {
        return view('user.account.view');
    }

    /**
     * Update my account username, email or password.
     *
     * JSON
     */
    public function updateMyAccount(Request $request){
        $me = User::where('id', Auth::user()->id)->first();
        if($me){
            $me->username = $request['username'];
            $me->email = $request['email'];
            $me->password = $request['password'];
            if($me->userrole == 2 || $me->userrole == 3 || $me->userrole == 4)
                $me->auto_report_open = $request['autoOpen'];
            if($me->userrole == 1 || $me->userrole == 2 || $me->userrole == 3 || $me->userrole == 4 || $me->userrole == 6)
                $me->allow_cc = $request['allowCC'];
            $me->save();
            return response()->json(['success' => true]);
        } else
            return response()->json(['success' => false, 'message' => 'Cannot find the user.']);
    }
}
