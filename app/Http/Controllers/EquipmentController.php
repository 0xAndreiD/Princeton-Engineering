<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\User;
use App\Company;
use App\CustomModule;

class EquipmentController extends Controller
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
        return redirect('home');
    }

    /**
     * Show the custom modules page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customModule()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1){
            return view('equipment.module.list');
        }
        else
            return redirect('home');
    }

    /**
     * Return the list of custom modules.
     *
     * @return JSON
     */
    public function getCustomModules(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'mfr',
            2 =>'model',
            3 =>'rating',
            4 =>'length',
            5 =>'width',
            6 =>'depth',
            7 =>'Mtg_Hole_1',
            8 =>'url'
        );
        
        $handler = CustomModule::where('client_no', Auth::user()->companyid);
        
        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $modules = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $modules =  $handler->where('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->orWhere('rating', 'LIKE',"%{$search}%")
                        ->orWhere('length', 'LIKE',"%{$search}%")
                        ->orWhere('width', 'LIKE',"%{$search}%")
                        ->orWhere('depth', 'LIKE',"%{$search}%")
                        ->orWhere('Mtg_Hole_1', 'LIKE',"%{$search}%")
                        ->orWhere('url', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();

            $totalFiltered = $handler->where('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->orWhere('rating', 'LIKE',"%{$search}%")
                        ->orWhere('length', 'LIKE',"%{$search}%")
                        ->orWhere('width', 'LIKE',"%{$search}%")
                        ->orWhere('depth', 'LIKE',"%{$search}%")
                        ->orWhere('Mtg_Hole_1', 'LIKE',"%{$search}%")
                        ->orWhere('url', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($modules))
        {
            $id = 1;
            foreach ($modules as $module)
            {
                $module['actions'] = "
                <div class='text-center'>
                    <button type='button' class='btn' onclick='toggleFavourite(this,{$module['id']})'>
                        " . ($module->favorite ? "<i class='fa fa-star'></i>" : "<i class='far fa-star'></i>") . 
                    "</button>
                    <button type='button' class='btn btn-primary' 
                        onclick='showEditModule(this,{$module['id']})'>
                        <i class='fa fa-pencil-alt'></i>
                    </button>
                    <button type='button' class='js-swal-confirm btn btn-danger' onclick='delModule(this,{$module['id']})'>
                        <i class='fa fa-trash'></i>
                    </button>
                    
                </div>";
                $module['id'] = $id; $id ++;
                $data[] = $module;
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
     * Create / Update custom module data.
     *
     * @return JSON
     */
    public function submitModule(Request $request){
        if(!empty($request['moduleId']) && !empty($request['data'])){
            $module = CustomModule::where('id', $request['moduleId'])->first();
            if($module){
                if($module->client_no != Auth::user()->companyid)
                    return response()->json(['success' => false, 'message' => 'Company Id Mismatch.']);
                
                foreach($request['data'] as $fieldKey => $value)
                    $module[$fieldKey] = $value;
                
                $module->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $data['client_no'] = Auth::user()->companyid;
                $newModule = CustomModule::create($data);
                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty moduleId or data.']);
        }
    }

    /**
     * Delete custom module data.
     *
     * @return JSON
     */
    public function deleteModule(Request $request){
        if(!empty($request['moduleId'])){
            $module = CustomModule::where('id', $request['moduleId'])->first();
            if($module){
                $module->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find module.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty moduleId.']);
        }
    }

    /**
     * Delete custom module data.
     *
     * @return JSON
     */
    public function moduleToggleFavorite(Request $request){
        if(!empty($request['moduleId'])){
            $module = CustomModule::where('id', $request['moduleId'])->first();
            if($module){
                $module->favorite = !$module->favorite;
                $module->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find module.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty moduleId.']);
        }
    }
}
