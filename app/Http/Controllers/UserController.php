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
        $companyList = Company::get();
        return view('admin.user.userlist',compact('companyList'));
    }

    /**
     * Get the All User Data
     *
     * @return JSON
     */
    public function getUserData(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'username',
            2 =>'email',
            3 =>'companyname',
            4 =>'usernumber',
            5 =>'membershipid'
        );
        $totalData = User::count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $users = User::offset($start)
                ->leftjoin('company_info', "company_info.id", "=", "users.companyid")
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array(
                        'users.id as id',
                        'users.username as username',
                        'users.email as email',
                        'company_info.company_name as companyname',
                        'users.usernumber as usernumber',
                        'users.membershipid as membershipid'
                    )
                );
        }
        else {
            $search = $request->input('search.value'); 
            $users =  User::where('users.id','LIKE',"%{$search}%")
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
                                'users.usernumber as usernumber',
                                'users.membershipid as membershipid'
                            )
                        );

            $totalFiltered = User::where('users.id','LIKE',"%{$search}%")
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

                if ($user->usernumber)
                    $nestedData['usernumber'] = "
                        <span class='badge badge-danger'> Admin </span>
                    ";
                else
                    $nestedData['usernumber'] = "
                        <span class='badge badge-info'> General </span>
                    ";
                
                if ($user->membershipid)
                    $nestedData['membershipid'] = "
                        <span class='badge badge-danger'> Admin </span>
                    ";
                else
                    $nestedData['membershipid'] = "
                        <span class='badge badge-info'> General </span>
                    ";

                $nestedData['actions'] = "
                <p class='text-center'>
                    <button type='button' class='btn btn-primary push' 
                        onclick='showEditUser(this,{$nestedData['id']})'
                        data-toggle='modal' data-target='#modal-block-normal'>
                        <i class='fa fa-pencil-alt'></i>
                    </button>
                    <button type='button' class='js-swal-confirm btn btn-danger push' onclick='delUser(this,{$nestedData['id']})'>
                        <i class='fa fa-trash'></i>
                    </button>
                    
                </p>";
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
            $res = new User;
            $res->username = $data['name'];
            $res->email = $data['email'];
            $res->password = Hash::make($data['password']);
            $res->userrole = 0;
            $res->companyid = $data['companyid'];
            $res->usernumber = $data['usernumber'];
            $res->membershipid = $data['membership'];
            $res->membershiprole = 0;
            $res->created_at = date('Y-m-d h:i:s',strtotime(now()));
            $res->save();
            echo true;
        } else {
            $res = User::where('id', $data['id'])->get()->first();
            $res->username = $data['name'];
            $res->email = $data['email'];
            if ($data['password']) $res->password = Hash::make($data['password']);
            $res->companyid = $data['companyid'];
            $res->usernumber = $data['usernumber'];
            $res->membershipid = $data['membership'];
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