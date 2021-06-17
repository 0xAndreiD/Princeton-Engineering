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
use App\JobRequest;
use App\PermitInfo;
use App\SealData;
use App\SealObjects;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;

use Imagick;


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
        $this->middleware('twofactor');
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
            $companys =  $handler->where(function ($q) use ($search) {
                            $q->where('company_info.id','LIKE',"%{$search}%")
                            ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_number', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_telno', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_address', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_email', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_website', 'LIKE',"%{$search}%");
                        })
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

            $totalFiltered = $handler->where(function ($q) use ($search) {
                            $q->where('company_info.id','LIKE',"%{$search}%")
                            ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_number', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_telno', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_address', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_email', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_website', 'LIKE',"%{$search}%");
                        })
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
                    <button type='button' class='btn btn-warning' 
                        onclick='showPermitInfoCompany(this,{$nestedData['id']})'
                        data-toggle='modal' data-target='#modal-permit-normal'>
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

            if($res->company_name != $data['name'] || $res->company_number != $data['number']){ // change folder name, update job_request table
                $oldName = $res->company_number. '. ' . $res->company_name . '/';
                $newName = $data['number']. '. ' . $data['name'] . '/';
                if(file_exists(storage_path('/input/') . $oldName))
                    rename(storage_path('/input/') . $oldName, storage_path('/input/') . $newName);

                try{
                    $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                    $dropbox = new Dropbox($app);
                    // $listFolderContents = $dropbox->listFolder(env('DROPBOX_JSON_INPUT') . $oldName);
                    // $files = $listFolderContents->getItems()->all();
                    // foreach($files as $file)
                        $dropbox->move(env('DROPBOX_JSON_INPUT') . $res->company_number. '. ' . $res->company_name, env('DROPBOX_JSON_INPUT') . $data['number']. '. ' . $data['name']);
                    // $dropbox->delete(env('DROPBOX_JSON_INPUT') . $oldName);
                } catch (DropboxClientException $e) { }

                $jobs = JobRequest::where('companyId', $data['id'])->where('companyName', '!=', $data['name'])->get();
                foreach($jobs as $job){
                    $job->companyName = $data['name'];
                    $job->save();
                }
            }

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

    /**
     * Return Company Permit Info By state
     *
     * @return JSON
     */
    function getPermitInfo(Request $request){
        if(!empty($request['state']) && !empty($request['id'])){
            if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $request['id'] == Auth::user()->companyid)
            {
                $permit = PermitInfo::where('company_id', $request['id'])->where('state', $request['state'])->first();
                if($permit)
                    return response()->json(["success" => true, "construction_email" => $permit->construction_email, "registration" => $permit->registration, "exp_date" => $permit->exp_date, "ein" => $permit->EIN, "fax" => $permit->FAX, "contact_person" => $permit->contact_person, "contact_phone" => $permit->contact_phone]);
                else
                    return response()->json(["success" => false]);
            }
            else
                return response()->json(["message" => "You don't have permission to edit this project.", "success" => false]);
        } else 
            return response()->json(["message" => "Wrong Parameters.", "success" => false]);
    }

    /**
     * Create Company Permit Info By company_id, state
     *
     * @return JSON
     */
    function updatePermitInfo(Request $request){
        if(!empty($request['data'])){
            $data = $request['data'];
            if(isset($data['state'])){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $data['id'] == Auth::user()->companyid)
                {
                    $permit = PermitInfo::where('company_id', $data['id'])->where('state', $data['state'])->first();
                    if($permit){
                        $permit->construction_email = $data['construction_email'];
                        $permit->registration = $data['registration'];
                        $permit->exp_date = $data['exp_date'];
                        $permit->EIN = $data['ein'];
                        $permit->contact_person = $data['contact_person'];
                        $permit->contact_phone = $data['contact_phone'];
                        $permit->FAX = $data['fax'];
                        $permit->save();
                    }
                    else{
                        PermitInfo::create([
                            'company_id' => Auth::user()->companyid,
                            'state' => $data['state'],
                            'construction_email' => $data['construction_email'],
                            'registration' => $data['registration'],
                            'exp_date' => $data['exp_date'],
                            'EIN' => $data['ein'],
                            'contact_person' => $data['contact_person'],
                            'contact_phone' => $data['contact_phone'],
                            'FAX' => $data['fax']
                        ]);
                    }
                    return response()->json(["success" => true]);
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this project.", "success" => false]);
            } else
                return response()->json(["message" => "Please select the state.", "success" => false]);
        } else 
            return response()->json(["message" => "Wrong Parameters.", "success" => false]);
    }

    /**
     * Show the seal positioning page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    function sealpos(Request $request){
        if(Auth::user()->userrole != 0)
            return view('clientadmin.sealpos.view');
        else
            return redirect('home');
    }

    /**
     * Extract first page from PDF and save it as jpg
     *
     * @return JSON
     */
    function extractImgFromPDF(Request $request){
        if(!empty($request->file('upl')) && !empty($request['state'])){
            $file = $request->file('upl');
            $filename = time() . '.pdf';
            $file->move(public_path() . '/sealfiles', $filename);
            $company = Company::where('id', Auth::user()->companyid)->first();
            if($company){
                $im = new Imagick();
                $im->setResolution(200, 200);
                $im->readImage(public_path() . '/sealfiles/' . $filename . "[0]");
                $im->setImageFormat('jpeg');
                $im->setImageCompression(imagick::COMPRESSION_JPEG); 
                $im->setImageCompressionQuality(100);
                $im->writeImage(public_path() . '/sealfiles/' . $company->company_number . '_' . $company->company_name . '_' . $request['state'] . '.jpg');
                $im->clear();
                $im->destroy();
                unlink(public_path() . '/sealfiles/' . $filename);
                return response()->json(["status" => true]);
            } else
                return response()->json(["message" => "Cannot find the company.", "status" => false]);    
        } else 
            return response()->json(["message" => "Empty file or state.", "status" => false]);
    }

    /**
     * Extract first page from PDF and save it as jpg
     *
     * @return JSON
     */
    function getSealImg(Request $request){
        if(Auth::user()->userrole != 0){
            if(!empty($request['state'])){
                $company = Company::where('id', (Auth::user()->userrole == 2 && !empty($request['companyId']) ? $request['companyId'] : Auth::user()->companyid))->first();
                if($company){
                    $url = public_path() . '/sealfiles/' . $company->company_number . '_' . $company->company_name . '_' . $request['state'] . '.jpg';
                    if(file_exists($url)){
                        return response()->json(["url" => asset('sealfiles'). '/' . $company->company_number . '_' . $company->company_name . '_' . $request['state'] . '.jpg', "status" => true]);
                    } else
                        return response()->json(["message" => "File does not exist.", "status" => false]);
                } else
                    return response()->json(["message" => "Cannot find the company.", "status" => false]);
            } else
                return response()->json(["message" => "Missing state.", "status" => false]);
        }
        else 
            return response()->json(["message" => "You don't have permission.", "status" => false]);
    }

    /**
     * Save seal canvas json data to db
     *
     * @return JSON
     */
    function saveSealData(Request $request){
        if(Auth::user()->userrole != 0){
            if(!empty($request['state'])){
                $company = Company::where('id', (Auth::user()->userrole == 2 && !empty($request['companyId']) ? $request['companyId'] : Auth::user()->companyid))->first();
                if($company){
                    $sealdata = SealData::where('companyId', $company->id)->where('state', $request['state'])->first();
                    if($sealdata){
                        $sealdata->data = $request->data;
                        $sealdata->save();
                    }
                    else{
                        SealData::create([
                            'companyId' => $company->id,
                            'data' => $request->data,
                            'state' => $request->state
                        ]);
                    }
                    if($request['objects']){
                        foreach($request['objects'] as $object){
                            if($object['type'] == 'image'){
                                $sealobj = SealObjects::where('client_id', $company->id)->where('jurisdiction_abbrev', $request['state'])->where('Execute_Command', 'Graphics')->first();
                                if(!$sealobj){
                                    SealObjects::create([
                                        'client_id' => $company->id,
                                        'jurisdiction_abbrev' => $request['state'],
                                        'Execute_Command' => 'Graphics',
                                        'ImageScale' => $object['scaleX'],
                                        'Page_X' => $request['pageWidth'],
                                        'Page_Y' => $request['pageHeight'],
                                        'Top_Lx_rel' => $object['left'] * $request['pageWidth'] / $request['canvasWidth'],
                                        'Top_Ly_rel' => $object['top'] * $request['pageHeight'] / $request['canvasHeight'],
                                        'Bot_Rx_rel' => ($object['left'] + $object['width']) * $request['pageWidth'] / $request['canvasWidth'],
                                        'Bot_Ry_rel' => ($object['top'] + $object['height']) * $request['pageHeight'] / $request['canvasHeight']
                                    ]);
                                } else{
                                    $sealobj->ImageScale = $object['scaleX'];
                                    $sealobj->Page_X = $request['pageWidth'];
                                    $sealobj->Page_Y = $request['pageHeight'];
                                    $sealobj->Top_Lx_rel = $object['left'] * $request['pageWidth'] / $request['canvasWidth'];
                                    $sealobj->Top_Ly_rel = $object['top'] * $request['pageHeight'] / $request['canvasHeight'];
                                    $sealobj->Bot_Rx_rel = ($object['left'] + $object['width']) * $request['pageWidth'] / $request['canvasWidth'];
                                    $sealobj->Bot_Ry_rel = ($object['top'] + $object['height']) * $request['pageHeight'] / $request['canvasHeight'];
                                    $sealobj->save();
                                }
                            } else if($object['type'] == 'textbox'){
                                $sealobj = SealObjects::where('client_id', $company->id)->where('jurisdiction_abbrev', $request['state'])->where('text', $object['text'])->first();
                                if(!$sealobj){
                                    if($object['text'] == 'eSign Text') 
                                        $objType = 'eSeal';
                                    else
                                        $objType = 'Text';
                                    SealObjects::create([
                                        'client_id' => $company->id,
                                        'jurisdiction_abbrev' => $request['state'],
                                        'Execute_Command' => $objType,
                                        'text' => $object['text'],
                                        'FontSize' => $object['fontSize'],
                                        'Page_X' => $request['pageWidth'],
                                        'Page_Y' => $request['pageHeight'],
                                        'Top_Lx_rel' => $object['left'] * $request['pageWidth'] / $request['canvasWidth'],
                                        'Top_Ly_rel' => $object['top'] * $request['pageHeight'] / $request['canvasHeight'],
                                        'Bot_Rx_rel' => ($object['left'] + $object['width']) * $request['pageWidth'] / $request['canvasWidth'],
                                        'Bot_Ry_rel' => ($object['top'] + $object['height']) * $request['pageHeight'] / $request['canvasHeight']
                                    ]);
                                } else{
                                    $sealobj->fontSize = $object['fontSize'];
                                    $sealobj->Page_X = $request['pageWidth'];
                                    $sealobj->Page_Y = $request['pageHeight'];
                                    $sealobj->FontSize = $object['fontSize'];
                                    $sealobj->Top_Lx_rel = $object['left'] * $request['pageWidth'] / $request['canvasWidth'];
                                    $sealobj->Top_Ly_rel = $object['top'] * $request['pageHeight'] / $request['canvasHeight'];
                                    $sealobj->Bot_Rx_rel = ($object['left'] + $object['width']) * $request['pageWidth'] / $request['canvasWidth'];
                                    $sealobj->Bot_Ry_rel = ($object['top'] + $object['height']) * $request['pageHeight'] / $request['canvasHeight'];
                                    $sealobj->save();
                                }
                            }
                        }
                    }

                    return response()->json(["status" => true]);
                } else
                    return response()->json(["message" => "Cannot find the company.", "status" => false]);
            } else
                return response()->json(["message" => "Missing state.", "status" => false]);
        }
        else 
            return response()->json(["message" => "You don't have permission.", "status" => false]);
    }

    /**
     * Return seal canvas json data
     *
     * @return JSON
     */
    function loadSealData(Request $request){
        if(Auth::user()->userrole != 0){
            if(!empty($request['state'])){
                $company = Company::where('id', (Auth::user()->userrole == 2 && !empty($request['companyId']) ? $request['companyId'] : Auth::user()->companyid))->first();
                if($company){
                    $sealdata = SealData::where('companyId', $company->id)->where('state', $request['state'])->first();
                    if($sealdata)
                        return response()->json(["status" => true, "data" => $sealdata->data]);
                    else
                        return response()->json(["message" => "Empty seal data.", "status" => false]);
                } else
                    return response()->json(["message" => "Cannot find the company.", "status" => false]);
            } else 
                return response()->json(["message" => "Missing state.", "status" => false]);
        }
        else 
            return response()->json(["message" => "You don't have permission.", "status" => false]);
    }

    /**
     * Save seal canvas data as a template
     *
     * @return JSON
     */
    function saveAsTemplate(Request $request){
        if(Auth::user()->userrole != 0){
            if(!empty($request['state']) && !empty($request['template'])){
                $company = Company::where('id', (Auth::user()->userrole == 2 && !empty($request['companyId']) ? $request['companyId'] : Auth::user()->companyid))->first();
                if($company){
                    $sealdata = SealData::where('companyId', $company->id)->where('state', $request['state'])->first();
                    if($sealdata){
                        $sealdata->template = $request['template'];
                        $sealdata->save();
                        return response()->json(["status" => true]);
                    }
                    else
                        return response()->json(["message" => "Cannot find the seal data. Please confirm you saved correctly before saving as a template.", "status" => false]);
                } else
                    return response()->json(["message" => "Cannot find the company.", "status" => false]);
            } else
                return response()->json(["message" => "Missing state.", "status" => false]);
        }
        else 
            return response()->json(["message" => "You don't have permission.", "status" => false]);
    }

    /**
     * Return template list
     *
     * @return JSON
     */
    function getTemplateList(Request $request){
        if(Auth::user()->userrole != 0){
            $company = Company::where('id', (Auth::user()->userrole == 2 && !empty($request['companyId']) ? $request['companyId'] : Auth::user()->companyid))->first();
            if($company){
                $sealdata = SealData::where('companyId', $company->id)->get();
                $templates = array();
                foreach($sealdata as $item){
                    if($item->template)
                        $templates[] = array("state" => $item->state, "title" => $item->template);
                }
                return response()->json(["data" => $templates, "status" => true]);
            } else
                return response()->json(["message" => "Cannot find the company.", "status" => false]);
        }
        else 
            return response()->json(["message" => "You don't have permission.", "status" => false]);
    }
}
