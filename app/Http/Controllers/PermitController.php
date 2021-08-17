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
use App\PermitFiles;
use App\PermitFields;

class PermitController extends Controller
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
        $pdfList = PermitFiles::orderBy('id', 'asc')->get();
        return view('admin.permit.list')->with('pdfList', $pdfList);
    }

    /**
     * Return the list of permit files.
     *
     * @return JSON
     */
    public function getPermitFiles(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'filename',
            2 =>'state',
            3 =>'description',
            4 =>'tabname'
        );

        $handler = new PermitFiles;

        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        $handler = $handler->where('description', 'LIKE', "%{$request->input("columns.2.search.value")}%");

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $totalFiltered = $handler->count();
            $files = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array(
                        'id', 'filename', 'state', 'description', 'tabname'
                    )
                );
        }
        else {
            $search = $request->input('search.value'); 
            $files =  $handler->where('description', 'LIKE',"%{$search}%")
                        ->orWhere('tabname', 'LIKE',"%{$search}%")
                        ->orWhere('filename', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array(
                                'id', 'filename', 'state', 'description', 'tabname'
                            )
                        );

            $totalFiltered = $handler->where('description', 'LIKE',"%{$search}%")
                        ->orWhere('tabname', 'LIKE',"%{$search}%")
                        ->orWhere('filename', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($files))
        {
            $id = 1;
            foreach ($files as $file)
            {
                $file['actions'] = "
                <div class='text-center'> 
                    <button type='button' class='btn btn-primary' onclick='showEditPermit(this,{$file['id']})'><i class='fa fa-pencil-alt'></i></button>
                    <button type='button' class='btn btn-primary' onclick='configPermit(this,{$file['id']})'><i class='fa fa-cogs'></i></button>
                    <button type='button' class='js-swal-confirm btn btn-danger' style='margin-left:5px;' onclick='delPermit(this,{$file['id']})'><i class='fa fa-trash'></i></button>
                </div>";

                $fields = PermitFields::where('filename', $file['filename'])->get();
                if(count($fields) > 0)
                    $file['configured'] = "<span class='badge badge-success'>Yes</span>";
                else 
                    $file['configured'] = "<span class='badge badge-danger'>No</span>";
                $file['id'] = $id; $id ++;
                $data[] = $file;
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
     * Create / Save Permit Config data.
     *
     * @return JSON
     */
    public function submitPermitConfig(Request $request){
        if(!empty($request->filename)){
            $data = $request->data;
            PermitFields::where('filename', $request->filename)->delete();
            
            foreach ($data as $item) {
                $field['filename'] = $request->filename;
                $field['pdffield'] = $item[1];
                if ($item[0] == 'on') $item[0] = 1; else $item[0] = 0;
                $field['pdfcheck'] = $item[0];
                $field['type'] = $item[2];
                $field['defaultvalue'] = $item[3];
                $field['htmlfield'] = $item[5];
                if ($item[4] == 'on') $item[4] = 1; else $item[4] = 0;
                $field['htmlcheck'] = $item[4];
                $field['section'] = $item[6];
                $field['label'] = $item[7];
                $field['dbinfo'] = $item[8];
                $field['options'] = $item[9];
                PermitFields::create($field);
            }
            return response()->json(['success' => true, 'status' => true]);
        } else {
            return response()->json(['success' => false, 'status' => false, 'message' => 'Empty filename.']);
        }
    }
    /**
     * Create / Update Permit data.
     *
     * @return JSON
     */
    public function submitPermit(Request $request){
        $request->validate([
            'file' => 'required|mimes:pdf|max:4096',
        ]);
        
        if(!empty($request->permitId)){
            $permit = PermitFiles::where('id', $request->permitId)->first();
            if($permit){
                $permit['filename'] = $request->filename;
                $permit['state'] = $request->state;
                $permit['description'] = $request->description;
                $permit['tabname'] = $request->tabname;
                $permit->save();
                return response()->json(['success' => true]);
            } else {
                $permit['filename'] = $request->filename;
                $permit['state'] = $request->state;
                $permit['description'] = $request->description;
                $permit['tabname'] = $request->tabname;
                
                $list = PermitFiles::where('filename', $request->filename)->where('state', $request->state)->first();

                if ($list) 
                    return response()->json(['success' => false, 'status' => false, 'message' => 'Already added this file to the state']);

                $list = PermitFiles::where('filename', $request->filename)->first(); //In the case of same name file is exist
                if ($list) {

                    return response()->json(['success' => true, 'status' => true, 'message' => 'Configuration is cloned with the same file that you already uploaded']);
                }

                // In the case of we should upload the new file
                $fileName = $request->filename;  
                $request->file->move(public_path('pdf'), $fileName);

                $newPermit = PermitFiles::create($permit);
                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['success' => false, 'status' => false, 'message' => 'Empty permitId or data.']);
        }
    }
    /**
     * Config Permit
     *
     * @return JSON
     */
    public function configPermit(Request $request) {
        $id = $request->id;
        $permit = PermitFiles::where('id', $id)->first(); //In the case of same name file is exist
        $filename = $permit->filename;
        $fields = PermitFields::where('filename', $filename)->get();
        return view('admin.permit.config')->with('permitId', $id)->with('filename', $filename)->with('fields', $fields);
    }   
    
    /**
     * Delete Permit
     *
     * @return JSON
     */
    function deletePermit(Request $request){
        if(!empty($request['permitId'])){
            $permit = PermitFiles::where('id', $request['permitId'])->first();
            if($permit){
                $permit->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find permit.']);
            }
        } else
            return response()->json(['success' => false, 'message' => 'Empty permitId.']);
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

}
