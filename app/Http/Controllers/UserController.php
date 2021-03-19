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
        else if(Auth::user()->userrole == 1)
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
                5 =>'usernumber'
            );
            $handler = new User;
        }
        else if(Auth::user()->userrole == 1)
        {
            $columns = array( 
                0 =>'id', 
                1 =>'username',
                2 =>'email',
                3 =>'companyname',
                4 =>'userrole',
                5 =>'usernumber'
            );
            $handler = User::where('companyid', Auth::user()->companyid);
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
        } else {
            if(!empty($request->input("columns.1.search.value")))
                $handler = $handler->where('users.username', 'LIKE', "%{$request->input("columns.1.search.value")}%");
            if(!empty($request->input("columns.2.search.value")))
                $handler = $handler->where('users.email', 'LIKE', "%{$request->input("columns.2.search.value")}%");
            $handler = $handler->where('users.userrole', 'LIKE', "%{$request->input("columns.3.search.value")}%");
            if(!empty($request->input("columns.4.search.value")))
                $handler = $handler->where('users.usernumber', 'LIKE', "%{$request->input("columns.4.search.value")}%");
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
                        'users.usernumber as usernumber'
                    )
                );
        }
        else {
            $search = $request->input('search.value'); 
            $users =  $handler->where('users.id','LIKE',"%{$search}%")
                        ->orWhere('users.username', 'LIKE',"%{$search}%")
                        ->orWhere('users.email', 'LIKE',"%{$search}%")
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
                                'users.usernumber as usernumber'
                            )
                        );

            $totalFiltered = $handler->where('users.id','LIKE',"%{$search}%")
                        ->orWhere('users.username', 'LIKE',"%{$search}%")
                        ->orWhere('users.email', 'LIKE',"%{$search}%")
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
                
                switch ($user->userrole){
                    case 2:
                        $nestedData['userrole'] = "
                            <span class='badge badge-danger'> Supe Admin </span>
                        ";
                        break;
                    case 1:
                        $nestedData['userrole'] = "
                            <span class='badge badge-primary'> Client </span>
                        ";
                        break;
                    case 0:
                        $nestedData['userrole'] = "
                            <span class='badge badge-info'> User </span>
                        ";
                        break;
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
            $isExist = User::where('username', $data['name'])->get()->first();
            if ($isExist) {
                echo "exist";
                return;
            }
            $idExist = User::where('companyid', $data['companyid'])->where('usernumber', $data['usernumber'])->get()->first();
            if ($idExist) {
                echo "idexist";
                return;
            }

            $res = new User;
            $res->username = $data['name'];
            $res->email = $data['email'];
            $res->password = $data['password'];
            $res->userrole = 0;
            $res->companyid = isset($data['companyid']) ? $data['companyid'] : Auth::user()->companyid;
            $res->userrole = $data['userrole'];
            $res->usernumber = $data['usernumber'];
            $res->membershipid = 0;
            $res->membershiprole = 0;
            $res->created_at = date('Y-m-d h:i:s',strtotime(now()));
            $res->save();
            echo true;
        } else {
            $isExist = User::where('username', $data['name'])->where('id', '!=', $data['id'])->get()->first();
            if ($isExist) {
                echo "exist";
                return;
            }
            $idExist = User::where('companyid', $data['companyid'])->where('usernumber', $data['usernumber'])->where('id', '!=', $data['id'])->get()->first();
            if ($idExist) {
                echo "idexist";
                return;
            }
            
            $res = User::where('id', $data['id'])->get()->first();
            $res->username = $data['name'];
            $res->email = $data['email'];
            if ($data['password']) $res->password = $data['password'];
            $res->companyid = isset($data['companyid']) ? $data['companyid'] : Auth::user()->companyid;
            if ($data['userrole'] && $data['userrole'] != '') $res->userrole = $data['userrole'];
            $res->usernumber = $data['usernumber'];
            $res->updated_at = date('Y-m-d h:i:s',strtotime(now()));
            $res->save();
            echo true;
        }
    }

    /**
     * Delete User
     *
     * @return JSON
     */
    function delete(Request $request){
        $id = $request->input('data');
        $res = User::where('id', $id)->delete();
        return $res;
    }

    /**
     * Get User By ID
     *
     * @return JSON
     */
    function getUser(Request $request){
        $id = $request->input('data');
        $user = User::where('id', $id)->first();
        return response()->json($user);
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
        else
            return 1;
    }

    /**
     * Show the User Configuration page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function settings()
    {
        $setting = UserSetting::where('userId', Auth::user()->id)->first();
        return view('user.configuration.settings')->with('setting', $setting);
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
}
