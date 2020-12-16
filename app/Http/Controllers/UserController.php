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
        $companyList = Company::all();
        if(Auth::user()->userrole == 2)
            return view('admin.user.userlist',compact('companyList'));
        else if(Auth::user()->userrole == 1)
            return view('clientadmin.user.userlist');
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

        if(empty($request->input('search.value')))
        {            
            $users = $handler->offset($start)
                ->leftjoin('company_info', "company_info.id", "=", "users.companyid")
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
                        ->leftjoin('company_info', "company_info.id", "=", "users.companyid")
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
                        ->leftjoin('company_info', "company_info.id", "=", "users.companyid")
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
                            <span class='badge badge-danger'> Admin </span>
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

            $res = new User;
            $res->username = $data['name'];
            $res->email = $data['email'];
            $res->password = Hash::make($data['password']);
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
            $res = User::where('id', $data['id'])->get()->first();
            $res->username = $data['name'];
            $res->email = $data['email'];
            if ($data['password']) $res->password = Hash::make($data['password']);
            $res->companyid = isset($data['companyid']) ? $data['companyid'] : Auth::user()->companyid;
            $res->userrole = $data['userrole'];
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
}
