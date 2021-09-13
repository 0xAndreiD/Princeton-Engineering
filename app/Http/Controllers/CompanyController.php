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
use App\BillingInfo;
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
            5 =>'website',
            6 => 'max_allowable_skip'
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
                        'company_info.company_website as website',
                        'company_info.max_allowable_skip as maxallowskip'
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
                            ->orWhere('company_info.company_website', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.max_allowable_skip', 'LIKE',"%{$search}%");
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
                                'company_info.company_website as website',
                                'company_info.max_allowable_skip as maxallowskip'
                            )
                        );

            $totalFiltered = $handler->where(function ($q) use ($search) {
                            $q->where('company_info.id','LIKE',"%{$search}%")
                            ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_number', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_telno', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_address', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_email', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.company_website', 'LIKE',"%{$search}%")
                            ->orWhere('company_info.max_allowable_skip', 'LIKE',"%{$search}%");
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
                $nestedData['maxallowskip'] = $company->maxallowskip;
                
                // $nestedData['userrole'] = "
                //     <span class='badge badge-danger'> Admin </span>
                // ";

                $nestedData['actions'] = "
                <div class='text-center'>
                    <a class='btn btn-primary' 
                        href='" . route('editCompany') . "?id={$nestedData['id']}'>
                        <i class='fa fa-pencil-alt'></i>
                    </a>
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
     * Edit Company Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    function editCompany(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['id'])){
                $company = Company::find($request['id']);
                return view('admin.company.edit')->with('company', $company);
            } else
                return redirect('home');
        } else 
            return redirect('home');
    }

    /**
     * User Edit View
     *
     * @return JSON
     */
    function updateCompany(Request $request){
        $data = $request;
        if(Auth::user()->userrole == 1)
            $data['id'] = Auth::user()->companyid;
        
        if ($data['id'] == 0){
            $isExist = Company::where('company_name', $data['name'])->get()->first();
            if ($isExist) {
                return response()->json(["success" => false, "message" => "Company already exists with the same name."]);
                return;
            }
            $company = new Company;
            if(Auth::user()->userrole == 2){
                $company->company_name = $data['name'];
                $company->company_number = $data['number'];
            }
            $company->legal_name = $data['legalname'];
            $company->company_telno = $data['telno'];
            $company->company_address = $data['address'];
            $company->second_address = $data['streetaddress'];
            $company->company_email = $data['email'];
            $company->company_website = $data['website'];
            $company->max_allowable_skip = $data['max_allowable_skip'];
            if(!empty($data->file('logofile'))){
                $file = $request->file('logofile');
                $filename = $data['number'] . ". " . $data['name'] . ' ' . $file->getClientOriginalName();
                $file->move(public_path() . '/logos', $filename);
                $company->company_logo = asset('logos') . '/' . $filename;

                //Backup json file to dropbox
                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                $dropboxFile = new DropboxFile(public_path() . '/logos/' . $filename);
                $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_LOGO_PATH') . $filename, ['autorename' => TRUE]);
            } else {
                $company->company_logo = $data['logolink'];
            }
            $company->save();
            return response()->json(["success" => true]);
        } else {
            $company = Company::where('id', $data['id'])->get()->first();

            if(isset($data['name']) && $company->company_name != $data['name'] || isset($data['number']) && $company->company_number != $data['number']){ // change folder name, update job_request table
                $oldName = $company->company_number. '. ' . $company->company_name . '/';
                $newName = $data['number']. '. ' . $data['name'] . '/';
                if(file_exists(storage_path('/input/') . $oldName))
                    rename(storage_path('/input/') . $oldName, storage_path('/input/') . $newName);

                try{
                    $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                    $dropbox = new Dropbox($app);
                    // $listFolderContents = $dropbox->listFolder(env('DROPBOX_JSON_INPUT') . $oldName);
                    // $files = $listFolderContents->getItems()->all();
                    // foreach($files as $file)
                        $dropbox->move(env('DROPBOX_JSON_INPUT') . $company->company_number. '. ' . $company->company_name, env('DROPBOX_JSON_INPUT') . $data['number']. '. ' . $data['name']);
                    // $dropbox->delete(env('DROPBOX_JSON_INPUT') . $oldName);
                } catch (DropboxClientException $e) { }

                $jobs = JobRequest::where('companyId', $data['id'])->where('companyName', '!=', $data['name'])->get();
                foreach($jobs as $job){
                    $job->companyName = $data['name'];
                    $job->save();
                }
            }

            if(Auth::user()->userrole == 2){
                if(isset($data['name'])) $company->company_name = $data['name'];
                if(isset($data['number'])) $company->company_number = $data['number'];
            }
            $company->legal_name = $data['legalname'];
            $company->company_telno = $data['telno'];
            $company->company_address = $data['address'];
            $company->second_address = $data['streetaddress'];
            $company->company_email = $data['email'];
            $company->company_website = $data['website'];
            $company->max_allowable_skip = $data['max_allowable_skip'];
            if(!empty($data->file('logofile'))){
                $file = $request->file('logofile');
                $filename = $data['number'] . ". " . $data['name'] . ' ' . $file->getClientOriginalName();
                $file->move(public_path() . '/logos', $filename);
                $company->company_logo = asset('logos') . '/' . $filename;

                $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                $dropbox = new Dropbox($app);
                $dropboxFile = new DropboxFile(public_path() . '/logos/' . $filename);
                $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_LOGO_PATH') . $filename, ['autorename' => TRUE]);
            } else {
                $company->company_logo = $data['logolink'];
            }
            $company->save();
            return response()->json(["success" => true]);
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
        if(Auth::user()->userrole == 2){
            if(!empty($request['data']))
                $id = $request->input('data');
            else
                $id = Auth::user()->companyid;
            $company = Company::where('id', $id)->first();
            
            return response()->json($company);
        } else {
            $company = Company::where('id', Auth::user()->companyid)->first();
            
            return response()->json($company);
        }
    }


    /**
     * Get Company Profile By ID
     *
     * @return JSON
     */
    function companyInfo(Request $request){
        $userObject = Auth::user();
        $companyID = $userObject->companyid;

        $company = Company::where('id', $companyID)->first();
        return view('clientadmin.companyInfo.companyinfo')->with('company', $company);

        // return response()->json($company);
    }

    /**
     * Return Company Permit Info By state
     *
     * @return JSON
     */
    function getPermitInfo(Request $request){
        $companyId = (Auth::user()->userrole == 1 ? Auth::user()->companyid : $request['id']);
        if(!empty($request['state']) && $companyId){
            if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $companyId == Auth::user()->companyid)
            {
                $permit = PermitInfo::where('company_id', $companyId)->where('state', $request['state'])->first();
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
            if(Auth::user()->userrole == 1)
                $data['id'] = Auth::user()->companyid;

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
    function sealtemplate(Request $request){
        if(Auth::user()->userrole == 2){
            $companyList = Company::orderBy('company_name', 'asc')->get();
            return view('admin.sealpos.templateview')->with('companyList', $companyList);
        }
        else if(Auth::user()->userrole != 0 && Auth::user()->userrole != 4)
            return view('clientadmin.sealpos.templateview');
        else
            return redirect('home');
    }

    /**
     * Show the seal positioning page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    function sealassign(Request $request){
        if(Auth::user()->userrole == 2){
            $companyList = Company::orderBy('company_name', 'asc')->get();
            return view('admin.sealpos.view')->with('companyList', $companyList);
        }
        else if(Auth::user()->userrole != 0 && Auth::user()->userrole != 4)
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
        if(!empty($request->file('upl'))){
            $file = $request->file('upl');
            $filename = time() . '.pdf';
            $file->move(public_path() . '/sealfiles', $filename);
            $company = Company::where('id', (Auth::user()->userrole == 2 && !empty($request['companyId']) ? $request['companyId'] : Auth::user()->companyid))->first();
            $jpgname = $company->company_number . '_' . $company->company_name . '_' . (empty($request['state']) ? time() : $request['state']);
            if($company){
                $im = new Imagick();
                $im->setResolution(200, 200);
                $im->readImage(public_path() . '/sealfiles/' . $filename . "[0]");
                $im->setImageFormat('jpeg');
                $im->setImageCompression(imagick::COMPRESSION_JPEG); 
                $im->setImageCompressionQuality(100);
                $im->writeImage(public_path() . '/sealfiles/' . $jpgname . '.jpg');
                $im->clear();
                $im->destroy();
                // unlink(public_path() . '/sealfiles/' . $filename);
                return response()->json(["status" => true, "filename" => asset('sealfiles') . '/' . $jpgname . '.jpg']);
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
    // function getSealImg(Request $request){
    //     if(Auth::user()->userrole != 0 && Auth::user()->userrole != 4){
    //         if(!empty($request['state'])){
    //             $company = Company::where('id', (Auth::user()->userrole == 2 && !empty($request['companyId']) ? $request['companyId'] : Auth::user()->companyid))->first();
    //             if($company){
    //                 $url = public_path() . '/sealfiles/' . $company->company_number . '_' . $company->company_name . '_' . $request['state'] . '.jpg';
    //                 if(file_exists($url)){
    //                     return response()->json(["url" => asset('sealfiles'). '/' . $company->company_number . '_' . $company->company_name . '_' . $request['state'] . '.jpg', "status" => true]);
    //                 } else
    //                     return response()->json(["message" => "File does not exist.", "status" => false]);
    //             } else
    //                 return response()->json(["message" => "Cannot find the company.", "status" => false]);
    //         } else
    //             return response()->json(["message" => "Missing state.", "status" => false]);
    //     }
    //     else 
    //         return response()->json(["message" => "You don't have permission.", "status" => false]);
    // }

    /**
     * Save seal canvas json data to db
     *
     * @return JSON
     */
    function saveSealData(Request $request){
        if(Auth::user()->userrole != 0 && Auth::user()->userrole != 4){
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
        if(Auth::user()->userrole != 0 && Auth::user()->userrole != 4){
            if(!empty($request['state']) || !empty($request['templateId'])){
                $company = Company::where('id', (Auth::user()->userrole == 2 && !empty($request['companyId']) ? $request['companyId'] : Auth::user()->companyid))->first();
                if($company){
                    if($request['state']){
                        $sealdata = SealData::where('companyId', $company->id)->where('state', $request['state'])->first();
                        if($sealdata)
                            return response()->json(["status" => true, "data" => $sealdata->data]);
                        else
                            return response()->json(["message" => "Empty seal data.", "status" => false]);
                    } else {
                        $sealdata = SealData::where('companyId', $company->id)->where('id', $request['templateId'])->first();
                        if($sealdata)
                            return response()->json(["status" => true, "data" => $sealdata->data]);
                        else
                            return response()->json(["message" => "Empty seal data.", "status" => false]);
                    }
                } else
                    return response()->json(["message" => "Cannot find the company.", "status" => false]);
            } else 
                return response()->json(["message" => "Missing state or templateId.", "status" => false]);
        }
        else 
            return response()->json(["message" => "You don't have permission.", "status" => false]);
    }

    /**
     * Save seal canvas data as a template
     *
     * @return JSON
     */
    function saveSealTemplate(Request $request){
        if(Auth::user()->userrole != 0 && Auth::user()->userrole != 4){
            if(!empty($request['template'])){
                $company = Company::where('id', (Auth::user()->userrole == 2 && !empty($request['companyId']) ? $request['companyId'] : Auth::user()->companyid))->first();
                if($company){
                    SealData::create([
                        'companyId' => $company->id,
                        'data' => $request['data'],
                        'template' => $request['template']
                    ]);
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
     * Return template list
     *
     * @return JSON
     */
    function getSealTemplateList(Request $request){
        if(Auth::user()->userrole != 0 && Auth::user()->userrole != 4){
            if(Auth::user()->userrole == 2){
                $sealdata = SealData::leftjoin("company_info", "company_info.id", "=", "seal_data.companyId")
                            ->get(array('seal_data.id as id', 'seal_data.companyId as companyId', 'seal_data.state as state', 'seal_data.template as template', 'company_info.company_name as companyName'));
                $templates = array();
                foreach($sealdata as $item){
                    if($item->template)
                        $templates[] = array("id" => $item->id, "companyId" => $item->companyId, "title" => $item->companyName . " - " . $item->template);
                }
                return response()->json(["data" => $templates, "status" => true]);
            } else {
                $company = Company::where('id', Auth::user()->companyid)->first();
                if($company){
                    $sealdata = SealData::where('companyId', $company->id)->get(array('id', 'state', 'template'));
                    $templates = array();
                    foreach($sealdata as $item){
                        if($item->template)
                            $templates[] = array("id" => $item->id, "title" => $item->template);
                    }
                    return response()->json(["data" => $templates, "status" => true]);
                } else
                    return response()->json(["message" => "Cannot find the company.", "status" => false]);
            }
        }
        else 
            return response()->json(["message" => "You don't have permission.", "status" => false]);
    }

    /**
     * Return Client Billing Info
     *
     * @return JSON
     */
    function getBillingInfo(Request $request){
        if(Auth::user()->userrole == 1){
            $info = BillingInfo::select('billing_name', 'billing_mail', 'billing_address', 'billing_city', 'billing_state', 'billing_zip', 'billing_same_info', 'shipping_name', 'shipping_mail', 'shipping_address', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_same_info', 'card_name', 'card_number', 'expiration_date', 'security_code')->where('clientId', Auth::user()->companyid)->first();
            if($info)
                return response()->json(["data" => $info, "success" => true]);
            else
                return response()->json(["success" => true]);
        } else if(Auth::user()->userrole == 2){
            if(!empty($request['clientId']))
                $clientId = $request['clientId'];
            else
                $clientId = Auth::user()->companyid;

            $info = BillingInfo::where('clientId', $clientId)->first();
            if($info)
                return response()->json(["data" => $info, "success" => true]);
            else
                return response()->json(["success" => true]);
        }
        else 
            return response()->json(["message" => "You don't have permission.", "success" => false]);
    }

    /**
     * Save Client Billing Info
     *
     * @return JSON
     */
    function saveBillingInfo(Request $request){
        if(Auth::user()->userrole == 1 || Auth::user()->userrole == 2){
            if(Auth::user()->userrole == 2 && !empty($request['clientId']))
                $clientId = $request['clientId'];
            else
                $clientId = Auth::user()->companyid;

            $info = BillingInfo::where('clientId', $clientId)->first();
            if(!$info){
                $info = new BillingInfo;
                $info->clientId = $clientId;
            }

            $info->billing_name = $request['billing_name'];
            $info->billing_mail = $request['billing_mail'];
            $info->billing_address = $request['billing_address'];
            $info->billing_city = $request['billing_city'];
            $info->billing_state = $request['billing_state'];
            $info->billing_zip = $request['billing_zip'];
            $info->billing_same_info = $request['billing_same_info'];

            $info->shipping_name = $request['shipping_name'];
            $info->shipping_mail = $request['shipping_mail'];
            $info->shipping_address = $request['shipping_address'];
            $info->shipping_city = $request['shipping_city'];
            $info->shipping_state = $request['shipping_state'];
            $info->shipping_zip = $request['shipping_zip'];
            $info->shipping_same_info = $request['shipping_same_info'];

            $info->card_name = $request['card_name'];
            $info->card_number = $request['card_number'];
            $info->expiration_date = $request['expiration_date'];
            $info->security_code = $request['security_code'];

            if(Auth::user()->userrole == 2){
                if(isset($request['billing_type'])) $info->billing_type = $request['billing_type'];
                if(isset($request['expected_jobs'])) $info->expected_jobs = $request['expected_jobs'];
                if(isset($request['base_fee'])) $info->base_fee = $request['base_fee'];
                if(isset($request['extra_fee'])) $info->extra_fee = $request['extra_fee'];
                if(isset($request['send_invoice'])) $info->send_invoice = $request['send_invoice'];
                if(isset($request['block_on_fail'])) $info->block_on_fail = $request['block_on_fail'];
            }

            $info->save();

            return response()->json(["success" => true]);
        } else 
            return response()->json(["message" => "You don't have permission.", "success" => false]);
    }

    // function billinginfo(Request $request){
    //     if(Auth::user()->userrole == 2)
    //         return view('admin.billing.view');
    //     else
    //         return redirect('home');
    // }

    // /**
    //  * Get the All Client Biling Info
    //  *
    //  * @return JSON
    //  */
    // public function getCompanyBilling(Request $request){
    //     $columns = array( 
    //         0 =>'id', 
    //         1 =>'name',
    //         2 =>'number',
    //         3 =>'billing_type',
    //         4 =>'amount',
    //         5 =>'send_invoice',
    //         6 => 'block_on_fail'
    //     );
    //     $totalData = Company::count();
    //     $totalFiltered = $totalData; 

    //     $limit = $request->input('length');
    //     $start = $request->input('start');
    //     $order = $columns[$request->input('order.0.column')];
    //     $dir = $request->input('order.0.dir');

    //     $handler = Company::leftjoin("billing_info", "billing_info.clientId", "=", "company_info.id");

    //     if(empty($request->input('search.value')))
    //     {            
    //         $totalFiltered = $handler->count();
    //         $companys = $handler->offset($start)
    //             ->limit($limit)
    //             ->orderBy($order,$dir)
    //             ->get(
    //                 array(
    //                     'company_info.id as id',
    //                     'company_info.company_name as name',
    //                     'company_info.company_number as number',
    //                     'billing_info.billing_type as billing_type', 'billing_info.amount_per_job as amount', 'billing_info.send_invoice as send_invoice', 'billing_info.block_on_fail as block_on_fail', 
    //                 )
    //             );
    //     }
    //     else {
    //         $search = $request->input('search.value'); 
    //         $companys =  $handler->where(function ($q) use ($search) {
    //                         $q->where('company_info.id','LIKE',"%{$search}%")
    //                         ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
    //                         ->orWhere('company_info.company_number', 'LIKE',"%{$search}%");
    //                     })
    //                     ->offset($start)
    //                     ->limit($limit)
    //                     ->orderBy($order,$dir)
    //                     ->get(
    //                         array(
    //                             'company_info.id as id',
    //                             'company_info.company_name as name',
    //                             'company_info.company_number as number',
    //                             'billing_info.billing_type as billing_type', 'billing_info.amount_per_job as amount', 'billing_info.send_invoice as send_invoice', 'billing_info.block_on_fail as block_on_fail', 
    //                         )
    //                     );

    //         $totalFiltered = $handler->where(function ($q) use ($search) {
    //                         $q->where('company_info.id','LIKE',"%{$search}%")
    //                         ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
    //                         ->orWhere('company_info.company_number', 'LIKE',"%{$search}%");
    //                     })
    //                     ->count();
    //     }

    //     $data = array();

    //     if(!empty($companys))
    //     {
    //         foreach ($companys as $company)
    //         {
    //             $nestedData['id'] = $company->id;
    //             $nestedData['name'] = $company->name;
    //             $nestedData['number'] = $company->number;
                
    //             if($company->billing_type == 1)
    //                 $nestedData['billing_type'] = '<span class="badge badge-primary">Bill on Creation Date</span>';
    //             else
    //                 $nestedData['billing_type'] = '<span class="badge badge-warning">Bill on Complete State</span>';

    //             $nestedData['amount'] = $company->amount;

    //             if($company->send_invoice == 1)
    //                 $nestedData['send_invoice'] = '<span class="badge badge-danger">Yes</span>';
    //             else
    //                 $nestedData['send_invoice'] = '<span class="badge badge-primary">No</span>';

    //             if($company->block_on_fail == 1)
    //                 $nestedData['block_on_fail'] = '<span class="badge badge-danger">Yes</span>';
    //             else
    //                 $nestedData['block_on_fail'] = '<span class="badge badge-primary">No</span>';

    //             $nestedData['actions'] = "
    //             <div class='text-center'>
    //                 <button type='button' class='btn btn-warning' 
    //                     onclick='showBillingInfo(this,{$nestedData['id']})'
    //                     data-toggle='modal' data-target='#modal-block-normal'>
    //                     <i class='fa fa-pencil-alt'></i>
    //                 </button>
    //             </div>";
    //             $data[] = $nestedData;
    //         }
    //     }
    //     $json_data = array(
    //         "draw"            => intval($request->input('draw')),  
    //         "recordsTotal"    => intval($totalData),  
    //         "recordsFiltered" => intval($totalFiltered), 
    //         "data"            => $data   
    //         );
    //     echo json_encode($json_data);
    // }
}
