<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\User;
use App\Company;
use App\PVModule;
use App\PVInverter;
use App\RailSupport;
use App\Stanchion;
use App\StandardFavorite;

class StandardEquipmentController extends Controller
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
     * Show the standard modules page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function standardModule()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1){
            return view('standardequipment.module.list');
        }
        else
            return redirect('home');
    }

    /**
     * Return the list of standard modules.
     *
     * @return JSON
     */
    public function getStandardModules(Request $request){
        $columns = array( 
            0 =>'id', 
            2 =>'mfr',
            3 =>'model'
        );
        $handler = new PVModule;

        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        if(Auth::user()->userrole == 2 && !empty($request->input("columns.1.search.value")))
            $handler = $handler->where('mfr', 'LIKE', "%{$request->input("columns.1.search.value")}%");

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $totalFiltered = $handler->count();
            $modules = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array(
                        'id', 'mfr', 'model', 'rating', 'length', 'width', 'depth', 'weight', 'crc32'
                    )
                );
        }
        else {
            $search = $request->input('search.value'); 
            $modules =  $handler->where('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array(
                                'id', 'mfr', 'model', 'rating', 'length', 'width', 'depth', 'weight', 'crc32'
                            )
                        );

            $totalFiltered = $handler->where('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($modules))
        {
            $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 0)->first();
            if(!$favorite)
                $favorite_ids = '';
            else
                $favorite_ids = $favorite->crc32_ids;
            $favorites = explode(",", $favorite_ids);
            $id = 1;
            foreach ($modules as $module)
            {
                $module['actions'] = "
                <div class='text-center'>
                    <button type='button' class='btn' onclick='toggleFavourite(this,{$module['id']})'>
                        " . (in_array(strval($module->crc32), $favorites) ? "<i class='fa fa-star'></i>" : "<i class='far fa-star'></i>") . 
                    "</button>" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='btn btn-primary' onclick='showEditModule(this,{$module['id']})'><i class='fa fa-pencil-alt'></i></button>" . "<button type='button' class='js-swal-confirm btn btn-danger' style='margin-left:5px;' onclick='delModule(this,{$module['id']})'><i class='fa fa-trash'></i></button>" : "")
                    . "</div>";
                $module['id'] = $id; $id ++;
                $module['favorite_ids'] = $favorites;
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
     * Create / Update standard module data.
     *
     * @return JSON
     */
    public function submitModule(Request $request){
        if(Auth::user()->userrole != 2)
            return response()->json(['success' => false, 'message' => 'You do not have any role.']);

        if(!empty($request['moduleId']) && !empty($request['data'])){
            $module = PVModule::where('id', $request['moduleId'])->first();
            if($module){
                foreach($request['data'] as $fieldKey => $value)
                    $module[$fieldKey] = $value;
                
                $tmp = unpack("l", pack("l", crc32($module['mfr'] . $module['model'])));
                $module['crc32'] = reset($tmp);
                $module->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $tmp = unpack("l", pack("l", crc32($data['mfr'] . $data['model'])));
                $data['crc32'] = reset($tmp);
                
                $newModule = PVModule::create($data);
                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty moduleId or data.']);
        }
    }

    /**
     * Delete standard module data.
     *
     * @return JSON
     */
    public function deleteModule(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['moduleId'])){
                $module = PVModule::where('id', $request['moduleId'])->first();
                if($module){
                    $module->delete();
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Cannot find module.']);
                }
            } else
                return response()->json(['success' => false, 'message' => 'Empty moduleId.']);
        } else
            return response()->json(['success' => false, 'message' => 'You do not have any role to delete this product.']);
    }

    /**
     * Set/Unset Favorite standard module data.
     *
     * @return JSON
     */
    public function moduleToggleFavorite(Request $request){
        if(!empty($request['moduleId'])){
            $module = PVModule::where('id', $request['moduleId'])->first();
            if($module){
                $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 0)->first();
                if(!$favorite){
                    StandardFavorite::create([
                        'client_no' => Auth::user()->companyid,
                        'type' => 0,
                        'crc32_ids' => $module->crc32
                    ]);
                } else {
                    $crc32_ids = explode(",", $favorite->crc32_ids);
                    if(in_array(strval($module->crc32), $crc32_ids))
                    {
                        $pos = array_search(strval($module->crc32), $crc32_ids);
                        unset($crc32_ids[$pos]);
                        $favorite->crc32_ids = implode(",", $crc32_ids);
                        $favorite->save();
                    } else {
                        $crc32_ids[] = $module->crc32;
                        $favorite->crc32_ids = implode(",", $crc32_ids);
                        $favorite->save();
                    }
                }
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find module.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty moduleId.']);
        }
    }

    /**
     * Show the standard inverters page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function standardInverter()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1){
            return view('standardequipment.inverter.list');
        }
        else
            return redirect('home');
    }

    /**
     * Return the list of standard inverters.
     *
     * @return JSON
     */
    public function getStandardInverters(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'mfr',
            2 =>'model',
        );
        $handler = new PVInverter;

        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        if(Auth::user()->userrole == 2 && !empty($request->input("columns.1.search.value")))
            $handler = $handler->where('module', 'LIKE', "%{$request->input("columns.1.search.value")}%");

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $totalFiltered = $handler->count();
            $inverters = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array('id', 'module', 'submodule', 'option1', 'option2', 'crc32')
                );
        }
        else {
            $search = $request->input('search.value'); 
            $inverters =  $handler->where('module', 'LIKE',"%{$search}%")
                        ->orWhere('submodule', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array('id', 'module', 'submodule', 'option1', 'option2', 'crc32')
                        );

            $totalFiltered = $handler->where('module', 'LIKE',"%{$search}%")
                        ->orWhere('submodule', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($inverters))
        {
            $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 1)->first();
            if(!$favorite)
                $favorite_ids = '';
            else
                $favorite_ids = $favorite->crc32_ids;
            $favorites = explode(",", $favorite_ids);
            $id = 1;
            foreach ($inverters as $inverter)
            {
                $inverter['actions'] = "
                <div class='text-center'>" . 
                    "<button type='button' class='btn' onclick='toggleFavourite(this,{$inverter['id']})'>" . (in_array(strval($inverter->crc32), $favorites) ? "<i class='fa fa-star'></i>" : "<i class='far fa-star'></i>") . "</button>" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='btn btn-primary' onclick='showEditInverter(this,{$inverter['id']})'><i class='fa fa-pencil-alt'></i></button>" . "<button type='button' class='js-swal-confirm btn btn-danger' style='margin-left:5px;' onclick='delInverter(this,{$inverter['id']})'><i class='fa fa-trash'></i></button>" : "")
                . "</div>";
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
     * Create / Update standard inverter data.
     *
     * @return JSON
     */
    public function submitInverter(Request $request){
        if(Auth::user()->userrole != 2)
            return response()->json(['success' => false, 'message' => 'You do not have any role.']);

        if(!empty($request['inverterId']) && !empty($request['data'])){
            $inverter = PVInverter::where('id', $request['inverterId'])->first();
            if($inverter){
                foreach($request['data'] as $fieldKey => $value)
                    $inverter[$fieldKey] = $value;

                $tmp = unpack("l", pack("l", crc32($inverter['module'] . $inverter['submodule'])));
                $inverter['crc32'] = reset($tmp);
                $inverter->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $tmp = unpack("l", pack("l", crc32($data['module'] . $data['submodule'])));
                $data['crc32'] = reset($tmp);
                
                $newInverter = PVInverter::create($data);
                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty inverterId or data.']);
        }
    }

    /**
     * Delete standard inverter data.
     *
     * @return JSON
     */
    public function deleteInverter(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['inverterId'])){
                $inverter = PVInverter::where('id', $request['inverterId'])->first();
                if($inverter){
                    $inverter->delete();
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Cannot find inverter.']);
                }
            } else
                return response()->json(['success' => false, 'message' => 'Empty inverterId.']);
        } else
            return response()->json(['success' => false, 'message' => 'You do not have any role to delete this product.']);
    }

    /**
     * Set/Unset Favorite standard inverter data.
     *
     * @return JSON
     */
    public function inverterToggleFavorite(Request $request){
        if(!empty($request['inverterId'])){
            $inverter = PVInverter::where('id', $request['inverterId'])->first();
            if($inverter){
                $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 1)->first();
                if(!$favorite){
                    StandardFavorite::create([
                        'client_no' => Auth::user()->companyid,
                        'type' => 1,
                        'crc32_ids' => $inverter->crc32
                    ]);
                } else {
                    $crc32_ids = explode(",", $favorite->crc32_ids);
                    if(in_array(strval($inverter->crc32), $crc32_ids))
                    {
                        $pos = array_search(strval($inverter->crc32), $crc32_ids);
                        unset($crc32_ids[$pos]);
                        $favorite->crc32_ids = implode(",", $crc32_ids);
                        $favorite->save();
                    } else {
                        $crc32_ids[] = $inverter->crc32;
                        $favorite->crc32_ids = implode(",", $crc32_ids);
                        $favorite->save();
                    }
                }
                return response()->json(['success' => true]);
            } else
                return response()->json(['success' => false, 'message' => 'Cannot find inverter.']);
        } else
            return response()->json(['success' => false, 'message' => 'Empty inverterId.']);
    }
    
    /**
     * Show the standard solar racking page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function standardRacking()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1){
            return view('standardequipment.racking.list');
        }
        else
            return redirect('home');
    }

    /**
     * Return the list of standard solar rackings.
     *
     * @return JSON
     */
    public function getStandardRacking(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'module',
            2 =>'submodule',
        );
        $handler = new RailSupport;

        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        if(Auth::user()->userrole == 2 && !empty($request->input("columns.1.search.value")))
            $handler = $handler->where('module', 'LIKE', "%{$request->input("columns.1.search.value")}%");

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $totalFiltered = $handler->count();
            $rackings = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array('id', 'module', 'submodule', 'option1', 'option2', 'crc32')
                );
        }
        else {
            $search = $request->input('search.value'); 
            $rackings =  $handler->where('module', 'LIKE',"%{$search}%")
                        ->orWhere('submodule', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array('id', 'module', 'submodule', 'option1', 'option2', 'crc32')
                        );

            $totalFiltered = $handler->where('module', 'LIKE',"%{$search}%")
                        ->orWhere('submodule', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($rackings))
        {
            $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 2)->first();
            if(!$favorite)
                $favorite_ids = '';
            else
                $favorite_ids = $favorite->crc32_ids;
            $favorites = explode(",", $favorite_ids);
            $id = 1;
            foreach ($rackings as $racking)
            {
                $racking['actions'] = "
                <div class='text-center'>
                    <button type='button' class='btn' onclick='toggleFavourite(this,{$racking['id']})'>
                        " . (in_array(strval($racking->crc32), $favorites) ? "<i class='fa fa-star'></i>" : "<i class='far fa-star'></i>") . 
                    "</button>" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='btn btn-primary' 
                        onclick='showEditRacking(this,{$racking['id']})'>
                        <i class='fa fa-pencil-alt'></i>
                    </button>" . 
                     "<button type='button' class='js-swal-confirm btn btn-danger' style='margin-left: 5px;' onclick='delRacking(this,{$racking['id']})'>
                        <i class='fa fa-trash'></i>
                    </button>" : "")
                    
                . "</div>";
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
     * Create / Update standard solar racking data.
     *
     * @return JSON
     */
    public function submitRacking(Request $request){
        if(Auth::user()->userrole != 2)
            return response()->json(['success' => false, 'message' => 'You do not have any role.']);

        if(!empty($request['rackingId']) && !empty($request['data'])){
            $racking = RailSupport::where('id', $request['rackingId'])->first();
            if($racking){
                foreach($request['data'] as $fieldKey => $value)
                    $racking[$fieldKey] = $value;
                
                $tmp = unpack("l", pack("l", crc32($racking['module'] . $racking['submodule'])));
                $racking['crc32'] = reset($tmp);                
                $racking->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $tmp = unpack("l", pack("l", crc32($data['module'] . $data['submodule'])));
                $data['crc32'] = reset($tmp);

                $newRacking = RailSupport::create($data);
                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty rackingId or data.']);
        }
    }

    /**
     * Delete standard solar racking data.
     *
     * @return JSON
     */
    public function deleteRacking(Request $request){
        if(Auth::user()->userrole == 2){
            if(!empty($request['rackingId'])){
                $racking = RailSupport::where('id', $request['rackingId'])->first();
                if($racking){
                    $racking->delete();
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Cannot find racking.']);
                }
            } else
                return response()->json(['success' => false, 'message' => 'Empty rackingId.']);
        } else
            return response()->json(['success' => false, 'message' => 'You do not have any role to delete this product.']);
    }

    /**
     * Delete standard solar racking data.
     *
     * @return JSON
     */
    public function rackingToggleFavorite(Request $request){
        if(!empty($request['rackingId'])){
            $racking = RailSupport::where('id', $request['rackingId'])->first();
            if($racking){
                $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 2)->first();
                if(!$favorite){
                    StandardFavorite::create([
                        'client_no' => Auth::user()->companyid,
                        'type' => 2,
                        'crc32_ids' => $racking->crc32
                    ]);
                } else {
                    $crc32_ids = explode(",", $favorite->crc32_ids);
                    if(in_array(strval($racking->crc32), $crc32_ids))
                    {
                        $pos = array_search(strval($racking->crc32), $crc32_ids);
                        unset($crc32_ids[$pos]);
                        $favorite->crc32_ids = implode(",", $crc32_ids);
                        $favorite->save();
                    } else {
                        $crc32_ids[] = $racking->crc32;
                        $favorite->crc32_ids = implode(",", $crc32_ids);
                        $favorite->save();
                    }
                }
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find racking.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty rackingId.']);
        }
    }

    /**
     * Show the standard stanchions page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function standardStanchion()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1){
            return view('standardequipment.stanchion.list');
        }
        else
            return redirect('home');
    }

    /**
     * Return the list of standard stanchions.
     *
     * @return JSON
     */
    public function getStandardStanchion(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'module',
            2 =>'submodule',
        );
        $handler = new Stanchion;
        
        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        if(Auth::user()->userrole == 2 && !empty($request->input("columns.1.search.value")))
            $handler = $handler->where('module', 'LIKE', "%{$request->input("columns.1.search.value")}%");

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $totalFiltered = $handler->count();
            $stanchions = $handler->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array('id', 'module', 'submodule', 'option1', 'option2', 'crc32')
                );
        }
        else {
            $search = $request->input('search.value'); 
            $stanchions =  $handler->where('module', 'LIKE',"%{$search}%")
                        ->orWhere('submodule', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array('id', 'module', 'submodule', 'option1', 'option2', 'crc32')
                        );

            $totalFiltered = $handler->where('module', 'LIKE',"%{$search}%")
                        ->orWhere('submodule', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($stanchions))
        {
            $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 3)->first();
            if(!$favorite)
                $favorite_ids = '';
            else
                $favorite_ids = $favorite->crc32_ids;
            $favorites = explode(",", $favorite_ids);
            $id = 1;
            foreach ($stanchions as $stanchion)
            {
                $stanchion['actions'] = "
                <div class='text-center'>
                    <button type='button' class='btn' onclick='toggleFavourite(this,{$stanchion['id']})'>
                        " . (in_array(strval($stanchion->crc32), $favorites) ? "<i class='fa fa-star'></i>" : "<i class='far fa-star'></i>") . 
                    "</button>" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='btn btn-primary' 
                        onclick='showEditStanchion(this,{$stanchion['id']})'>
                        <i class='fa fa-pencil-alt'></i>
                    </button>" . 
                    "<button type='button' class='js-swal-confirm btn btn-danger' style='margin-left: 5px;' onclick='delStanchion(this,{$stanchion['id']})'>
                        <i class='fa fa-trash'></i>
                    </button>" : "")
                    
                . "</div>";
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
     * Create / Update standard stanchion data.
     *
     * @return JSON
     */
    public function submitStanchion(Request $request){
        if(Auth::user()->userrole != 2)
            return response()->json(['success' => false, 'message' => 'You do not have any role.']);

        if(!empty($request['stanchionId']) && !empty($request['data'])){
            $stanchion = Stanchion::where('id', $request['stanchionId'])->first();
            if($stanchion){
                foreach($request['data'] as $fieldKey => $value)
                    $stanchion[$fieldKey] = $value;

                $tmp = unpack("l", pack("l", crc32($stanchion['module'] . $stanchion['submodule'])));
                $stanchion['crc32'] = reset($tmp);
                $stanchion->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $tmp = unpack("l", pack("l", crc32($data['module'] . $data['submodule'])));
                $data['crc32'] = reset($tmp);
                
                $newStanchion = Stanchion::create($data);
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
        if(Auth::user()->userrole == 2){
            if(!empty($request['stanchionId'])){
                $stanchion = Stanchion::where('id', $request['stanchionId'])->first();
                if($stanchion){
                    $stanchion->delete();
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Cannot find stanchion.']);
                }
            } else
                return response()->json(['success' => false, 'message' => 'Empty stanchionId.']);
        } else
            return response()->json(['success' => false, 'message' => 'You do not have any role to delete this product.']);
        
    }

    /**
     * Delete custom stanchion data.
     *
     * @return JSON
     */
    public function stanchionToggleFavorite(Request $request){
        if(!empty($request['stanchionId'])){
            $stanchion = Stanchion::where('id', $request['stanchionId'])->first();
            if($stanchion){
                $favorite = StandardFavorite::where('client_no', Auth::user()->companyid)->where('type', 3)->first();
                if(!$favorite){
                    StandardFavorite::create([
                        'client_no' => Auth::user()->companyid,
                        'type' => 3,
                        'crc32_ids' => $stanchion->crc32
                    ]);
                } else {
                    $crc32_ids = explode(",", $favorite->crc32_ids);
                    if(in_array(strval($stanchion->crc32), $crc32_ids))
                    {
                        $pos = array_search(strval($stanchion->crc32), $crc32_ids);
                        unset($crc32_ids[$pos]);
                        $favorite->crc32_ids = implode(",", $crc32_ids);
                        $favorite->save();
                    } else {
                        $crc32_ids[] = $stanchion->crc32;
                        $favorite->crc32_ids = implode(",", $crc32_ids);
                        $favorite->save();
                    }
                }
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cannot find stanchion.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Empty stanchionId.']);
        }
    }
}
