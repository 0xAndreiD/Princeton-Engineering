<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\User;
use App\Company;
use App\CustomModule;
use App\CustomInverter;
use App\CustomRacking;
use App\CustomStanchion;

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

    /**
     * Show the custom inverters page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customInverter()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1){
            return view('equipment.inverter.list');
        }
        else
            return redirect('home');
    }

    /**
     * Return the list of custom inverters.
     *
     * @return JSON
     */
    public function getCustomInverters(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'mfr',
            2 =>'model',
            3 =>'rating',
        );
        
        $handler = CustomInverter::where('client_no', Auth::user()->companyid);
        
        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $inverters = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $inverters =  $handler->where('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->orWhere('rating', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();

            $totalFiltered = $handler->where('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->orWhere('rating', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($inverters))
        {
            $id = 1;
            foreach ($inverters as $inverter)
            {
                $inverter['actions'] = "
                <div class='text-center'>
                    <button type='button' class='btn' onclick='toggleFavourite(this,{$inverter['id']})'>
                        " . ($inverter->favorite ? "<i class='fa fa-star'></i>" : "<i class='far fa-star'></i>") . 
                    "</button>
                    <button type='button' class='btn btn-primary' 
                        onclick='showEditInverter(this,{$inverter['id']})'>
                        <i class='fa fa-pencil-alt'></i>
                    </button>
                    <button type='button' class='js-swal-confirm btn btn-danger' onclick='delInverter(this,{$inverter['id']})'>
                        <i class='fa fa-trash'></i>
                    </button>
                    
                </div>";
                $inverter['id'] = $id; $id ++;
                $data[] = $inverter;
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
     * Create / Update custom inverter data.
     *
     * @return JSON
     */
    public function submitInverter(Request $request){
        if(!empty($request['inverterId']) && !empty($request['data'])){
            $inverter = CustomInverter::where('id', $request['inverterId'])->first();
            if($inverter){
                if($inverter->client_no != Auth::user()->companyid)
                    return response()->json(['success' => false, 'message' => 'Company Id Mismatch.']);
                
                foreach($request['data'] as $fieldKey => $value)
                    $inverter[$fieldKey] = $value;
                
                $inverter->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $data['client_no'] = Auth::user()->companyid;
                $newInverter = CustomInverter::create($data);
                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty inverterId or data.']);
        }
    }

    /**
     * Delete custom inverter data.
     *
     * @return JSON
     */
    public function deleteInverter(Request $request){
        if(!empty($request['inverterId'])){
            $inverter = CustomInverter::where('id', $request['inverterId'])->first();
            if($inverter){
                $inverter->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find inverter.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty inverterId.']);
        }
    }

    /**
     * Delete custom inverter data.
     *
     * @return JSON
     */
    public function inverterToggleFavorite(Request $request){
        if(!empty($request['inverterId'])){
            $inverter = CustomInverter::where('id', $request['inverterId'])->first();
            if($inverter){
                $inverter->favorite = !$inverter->favorite;
                $inverter->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find inverter.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty inverterId.']);
        }
    }
    
    /**
     * Show the custom solar racking page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customRacking()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1){
            return view('equipment.racking.list');
        }
        else
            return redirect('home');
    }

    /**
     * Return the list of custom solar rackings.
     *
     * @return JSON
     */
    public function getCustomRacking(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'mfr',
            2 =>'model',
            3 =>'rating',
        );
        
        $handler = CustomRacking::where('client_no', Auth::user()->companyid);
        
        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $rackings = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $rackings =  $handler->where('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->orWhere('style', 'LIKE',"%{$search}%")
                        ->orWhere('angle', 'LIKE',"%{$search}%")
                        ->orWhere('rack_weight', 'LIKE',"%{$search}%")
                        ->orWhere('width', 'LIKE',"%{$search}%")
                        ->orWhere('depth', 'LIKE',"%{$search}%")
                        ->orWhere('lowest_height', 'LIKE',"%{$search}%")
                        ->orWhere('module_spacing_EW', 'LIKE',"%{$search}%")
                        ->orWhere('module_spacing_NS', 'LIKE',"%{$search}%")
                        ->orWhere('url', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();

            $totalFiltered = $handler->where('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->orWhere('style', 'LIKE',"%{$search}%")
                        ->orWhere('angle', 'LIKE',"%{$search}%")
                        ->orWhere('rack_weight', 'LIKE',"%{$search}%")
                        ->orWhere('width', 'LIKE',"%{$search}%")
                        ->orWhere('depth', 'LIKE',"%{$search}%")
                        ->orWhere('lowest_height', 'LIKE',"%{$search}%")
                        ->orWhere('module_spacing_EW', 'LIKE',"%{$search}%")
                        ->orWhere('module_spacing_NS', 'LIKE',"%{$search}%")
                        ->orWhere('url', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($rackings))
        {
            $id = 1;
            foreach ($rackings as $racking)
            {
                $racking['actions'] = "
                <div class='text-center'>
                    <button type='button' class='btn' onclick='toggleFavourite(this,{$racking['id']})'>
                        " . ($racking->favorite ? "<i class='fa fa-star'></i>" : "<i class='far fa-star'></i>") . 
                    "</button>
                    <button type='button' class='btn btn-primary' 
                        onclick='showEditRacking(this,{$racking['id']})'>
                        <i class='fa fa-pencil-alt'></i>
                    </button>
                    <button type='button' class='js-swal-confirm btn btn-danger' onclick='delRacking(this,{$racking['id']})'>
                        <i class='fa fa-trash'></i>
                    </button>
                    
                </div>";
                $racking['id'] = $id; $id ++;
                $data[] = $racking;
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
     * Create / Update custom solar racking data.
     *
     * @return JSON
     */
    public function submitRacking(Request $request){
        if(!empty($request['rackingId']) && !empty($request['data'])){
            $racking = CustomRacking::where('id', $request['rackingId'])->first();
            if($racking){
                if($racking->client_no != Auth::user()->companyid)
                    return response()->json(['success' => false, 'message' => 'Company Id Mismatch.']);
                
                foreach($request['data'] as $fieldKey => $value)
                    $racking[$fieldKey] = $value;
                
                $racking->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $data['client_no'] = Auth::user()->companyid;
                $newRacking = CustomRacking::create($data);
                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty rackingId or data.']);
        }
    }

    /**
     * Delete custom solar racking data.
     *
     * @return JSON
     */
    public function deleteRacking(Request $request){
        if(!empty($request['rackingId'])){
            $racking = CustomRacking::where('id', $request['rackingId'])->first();
            if($racking){
                $racking->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find racking.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty rackingId.']);
        }
    }

    /**
     * Delete custom solar racking data.
     *
     * @return JSON
     */
    public function rackingToggleFavorite(Request $request){
        if(!empty($request['rackingId'])){
            $racking = CustomRacking::where('id', $request['rackingId'])->first();
            if($racking){
                $racking->favorite = !$racking->favorite;
                $racking->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find racking.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty rackingId.']);
        }
    }

    /**
     * Show the custom stanchions page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customStanchion()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1){
            return view('equipment.stanchion.list');
        }
        else
            return redirect('home');
    }

    /**
     * Return the list of custom stanchions.
     *
     * @return JSON
     */
    public function getCustomStanchion(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'mfr',
            2 =>'model',
            3 =>'rating',
        );
        
        $handler = CustomStanchion::where('client_no', Auth::user()->companyid);
        
        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $stanchions = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $stanchions =  $handler->where('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->orWhere('pullout', 'LIKE',"%{$search}%")
                        ->orWhere('weight', 'LIKE',"%{$search}%")
                        ->orWhere('url', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();

            $totalFiltered = $handler->where('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->orWhere('pullout', 'LIKE',"%{$search}%")
                        ->orWhere('weight', 'LIKE',"%{$search}%")
                        ->orWhere('url', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($stanchions))
        {
            $id = 1;
            foreach ($stanchions as $stanchion)
            {
                $stanchion['actions'] = "
                <div class='text-center'>
                    <button type='button' class='btn' onclick='toggleFavourite(this,{$stanchion['id']})'>
                        " . ($stanchion->favorite ? "<i class='fa fa-star'></i>" : "<i class='far fa-star'></i>") . 
                    "</button>
                    <button type='button' class='btn btn-primary' 
                        onclick='showEditStanchion(this,{$stanchion['id']})'>
                        <i class='fa fa-pencil-alt'></i>
                    </button>
                    <button type='button' class='js-swal-confirm btn btn-danger' onclick='delStanchion(this,{$stanchion['id']})'>
                        <i class='fa fa-trash'></i>
                    </button>
                    
                </div>";
                $stanchion['id'] = $id; $id ++;
                $data[] = $stanchion;
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
     * Create / Update custom stanchion data.
     *
     * @return JSON
     */
    public function submitStanchion(Request $request){
        if(!empty($request['stanchionId']) && !empty($request['data'])){
            $stanchion = CustomStanchion::where('id', $request['stanchionId'])->first();
            if($stanchion){
                if($stanchion->client_no != Auth::user()->companyid)
                    return response()->json(['success' => false, 'message' => 'Company Id Mismatch.']);
                
                foreach($request['data'] as $fieldKey => $value)
                    $stanchion[$fieldKey] = $value;
                
                $stanchion->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $data['client_no'] = Auth::user()->companyid;
                $newStanchion = CustomStanchion::create($data);
                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty stanchionId or data.']);
        }
    }

    /**
     * Delete custom stanchion data.
     *
     * @return JSON
     */
    public function deleteStanchion(Request $request){
        if(!empty($request['stanchionId'])){
            $stanchion = CustomStanchion::where('id', $request['stanchionId'])->first();
            if($stanchion){
                $stanchion->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find stanchion.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty stanchionId.']);
        }
    }

    /**
     * Delete custom stanchion data.
     *
     * @return JSON
     */
    public function stanchionToggleFavorite(Request $request){
        if(!empty($request['stanchionId'])){
            $stanchion = CustomStanchion::where('id', $request['stanchionId'])->first();
            if($stanchion){
                $stanchion->favorite = !$stanchion->favorite;
                $stanchion->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find stanchion.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty stanchionId.']);
        }
    }
}
