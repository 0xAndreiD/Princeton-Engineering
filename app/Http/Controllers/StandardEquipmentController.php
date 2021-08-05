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
        $this->middleware('twofactor');
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
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1 || Auth::user()->userrole == 3){
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
        if(Auth::user()->userrole == 2){
            $columns = array( 
                0 =>'id', 
                1 =>'id', 
                2 =>'mfr',
                3 =>'model'
            );
        } else {
            $columns = array( 
                0 =>'id', 
                1 =>'mfr',
                2 =>'model'
            );
        }
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
                if(Auth::user()->userrole == 2)
                    $module['bulkcheck'] = "
                        <div class='text-center'>
                            <input type='checkbox' id='bulkcheck_{$module['id']}' class='bulkcheck' style='cursor: pointer;'>
                        </div>";
                $module['actions'] = "
                <div class='text-center'>
                    <button type='button' class='btn' onclick='toggleFavourite(this,{$module['id']})'>
                        " . (in_array(strval($module->crc32), $favorites) ? "<i class='fa fa-star'></i>" : "<i class='far fa-star'></i>") . 
                    "</button>" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='btn btn-primary' onclick='showEditModule(this,{$module['id']})'><i class='fa fa-pencil-alt'></i></button>" . "<button type='button' class='js-swal-confirm btn btn-danger' style='margin-left:5px;' onclick='delModule(this,{$module['id']})'><i class='fa fa-trash'></i></button>" : "")
                    . "</div>";
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
     * Generate Random String.
     *
     * @return String
     */
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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
                
                $base_str = $module['mfr'] . $module['model'];
                do{
                    $tmp = unpack("l", pack("l", crc32($base_str)));
                    $crc32 = reset($tmp);
                    $check = PVModule::where('id', '!=', $module->id)->where('crc32', $crc32)->first();
                    $base_str .= $this->generateRandomString();
                }while($check);

                $module->crc32 = $crc32;
                $module->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];

                $base_str = $data['mfr'] . $data['model'];
                do{
                    $tmp = unpack("l", pack("l", crc32($base_str)));
                    $crc32 = reset($tmp);
                    $check = PVModule::where('crc32', $crc32)->first();
                    $base_str .= $this->generateRandomString();
                }while($check);
                
                $data['crc32'] = $crc32;
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
     * Copy standard modules.
     *
     * @return JSON
     */
    public function copyModules(Request $request){
        if(!empty($request['ids']) && count($request['ids']) > 0){
            foreach($request['ids'] as $id){
                $module = PVModule::where('id', $id)->first()->replicate();
                if($module){
                    $module['model'] = $module['model'] . "_copy";
                    $base_str = $module['mfr'] . $module['model'];
                    do{
                        $tmp = unpack("l", pack("l", crc32($base_str)));
                        $crc32 = reset($tmp);
                        $check = PVModule::where('crc32', $crc32)->first();
                        $base_str .= $this->generateRandomString();
                    }while($check);
                    
                    $module['crc32'] = $crc32;
                    $module->save();
                }
            }
            return response()->json(['success' => true]);
        } else 
            return response()->json(['success' => false, 'message' => 'Nothing to copy.']);
    }

    /**
     * Delete standard modules.
     *
     * @return JSON
     */
    public function delModules(Request $request){
        if(!empty($request['ids']) && count($request['ids']) > 0){
            foreach($request['ids'] as $id){
                $module = PVModule::where('id', $id)->first();
                if($module)
                    $module->delete();    
            }
            return response()->json(['success' => true]);
        } else 
            return response()->json(['success' => false, 'message' => 'Nothing to copy.']);
    }

    /**
     * Show the standard inverters page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function standardInverter()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1 || Auth::user()->userrole == 3){
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
        if(Auth::user()->userrole == 2){
            $columns = array( 
                0 =>'id', 
                1 =>'id', 
                2 =>'mfr',
                3 =>'model',
            );
        } else {
            $columns = array( 
                0 =>'id', 
                1 =>'mfr',
                2 =>'model',
            );
        }
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
                if(Auth::user()->userrole == 2)
                    $inverter['bulkcheck'] = "
                        <div class='text-center'>
                            <input type='checkbox' id='bulkcheck_{$inverter['id']}' class='bulkcheck' style='cursor: pointer;'>
                        </div>";
                $inverter['actions'] = "
                <div class='text-center'>" . 
                    "<button type='button' class='btn' onclick='toggleFavourite(this,{$inverter['id']})'>" . (in_array(strval($inverter->crc32), $favorites) ? "<i class='fa fa-star'></i>" : "<i class='far fa-star'></i>") . "</button>" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='btn btn-primary' onclick='showEditInverter(this,{$inverter['id']})'><i class='fa fa-pencil-alt'></i></button>" . "<button type='button' class='js-swal-confirm btn btn-danger' style='margin-left:5px;' onclick='delInverter(this,{$inverter['id']})'><i class='fa fa-trash'></i></button>" : "")
                . "</div>";
                
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

                $base_str = $inverter['module'] . $inverter['submodule'];
                do{
                    $tmp = unpack("l", pack("l", crc32($base_str)));
                    $crc32 = reset($tmp);
                    $check = PVInverter::where('id', '!=', $inverter->id)->where('crc32', $crc32)->first();
                    $base_str .= $this->generateRandomString();
                }while($check);

                $inverter['crc32'] = $crc32;
                $inverter->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];

                $base_str = $data['module'] . $data['submodule'];
                do{
                    $tmp = unpack("l", pack("l", crc32($base_str)));
                    $crc32 = reset($tmp);
                    $check = PVInverter::where('crc32', $crc32)->first();
                    $base_str .= $this->generateRandomString();
                }while($check);

                $data['crc32'] = $crc32;
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
     * Copy standard inverters.
     *
     * @return JSON
     */
    public function copyInverters(Request $request){
        if(!empty($request['ids']) && count($request['ids']) > 0){
            foreach($request['ids'] as $id){
                $inverter = PVInverter::where('id', $id)->first()->replicate();
                if($inverter){
                    $inverter['submodule'] = $inverter['submodule'] . "_copy";
                    $base_str = $inverter['mfr'] . $inverter['model'];
                    do{
                        $tmp = unpack("l", pack("l", crc32($base_str)));
                        $crc32 = reset($tmp);
                        $check = PVInverter::where('crc32', $crc32)->first();
                        $base_str .= $this->generateRandomString();
                    }while($check);
                    
                    $inverter['crc32'] = $crc32;
                    $inverter->save();
                }
            }
            return response()->json(['success' => true]);
        } else 
            return response()->json(['success' => false, 'message' => 'Nothing to copy.']);
    }

    /**
     * Delete standard inverters.
     *
     * @return JSON
     */
    public function delInverters(Request $request){
        if(!empty($request['ids']) && count($request['ids']) > 0){
            foreach($request['ids'] as $id){
                $inverter = PVInverter::where('id', $id)->first();
                if($inverter)
                    $inverter->delete();    
            }
            return response()->json(['success' => true]);
        } else 
            return response()->json(['success' => false, 'message' => 'Nothing to copy.']);
    }
    
    /**
     * Show the standard solar racking page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function standardRacking()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1 || Auth::user()->userrole == 3){
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
        if(Auth::user()->userrole == 2){
            $columns = array( 
                0 =>'id',
                1 =>'id', 
                2 =>'module',
                3 =>'submodule',
            );
        } else {
            $columns = array( 
                0 =>'id', 
                1 =>'module',
                2 =>'submodule',
            );
        }
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
                if(Auth::user()->userrole == 2)
                    $racking['bulkcheck'] = "
                        <div class='text-center'>
                            <input type='checkbox' id='bulkcheck_{$racking['id']}' class='bulkcheck' style='cursor: pointer;'>
                        </div>";
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

                $base_str = $racking['module'] . $racking['submodule'];
                do{
                    $tmp = unpack("l", pack("l", crc32($base_str)));
                    $crc32 = reset($tmp);
                    $check = RailSupport::where('id', '!=', $racking->id)->where('crc32', $crc32)->first();
                    $base_str .= $this->generateRandomString();
                }while($check);

                $racking['crc32'] = $crc32;             
                $racking->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                
                $base_str = $data['module'] . $data['submodule'];
                do{
                    $tmp = unpack("l", pack("l", crc32($base_str)));
                    $crc32 = reset($tmp);
                    $check = RailSupport::where('crc32', $crc32)->first();
                    $base_str .= $this->generateRandomString();
                }while($check);

                $data['crc32'] = $crc32;
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
     * Copy standard rackings.
     *
     * @return JSON
     */
    public function copyRackings(Request $request){
        if(!empty($request['ids']) && count($request['ids']) > 0){
            foreach($request['ids'] as $id){
                $racking = RailSupport::where('id', $id)->first()->replicate();
                if($racking){
                    $racking['submodule'] = $racking['submodule'] . "_copy";
                    $base_str = $racking['mfr'] . $racking['model'];
                    do{
                        $tmp = unpack("l", pack("l", crc32($base_str)));
                        $crc32 = reset($tmp);
                        $check = RailSupport::where('crc32', $crc32)->first();
                        $base_str .= $this->generateRandomString();
                    }while($check);
                    
                    $racking['crc32'] = $crc32;
                    $racking->save();
                }
            }
            return response()->json(['success' => true]);
        } else 
            return response()->json(['success' => false, 'message' => 'Nothing to copy.']);
    }

    /**
     * Delete standard rackings.
     *
     * @return JSON
     */
    public function delRackings(Request $request){
        if(!empty($request['ids']) && count($request['ids']) > 0){
            foreach($request['ids'] as $id){
                $racking = RailSupport::where('id', $id)->first();
                if($racking)
                    $racking->delete();    
            }
            return response()->json(['success' => true]);
        } else 
            return response()->json(['success' => false, 'message' => 'Nothing to copy.']);
    }

    /**
     * Show the standard stanchions page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function standardStanchion()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1 || Auth::user()->userrole == 3){
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
        if(Auth::user()->userrole == 2){
            $columns = array( 
                0 =>'id', 
                1 =>'id', 
                2 =>'module',
                3 =>'submodule',
            );
        } else {
            $columns = array( 
                0 =>'id', 
                1 =>'module',
                2 =>'submodule',
            );
        }
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
                if(Auth::user()->userrole == 2)
                    $stanchion['bulkcheck'] = "
                        <div class='text-center'>
                            <input type='checkbox' id='bulkcheck_{$stanchion['id']}' class='bulkcheck' style='cursor: pointer;'>
                        </div>";
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

                $base_str = $stanchion['module'] . $stanchion['submodule'];
                do{
                    $tmp = unpack("l", pack("l", crc32($base_str)));
                    $crc32 = reset($tmp);
                    $check = Stanchion::where('id', '!=', $stanchion->id)->where('crc32', $crc32)->first();
                    $base_str .= $this->generateRandomString();
                }while($check);

                $stanchion['crc32'] = $crc32;
                $stanchion->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];

                $base_str = $data['module'] . $data['submodule'];
                do{
                    $tmp = unpack("l", pack("l", crc32($base_str)));
                    $crc32 = reset($tmp);
                    $check = Stanchion::where('crc32', $crc32)->first();
                    $base_str .= $this->generateRandomString();
                }while($check);

                $data['crc32'] = $crc32;
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

    /**
     * Copy standard stanchions.
     *
     * @return JSON
     */
    public function copyStanchions(Request $request){
        if(!empty($request['ids']) && count($request['ids']) > 0){
            foreach($request['ids'] as $id){
                $stanchion = Stanchion::where('id', $id)->first()->replicate();
                if($stanchion){
                    $stanchion['submodule'] = $stanchion['submodule'] . "_copy";
                    $base_str = $stanchion['mfr'] . $stanchion['model'];
                    do{
                        $tmp = unpack("l", pack("l", crc32($base_str)));
                        $crc32 = reset($tmp);
                        $check = Stanchion::where('crc32', $crc32)->first();
                        $base_str .= $this->generateRandomString();
                    }while($check);
                    
                    $stanchion['crc32'] = $crc32;
                    $stanchion->save();
                }
            }
            return response()->json(['success' => true]);
        } else 
            return response()->json(['success' => false, 'message' => 'Nothing to copy.']);
    }

    /**
     * Delete standard stanchions.
     *
     * @return JSON
     */
    public function delStanchions(Request $request){
        if(!empty($request['ids']) && count($request['ids']) > 0){
            foreach($request['ids'] as $id){
                $stanchion = Stanchion::where('id', $id)->first();
                if($stanchion)
                    $stanchion->delete();    
            }
            return response()->json(['success' => true]);
        } else 
            return response()->json(['success' => false, 'message' => 'Nothing to copy.']);
    }
}
