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

class CompanyController extends Controller
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
        if(Auth::user()->userrole == 2)
            return view('admin.company.companylist');
        else
            return redirect('home');
    }

    /**
     * Get the All User Data
     *
     * @return JSON
     */
    public function getCompanyData(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'name',
            2 =>'number',
            3 =>'telno',
            4 =>'address',
            5 =>'website'
        );
        $totalData = Company::count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $handler = new Company;
        if(!empty($request->input("columns.1.search.value")))
            $handler = $handler->where('company_info.company_name', 'LIKE', "%{$request->input("columns.1.search.value")}%");
        if(!empty($request->input("columns.2.search.value")))
            $handler = $handler->where('company_info.company_number', 'LIKE', "%{$request->input("columns.2.search.value")}%");
        if(!empty($request->input("columns.3.search.value")))
            $handler = $handler->where('company_info.company_telno', 'LIKE', "%{$request->input("columns.3.search.value")}%");
        if(!empty($request->input("columns.4.search.value")))
            $handler = $handler->where('company_info.company_address', 'LIKE', "%{$request->input("columns.4.search.value")}%");
        if(!empty($request->input("columns.5.search.value")))
            $handler = $handler->where('company_info.company_email', 'LIKE', "%{$request->input("columns.5.search.value")}%");
        if(!empty($request->input("columns.6.search.value")))
            $handler = $handler->where('company_info.company_website', 'LIKE', "%{$request->input("columns.6.search.value")}%");

        if(empty($request->input('search.value')))
        {            
            $totalFiltered = $handler->count();
            $companys = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array(
                        'company_info.id as id',
                        'company_info.company_name as name',
                        'company_info.company_number as number',
                        'company_info.company_telno as telno',
                        'company_info.company_address as address',
                        'company_info.company_email as email',
                        'company_info.company_website as website'
                    )
                );
        }
        else {
            $search = $request->input('search.value'); 
            $companys =  $handler->where('company_info.id','LIKE',"%{$search}%")
                        ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('company_info.company_number', 'LIKE',"%{$search}%")
                        ->orWhere('company_info.company_telno', 'LIKE',"%{$search}%")
                        ->orWhere('company_info.company_address', 'LIKE',"%{$search}%")
                        ->orWhere('company_info.company_email', 'LIKE',"%{$search}%")
                        ->orWhere('company_info.company_website', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array(
                                'company_info.id as id',
                                'company_info.company_name as name',
                                'company_info.company_number as number',
                                'company_info.company_telno as telno',
                                'company_info.company_address as address',
                                'company_info.company_email as email',
                                'company_info.company_website as website'
                            )
                        );

            $totalFiltered = $handler->where('company_info.id','LIKE',"%{$search}%")
                        ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('company_info.company_number', 'LIKE',"%{$search}%")
                        ->orWhere('company_info.company_telno', 'LIKE',"%{$search}%")
                        ->orWhere('company_info.company_address', 'LIKE',"%{$search}%")
                        ->orWhere('company_info.company_email', 'LIKE',"%{$search}%")
                        ->orWhere('company_info.company_website', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($companys))
        {
            foreach ($companys as $company)
            {
                $nestedData['id'] = $company->id;
                $nestedData['name'] = $company->name;
                $nestedData['number'] = $company->number;
                $nestedData['telno'] = $company->telno;
                $nestedData['address'] = $company->address;
                $nestedData['email'] = $company->email;
                $nestedData['website'] = $company->website;
                
                $nestedData['userrole'] = "
                    <span class='badge badge-danger'> Admin </span>
                ";

                $nestedData['actions'] = "
                <div class='text-center'>
                    <button type='button' class='btn btn-primary' 
                        onclick='showEditCompany(this,{$nestedData['id']})'
                        data-toggle='modal' data-target='#modal-block-normal'>
                        <i class='fa fa-pencil-alt'></i>
                    </button>
                    <button type='button' class='js-swal-confirm btn btn-danger' onclick='delCompany(this,{$nestedData['id']})'>
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
    function updateCompany(Request $request){
        $data = $request->input('data');
        if ($data['id'] == 0){
            $isExist = Company::where('company_name', $data['name'])->get()->first();
            if ($isExist) {
                echo "exist";
                return;
            }
            $res = new Company;
            $res->company_name = $data['name'];
            $res->company_number = $data['number'];
            $res->company_telno = $data['telno'];
            $res->company_address = $data['address'];
            $res->company_email = $data['email'];
            $res->company_website = $data['website'];
            $res->save();
            echo true;
        } else {
            $res = Company::where('id', $data['id'])->get()->first();
            $res->company_name = $data['name'];
            $res->company_number = $data['number'];
            $res->company_telno = $data['telno'];
            $res->company_address = $data['address'];
            $res->company_email = $data['email'];
            $res->company_website = $data['website'];
            $res->save();
            echo true;
        }
    }

    /**
     * Delete Company
     *
     * @return JSON
     */
    function delete(Request $request){
        $id = $request->input('data');
        $res = Company::where('id', $id)->delete();
        return $res;
    }

    /**
     * Get Company By ID
     *
     * @return JSON
     */
    function getCompany(Request $request){
        $id = $request->input('data');
        $company = Company::where('id', $id)->first();
        
        return response()->json($company);
    }


    /**
     * Get Company Profile By ID
     *
     * @return JSON
     */
    function companyProfile(Request $request){
        $userObject = Auth::user();
        $companyID = $userObject->companyid;

        $company = Company::where('id', $companyID)->first();
        return view('clientadmin.companyProfile.companyprofile')->with('company', $company);

        // return response()->json($company);
    }
}
