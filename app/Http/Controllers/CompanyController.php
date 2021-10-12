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
use App\BillingHistory;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Dompdf\Dompdf;
use Dompdf\Options;

use Imagick;
use Response;
use Mail;

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
            $company->city = $data['city'];
            $company->state = $data['state'];
            $company->zip = $data['zip'];
            $company->company_email = $data['email'];
            $company->company_website = $data['website'];
            $company->max_allowable_skip = $data['max_allowable_skip'];
            $company->bill_notifiers = $data['bill_notifiers'];
            if(!empty($data->file('logofile'))){
                $file = $request->file('logofile');
                $filename = $data['number'] . ". " . $data['name'] . ' ' . $file->getClientOriginalName();
                $file->move(public_path() . '/logos', $filename);
                $company->company_logo = $filename;

                //Backup json file to dropbox
                // $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                // $dropbox = new Dropbox($app);
                // $dropboxFile = new DropboxFile(public_path() . '/logos/' . $filename);
                // $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_LOGO_PATH') . $filename, ['autorename' => TRUE]);
            } 
            // else {
            //     $company->company_logo = $data['logolink'];
            // }
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
            $company->city = $data['companycity'];
            $company->state = $data['companystate'];
            $company->zip = $data['companyzip'];
            $company->company_email = $data['email'];
            $company->company_website = $data['website'];
            $company->max_allowable_skip = $data['max_allowable_skip'];
            $company->bill_notifiers = $data['bill_notifiers'];
            if(!empty($data->file('logofile'))){
                $file = $request->file('logofile');
                $filename = $data['number'] . ". " . $data['name'] . ' ' . $file->getClientOriginalName();
                $file->move(public_path() . '/logos', $filename);
                $company->company_logo = $filename;

                // $app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));
                // $dropbox = new Dropbox($app);
                // $dropboxFile = new DropboxFile(public_path() . '/logos/' . $filename);
                // $dropfile = $dropbox->upload($dropboxFile, env('DROPBOX_LOGO_PATH') . $filename, ['autorename' => TRUE]);
            }
            // else {
            //     $company->company_logo = $data['logolink'];
            // }
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
                if(isset($request['billing_period'])) $info->billing_period = $request['billing_period'];
                if(isset($request['billing_day'])) $info->billing_day = $request['billing_day'];
                if(isset($request['block_days_after'])) $info->block_days_after = $request['block_days_after'];
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

    /**
     * Show billing history of all clients, with various features.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function bills(Request $request){
        if(Auth::user()->userrole == 2){
            $companyList = Company::orderBy('company_name', 'asc')->get();
            return view('admin.bills.view')->with('companyList', $companyList);
        } else if(Auth::user()->userrole == 1){
            return view('clientadmin.bills.view');
        }
    }

    /**
     * Return the list of bills
     *
     * @return JSON
     */
    public function getBills(Request $request){
        if(Auth::user()->userrole == 2){
            $columns = array( 
                0 =>'id', 
                1 =>'companyname', 
                2 =>'issuedDate',
                3 =>'issuedFrom',
                4 =>'issuedTo',
                5 =>'jobCount',
                6 =>'amount',
                7 => 'state'
            );
            $handler = new BillingHistory;
        } else {
            $columns = array( 
                0 =>'id', 
                1 =>'issuedDate',
                2 =>'issuedFrom',
                3 =>'issuedTo',
                4 =>'jobCount',
                5 =>'amount',
                6 => 'state'
            );
            $handler = BillingHistory::where('companyId', Auth::user()->companyid);
        }
        $totalData = BillingHistory::count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $handler = $handler->leftjoin("company_info", "company_info.id", "=", "billing_history.companyId");

        if(!empty($request->input("columns.1.search.value")))
            $handler = $handler->where('companyId', '=', $request->input("columns.1.search.value"));
        if(!empty($request->input("columns.7.search.value")))
            $handler = $handler->where('state', '=', $request->input("columns.7.search.value") - 1);
        
        // filter issued_from
        if(!empty($request->input("issued_at")) && $request->input("issued_at") != "")
            $handler = $handler->where('billing_history.issuedAt', 'LIKE', "{$request->input("issued_at")}%");
        
        // filter issued_from
        if(!empty($request->input("issued_from")) && $request->input("issued_from") != "")
            $handler = $handler->where('billing_history.issuedFrom', '>=', $request->input("issued_from"));
        
        // filter issued_to
        if(!empty($request->input("issued_to")) && $request->input("issued_to") != "")
            $handler = $handler->where('billing_history.issuedTo', '<=', $request->input("issued_to"));

        if(empty($request->input('search.value')))
        {            
            $totalFiltered = $handler->count();
            $bills = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array(
                        'billing_history.id as id',
                        'company_info.company_name as companyname',
                        'billing_history.issuedAt as issuedAt', 'billing_history.issuedFrom as issuedFrom', 'billing_history.issuedTo as issuedTo', 'billing_history.jobCount as jobCount', 'billing_history.jobIds as jobIds', 'billing_history.amount as amount', 'billing_history.state as state',
                    )
                );
        }
        else {
            $search = $request->input('search.value'); 
            $bills =  $handler->where(function ($q) use ($search) {
                            $q->where('billing_history.id','LIKE',"%{$search}%")
                            ->orWhere('billing_history.jobCount', 'LIKE',"%{$search}%")
                            ->orWhere('billing_history.issuedAt', 'LIKE',"%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array(
                                array(
                                    'billing_history.id as id',
                                    'company_info.company_name as companyname',
                                    'billing_history.issuedAt as issuedAt', 'billing_history.issuedFrom as issuedFrom', 'billing_history.issuedTo as issuedTo', 'billing_history.jobCount as jobCount', 'billing_history.jobIds as jobIds', 'billing_history.amount as amount', 'billing_history.state as state',
                                )
                            )
                        );

            $totalFiltered = $handler->where(function ($q) use ($search) {
                            $q->where('billing_history.id','LIKE',"%{$search}%")
                            ->orWhere('billing_history.jobCount', 'LIKE',"%{$search}%")
                            ->orWhere('billing_history.issuedDate', 'LIKE',"%{$search}%");
                        })
                        ->count();
        }

        $data = array();

        if(!empty($bills))
        {
            foreach ($bills as $bill)
            {
                $nestedData['id'] = $bill->id;
                $nestedData['companyname'] = $bill->companyname;
                $nestedData['issuedDate'] = $bill->issuedAt;
                $nestedData['issuedFrom'] = $bill->issuedFrom;
                $nestedData['issuedTo'] = $bill->issuedTo;
                
                $jobs = JobRequest::whereIn('id', json_decode($bill->jobIds))->where('billed', 1)->get('id');
                
                $nestedData['jobCount'] = count($jobs) . ' / ' . $bill->jobCount;
                $nestedData['amount'] = '$' . $bill->amount;

                if($bill->state == 0) { $badgeColor = 'danger'; $badgeTxt = 'Unpaid'; }
                if($bill->state == 1) { $badgeColor = 'warning'; $badgeTxt = 'Failed'; }
                if($bill->state == 2) { $badgeColor = 'success'; $badgeTxt = 'Paid'; }
                if($bill->state == 3) { $badgeColor = 'dark'; $badgeTxt = 'Deleted'; }

                if(Auth::user()->userrole == 2){
                    $nestedData['state'] = "<span class='badge badge-{$badgeColor} dropdown-toggle job-dropdown' style='color: #fff;' id='state_{$bill->id}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> {$badgeTxt} </span>";
                    $nestedData['state'] .= "<div class='dropdown-menu' aria-labelledby='state_{$bill->id}'>";
                    $nestedData['state'] .= "<a class='dropdown-item' href='javascript:changeState(this, {$bill->id}, 0)' style='color: white; background-color: #e04f1a;'>Unpaid</a>";
                    $nestedData['state'] .= "<a class='dropdown-item' href='javascript:changeState(this, {$bill->id}, 1)' style='color: white; background-color: rgb(255, 177, 25);'>Failed</a>";
                    $nestedData['state'] .= "<a class='dropdown-item' href='javascript:changeState(this, {$bill->id}, 2)' style='color: white; background-color: #82b54b;'>Paid</a>";
                    $nestedData['state'] .= "</div>";

                    $nestedData['actions'] = "
                    <div class='text-center' style='display: flex; align-items: center; justify-content: center;'>
                        <button type='button' class='btn btn-success mr-1' onclick='editBill(this, {$bill->id})' style='padding: 3px 4px;'>
                            <i class='fa fa-pencil-alt'></i>
                        </button>
                        <a href='invoice?id={$bill['id']}' class='btn btn-warning mr-1' style='padding: 3px 4px; " . ($bill->state == 3 ? "opacity: 0.5; pointer-events: none;" : "") . "' target='_blank'>
                            <i class='fa fa-download'></i>
                        </a>
                        <button type='button' class='btn btn-success mr-1' onclick='chargeNow(this, {$bill->id})' style='padding: 3px 4px;' " . ($bill->state == 2 || $bill->state == 3 || count($jobs) == $bill->jobCount ? "disabled" : "") . ">
                            <i class='fa fa-money-check'></i>
                        </button>
                        <button type='button' class='btn btn-primary mr-1' onclick='markAsPaid(this, {$bill->id})' style='padding: 3px 4px;' " . ($bill->state == 2 || $bill->state == 3 ? "disabled" : "") . ">
                            <i class='fa fa-check'></i>
                        </button>
                        <button type='button' class='btn btn-danger mr-1' onclick='delBill(this, {$bill->id})' style='padding: 3px 4px;'>
                            <i class='fa fa-trash'></i>
                        </button>
                    </div>
                    ";
                } else {
                    $nestedData['state'] = "<span class='badge badge-{$badgeColor}' style='white-space: pre-wrap; color: #fff;'> {$badgeTxt} </span>";
                    $nestedData['actions'] = "
                    <div class='text-center' style='display: flex; align-items: center; justify-content: center;'>
                        <a href='invoice?id={$bill['id']}' class='btn btn-warning mr-1' style='padding: 3px 4px;' target='_blank'>
                            <i class='fa fa-download'></i>
                        </a>
                        <button type='button' class='btn btn-success mr-1' onclick='chargeNow(this, {$bill->id})' style='padding: 3px 4px;' " . ($bill->state == 2 ? "disabled" : "") . ">
                            <i class='fa fa-money-check'></i>
                        </button>
                    </div>
                    ";
                }
                
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
     * Return the invoice file pdf.
     *
     * @return FILE or Redirect
     */
    public function getInvoice(Request $request){
        if(!empty($request['id'])){
            $bill = BillingHistory::where('id', $request['id'])->first();
            if($bill){
                if($bill->invoice && $bill->invoice != ''){
                    if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1){
                        if(Auth::user()->userrole == 1 && Auth::user()->companyid != $bill->companyId) // check permission
                            return redirect('home');
    
                        if(Storage::disk('invoice')->exists($bill->invoice)){
                            $path = storage_path('invoice') . '/' . $bill->invoice;
                            
                            $file = Storage::disk('invoice')->get($bill->invoice);
                            $type = Storage::disk('invoice')->mimeType($bill->invoice);
            
                            $response = Response::make($file, 200);
                            $response->header("Content-Type", $type);
            
                            return $response;
                        } else
                            abort(404);
                    } else
                        return redirect('home');
                } else
                    abort(404);
            } else 
                return redirect('home');
        } else
            return redirect('home');
    }

    /**
     * Charge the payment.
     *
     * @return FILE or Redirect
     */
    public function chargeNow(Request $request){
        if(!empty($request['id'])){
            $bill = BillingHistory::where('id', $request['id'])->first();
            if($bill){
                if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1){
                    if(Auth::user()->userrole == 1 && Auth::user()->companyid != $bill->companyId)
                        return response()->json(["success" => false, "message" => "You don't have any permission."]);

                    $company = Company::where('id', $bill->companyId)->first();
                    $billInfo = BillingInfo::where('clientId', $bill->companyId)->first();
                    
                    if($this->chargeCreditCard($bill, $company, $billInfo, $bill->amount, time()))
                        return response()->json(["success" => true]);
                    else
                        return response()->json(["success" => false, "message" => "Transaction failed. Please check the error details from the email."]);
                } else
                    return response()->json(["success" => false, "message" => "You don't have any permission."]);
            } else
                return response()->json(["success" => false, "message" => "Cannot find the bill."]);
        } else
            return response()->json(["success" => false, "message" => "Empty Bill Id."]);
    }

    /**
     * Create the invoice and set the invoice filename to invoice field.
     *
     * @return Boolean
     */
    private function createInvoice($type, $curBill, $company, $billInfo, $curtime){
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new DOMPDF($options);
        $dompdf->setPaper('A4', 'portrait');
        
        $jobs = JobRequest::whereIn('job_request.id', json_decode($curBill->jobIds))->leftjoin('users', function($join){
                $join->on('job_request.companyId', '=', 'users.companyid');
                $join->on('job_request.userId', '=', 'users.usernumber');
            })->get(
                array('job_request.id as id', 'users.username as username', 'job_request.clientProjectName as projectname', 'job_request.clientProjectNumber as projectnumber', 'job_request.state as state', 'job_request.createdTime as createdtime', 'job_request.submittedTime as submittedtime')
            );
        
        $html = view('pdf.invoice')
                ->with('type', $type)
                ->with('curBill', $curBill)
                ->with('invoiceDate', gmdate("Y-m-d", $curtime))
                ->with('company', $company)
                ->with('billInfo', $billInfo)
                ->with('jobs', $jobs)
                ->render();

        $dompdf->load_html($html);
        $dompdf->render();
        $output = $dompdf->output();

        $filepath = storage_path('invoice') . '/' . $company->company_number . '. '. $company->company_name . ' ' . $curtime . '.pdf';
        file_put_contents($filepath, $output);

        $curBill->invoice = $company->company_number . '. '. $company->company_name . ' ' . $curtime . '.pdf';
        $curBill->save();
    }

    /**
     * Send bill notification to client / supers
     *
     * @return Boolean
     */
    private function sendBillMail($type, $curBill, $company, $billInfo, $error = ''){
        $data = ['type' => $type, 'curBill' => $curBill, 'company' => $company, 'cardnumber' => substr($billInfo->card_number, -4), 'issuedDate' => date('Y-m-d', strtotime($curBill->issuedAt)),'error' => $error];
        
        if($company->bill_notifiers){
            $notifiers = explode(";", $company->bill_notifiers);
            foreach($notifiers as $notifier){
                if($notifier != ''){
                    $info = ['email' => $notifier, 'filename' => $curBill->invoice];
                    Mail::send('mail.billnotification', $data, function ($m) use ($info) {
                        $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($info['email'])->subject('Important! iRoof Bill Notification.');
                        $m->attach(storage_path('invoice') . '/' . $info['filename']);
                    });
                }
            }
        }

        $supers = User::where('userrole', 2)->get();
        foreach($supers as $super){
            $info = ['email' => $super->email, 'filename' => $curBill->invoice];
            Mail::send('mail.billsupernotify', $data, function ($m) use ($info) {
                $m->from(env('MAIL_FROM_ADDRESS'), 'Princeton Engineering')->to($info['email'])->subject('Important! iRoof Bill Notification.');
                $m->attach(storage_path('invoice') . '/' . $info['filename']);
            });
        }
    }

    /**
     * Authorize and Capture credit card payments
     *
     * @return Authorize.Net Response
     */
    private function chargeCreditCard($curBill, $company, $billInfo, $amount, $curtime)
    {
        //echo "Processing credit card payments for " . $company->company_number . ". " . $company->company_name . " with historyId: " . $curBill->id . "\n";
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        
        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber(str_replace(' ', '', $billInfo->card_number));
        $creditCard->setExpirationDate($billInfo->expiration_date);
        $creditCard->setCardCode($billInfo->security_code);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create order information
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($curBill->id);
        $order->setDescription("iRoof Engineering Services");

        if($billInfo->billing_name != ''){
            // Set the customer's Bill To address
            $customerAddress = new AnetAPI\CustomerAddressType();
            $customerAddress->setFirstName($billInfo->billing_name);
            $customerAddress->setCompany($company->legal_name);
            $customerAddress->setAddress($billInfo->billing_address);
            $customerAddress->setCity($billInfo->billing_city);
            $customerAddress->setState($billInfo->billing_state);
            $customerAddress->setZip($billInfo->billing_zip);
            $customerAddress->setCountry("USA");
        }

        if($billInfo->shipping_name != ''){
            // Set the customer's Ship To address
            $shipAddress = new AnetAPI\CustomerAddressType();
            $shipAddress->setFirstName($billInfo->shipping_name);
            $shipAddress->setCompany("Princeton Engineering");
            $shipAddress->setAddress($billInfo->shipping_address);
            $shipAddress->setCity($billInfo->shipping_city);
            $shipAddress->setState($billInfo->shipping_state);
            $shipAddress->setZip($billInfo->shipping_zip);
            $shipAddress->setCountry("USA");
        }

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($company->company_number);
        $customerData->setEmail($company->company_email);

        // Add values for transaction settings
        // $duplicateWindowSetting = new AnetAPI\SettingType();
        // $duplicateWindowSetting->setSettingName("duplicateWindow");
        // $duplicateWindowSetting->setSettingValue("60");

        // Add some merchant defined fields. These fields won't be stored with the transaction,
        // but will be echoed back in the response.
        // $merchantDefinedField1 = new AnetAPI\UserFieldType();
        // $merchantDefinedField1->setName("customerLoyaltyNum");
        // $merchantDefinedField1->setValue("1128836273");

        // $merchantDefinedField2 = new AnetAPI\UserFieldType();
        // $merchantDefinedField2->setName("favoriteColor");
        // $merchantDefinedField2->setValue("blue");

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);
        if($billInfo->billing_name)
            $transactionRequestType->setBillTo($customerAddress);
        if($billInfo->shipping_name)
            $transactionRequestType->setBillTo($shipAddress);
        $transactionRequestType->setCustomer($customerData);
        // $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
        // $transactionRequestType->addToUserFields($merchantDefinedField1);
        // $transactionRequestType->addToUserFields($merchantDefinedField2);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        

        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();
            
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    //echo " Successfully created transaction with";
                    $curBill->response = " Transaction ID: " . $tresponse->getTransId() . "\n";
                    $curBill->response .= (" Transaction Response Code: " . $tresponse->getResponseCode() . "\n");
                    $curBill->response .= (" Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n");
                    $curBill->response .= (" Auth Code: " . $tresponse->getAuthCode() . "\n");
                    $curBill->response .= (" Description: " . $tresponse->getMessages()[0]->getDescription() . "\n");
                    //echo $curBill->response;

                    $curBill->state = 2;
                    $curBill->save();

                    $this->setBilled($curBill);
                    $this->createInvoice(1, $curBill, $company, $billInfo, $curtime); // Paid invoice
                    $this->sendBillMail(2, $curBill, $company, $billInfo, '');
                    return true;
                } else {
                    //echo "Transaction Failed \n";
                    $error = '';
                    if ($tresponse->getErrors() != null) {
                        $curBill->response = " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        $curBill->response .= (" Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n");
                        $error = $tresponse->getErrors()[0]->getErrorText();
                        //echo $curBill->response;
                    }
                    $curBill->state = 1;
                    $curBill->save();
                    $this->sendBillMail(1, $curBill, $company, $billInfo, $error);
                    return false;
                }
                // Or, print errors if the API request wasn't successful
            } else {
                //echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
            
                $error = '';
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $curBill->response = " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    $curBill->response .= (" Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n");
                    $error = $tresponse->getErrors()[0]->getErrorText();
                    //echo $curBill->response;
                } else {
                    $curBill->response = " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    $curBill->response .= (" Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n");
                    $error = $response->getMessages()->getMessage()[0]->getText();
                    //echo $curBill->response;
                }
                $curBill->state = 1;
                $curBill->save();
                $this->sendBillMail(1, $curBill, $company, $billInfo, $error);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Set job_request jobs' billed to 1
     *
     * @return Boolean
     */
    private function setBilled($history){
        if($history && $history->jobIds){
            $jobIds = json_decode($history->jobIds);
            foreach($jobIds as $id){
                $job = JobRequest::where('id', $id)->first();
                if($job){
                    $job->billed = 1;
                    $job->save();
                }
            }
            return true;
        } else
            return false;
    }

    /**
     * mark jobs as paid
     *
     * @return JSON
     */
    public function markAsPaid(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['id'])){
                $bill = BillingHistory::where('id', $request['id'])->first();
                if($bill){
                    $jobs = JobRequest::whereIn('id', json_decode($bill->jobIds))->get();
                    foreach($jobs as $job){
                        $job->billed = 1;
                        $job->save();
                    }
                    $bill->state = 2;
                    $bill->save();
                    return response()->json(["success" => true]);
                } else 
                    return response()->json(["success" => false, "message" => "Cannot find the bill."]);
            } else
                return response()->json(["success" => false, "message" => "Empty bill id."]);
        } else
            return response()->json(["success" => false, "message" => "You don't have any permission."]);
    }

    /**
     * set bill state to deleted
     *
     * @return JSON
     */
    public function delBill(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['id'])){
                $bill = BillingHistory::where('id', $request['id'])->first();
                if($bill){
                    $bill->state = 3;
                    $bill->save();
                    return response()->json(["success" => true]);
                } else 
                    return response()->json(["success" => false, "message" => "Cannot find the bill."]);
            } else
                return response()->json(["success" => false, "message" => "Empty bill id."]);
        } else
            return response()->json(["success" => false, "message" => "You don't have any permission."]);
    }

    /**
     * set bill state
     *
     * @return JSON
     */
    public function setBillState(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['id']) && isset($request['state'])){
                $bill = BillingHistory::where('id', $request['id'])->first();
                if($bill){
                    $bill->state = $request['state'];
                    $bill->save();
                    return response()->json(["success" => true]);
                } else 
                    return response()->json(["success" => false, "message" => "Cannot find the bill."]);
            } else
                return response()->json(["success" => false, "message" => "Empty bill id."]);
        } else
            return response()->json(["success" => false, "message" => "You don't have any permission."]);
    }

    /**
     * return billing history data
     *
     * @return JSON
     */
    public function getBillData(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['id'])){
                $bill = BillingHistory::where('id', $request['id'])->first();
                if($bill){
                    return response()->json(["success" => true, "data" => $bill]);
                } else 
                    return response()->json(["success" => false, "message" => "Cannot find the bill."]);
            } else
                return response()->json(["success" => false, "message" => "Empty bill id."]);
        } else
            return response()->json(["success" => false, "message" => "You don't have any permission."]);
    }

    /**
     * Save edited bill data
     *
     * @return JSON
     */
    public function saveBill(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['id'])){
                $bill = BillingHistory::where('id', $request['id'])->first();
                if($bill){
                    $bill->companyId = $request['companyId'];
                    $bill->issuedAt = $request['issuedAt'];
                    $bill->issuedFrom = $request['issuedFrom'];
                    $bill->issuedTo = $request['issuedTo'];
                    $bill->jobCount = $request['jobCount'];
                    $bill->jobIds = json_encode($request['jobIds']);
                    $bill->amount = $request['amount'];
                    $bill->state = $request['state'];
                    $bill->save();

                    if($request['updatePDF']){
                        $company = Company::where('id', $bill->companyId)->first();
                        $billInfo = BillingInfo::where('clientId', $bill->companyId)->first();
                        if($bill->state == 2)
                            $this->createInvoice(1, $bill, $company, $billInfo, strtotime($bill->issuedAt));
                        else
                            $this->createInvoice(0, $bill, $company, $billInfo, strtotime($bill->issuedAt));
                    }

                    return response()->json(["success" => true]);
                } else 
                    return response()->json(["success" => false, "message" => "Cannot find the bill."]);
            } else{
                $bill = new BillingHistory;
                $bill->companyId = $request['companyId'];
                if(!empty($request['issuedAt']))
                    $bill->issuedAt = $request['issuedAt'];
                else
                    $bill->issuedAt = gmdate("Y-m-d\TH:i:s", $curtime);
                $bill->issuedFrom = $request['issuedFrom'];
                $bill->issuedTo = $request['issuedTo'];
                $bill->jobCount = $request['jobCount'];
                $bill->jobIds = json_encode($request['jobIds']);
                $bill->amount = $request['amount'];
                $bill->state = $request['state'];
                $bill->save();

                if($request['updatePDF']){
                    $company = Company::where('id', $bill->companyId)->first();
                    $billInfo = BillingInfo::where('clientId', $bill->companyId)->first();
                    if($bill->state == 2)
                        $this->createInvoice(1, $bill, $company, $billInfo, strtotime($bill->issuedAt));
                    else
                        $this->createInvoice(0, $bill, $company, $billInfo, strtotime($bill->issuedAt));
                }
                return response()->json(["success" => true]);
            }
        } else
            return response()->json(["success" => false, "message" => "You don't have any permission."]);
    }

    /**
     * Return cc last 4 numbers, and payment due date
     *
     * @return JSON
     */
    public function getPaymentShortInfo(Request $request){
        if(!empty($request['id'])){
            $bill = BillingHistory::where('id', $request['id'])->first();
            if($bill){
                $billInfo = BillingInfo::where('clientId', $bill->companyId)->first();
                if($billInfo){
                    if($billInfo->block_days_after)
                        $duedate = date('Y-m-d', strtotime("+{$billInfo->block_days_after} day", strtotime($bill->issuedAt)));
                    else
                        $duedate = date('Y-m-d', strtotime($bill->issuedAt));

                    if($billInfo->card_number)
                        $cardnumber = substr($billInfo->card_number, -4);
                    else
                        $cardnumber = 'XXXX';

                    return response()->json(["success" => true, "duedate" => $duedate, "cardnumber" => $cardnumber]);
                } else 
                    return response()->json(["success" => false, "message" => "Cannot find the billing info."]);
            } else 
                return response()->json(["success" => false, "message" => "Cannot find the bill."]);
        } else
            return response()->json(["success" => false, "message" => "Empty bill id."]);
    }

    /**
     * call billing routines right now
     *
     * @return JSON
     */
    public function billNow(Request $request){
        if(!empty($request['companyIds'])){
            foreach($request['companyIds'] as $id){
                $company = Company::where('id', $id)->first();
                if($company){
                    $billInfo = BillingInfo::where('clientId', $company->id)->first();
                    if($billInfo){
                        $dateFrom = date('Y-m-d', strtotime($request['issuedFrom']));
                        $dateTo = date('Y-m-d', strtotime($request['issuedTo']));
                        $timeFrom = $dateFrom . ' 00:00:00';
                        $timeTo = $dateTo . ' 23:59:59';

                        // collect the jobs that need to be billed
                        if($billInfo->billing_type == 0){
                            $jobs = JobRequest::leftjoin('users', function($join){
                                $join->on('job_request.companyId', '=', 'users.companyid');
                                $join->on('job_request.userId', '=', 'users.usernumber');
                            })
                            ->where('job_request.companyId', $company->id)->where('job_request.billed', '0')->where('job_request.projectState', '9')
                            ->get(
                                array('job_request.id as id', 'users.username as username', 'job_request.clientProjectName as projectname', 'job_request.clientProjectNumber as projectnumber', 'job_request.state as state', 'job_request.createdTime as createdtime', 'job_request.submittedTime as submittedtime')
                            );
                        }
                        else{
                            $jobs = JobRequest::leftjoin('users', function($join){
                                $join->on('job_request.companyId', '=', 'users.companyid');
                                $join->on('job_request.userId', '=', 'users.usernumber');
                            })
                            ->where('job_request.companyId', $company->id)->where('job_request.billed', '0')->where('job_request.createdTime', '>=', $timeFrom)->where('job_request.createdTime', '<=', $timeTo)
                            ->get(
                                array('job_request.id as id', 'users.username as username', 'job_request.clientProjectName as projectname', 'job_request.clientProjectNumber as projectnumber', 'job_request.state as state', 'job_request.createdTime as createdtime', 'job_request.submittedTime as submittedtime')
                            );
                        }
                        
                        $curtime = time();
                        if(count($jobs) > 0){
                            // calculate this month's billed jobs count
                            $month = date('m');
                            $histories = BillingHistory::where('companyId', $company->id)->whereRaw('Month(issuedAt) = '.$month)->where('state', 2)->get();
                            $billedCount = 0;
                            foreach($histories as $history)
                                $billedCount += $history->jobCount;
                            
                            // calcs the number of not exceeded job counts, exceeded job counts in the month
                            if($billedCount >= $billInfo->expected_jobs){
                                $notExceeded = 0;
                                $exceeded = count($jobs);
                            } else {
                                $notExceeded = min($billInfo->expected_jobs - $billedCount, count($jobs));
                                $exceeded = count($jobs) - $notExceeded;
                            }
                            $amount = $billInfo->base_fee * $notExceeded + $billInfo->extra_fee * $exceeded;

                            $curBill = new BillingHistory();
                            $curBill->companyId = $company->id;
                            $curBill->amount = $amount;
                            $jobIds = array();
                            foreach($jobs as $job)
                                $jobIds[] = $job->id;
                            $curBill->jobIds = json_encode($jobIds);
                            $curBill->jobCount = count($jobs);
                            $curBill->issuedAt = gmdate("Y-m-d\TH:i:s", $curtime);
                            // if($billInfo->billing_type == 1)
                            $curBill->issuedFrom = $dateFrom;
                            $curBill->issuedTo = $dateTo;
                            $curBill->state = 0;
                            $curBill->notExceeded = $notExceeded;
                            $curBill->exceeded = $exceeded;
                            $curBill->save();

                            if($billInfo->send_invoice == 0){ // Directly authorize and charge funds
                                $this->createInvoice(0, $curBill, $company, $billInfo, $curtime); // Unpaid invoice
                                $this->chargeCreditCard($curBill, $company, $billInfo, $amount, $curtime);
                            } else if($billInfo->send_invoice == 1) { // Send unpaid invoice first
                                $this->createInvoice(0, $curBill, $company, $billInfo, $curtime); // Unpaid invoice
                                $this->sendBillMail(0, $curBill, $company, $billInfo, '');
                            }
                        }
                    }
                }
            }
            return response()->json(["success" => true]);
        } else
            return response()->json(["success" => false, "message" => "Empty company ids."]);
    }
}
