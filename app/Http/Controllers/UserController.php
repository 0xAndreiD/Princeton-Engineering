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
        // if( Auth::user()->userrole == 2 )
        return view('user.userlist');
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
            2=> 'email'
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
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
        $search = $request->input('search.value'); 

        $users =  User::where('id','LIKE',"%{$search}%")
                    ->orWhere('username', 'LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

        $totalFiltered = User::where('id','LIKE',"%{$search}%")
                    ->orWhere('username', 'LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->count();
        }

        $data = array();
        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $show =  route('userEdit', $user->id);
                // $edit =  route('user.edit',$user->id);

                $nestedData['id'] = $user->id;
                $nestedData['username'] = $user->username;
                $nestedData['email'] = $user->email;
                // $nestedData['body'] = substr(strip_tags($user->body),0,50)."...";
                // $nestedData['created_at'] = date('j M Y h:i a',strtotime($user->created_at));
                $nestedData['actions'] = "
                <p class='text-center'>
                    &emsp;<a href='{$show}' title='EDIT'><i class='fa fa-pencil-alt'></i></a>
                    &emsp;<a href='#' title='DEL'><i class='fa fa-times'></i></a>
                </p>";
                // $nestedData['actions'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                //                         &emsp;<a href='{$edit}' title='EDIT' ><span class='glyphicon glyphicon-edit'></span></a>";
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
    function edit(Request $request){
        $id = $request->input('id');
        return view('user.edit');
    }
}
