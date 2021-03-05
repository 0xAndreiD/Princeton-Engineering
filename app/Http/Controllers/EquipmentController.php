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
            $companyList = Company::orderBy('company_name', 'asc')->get();
            return view('equipment.module.list')->with('companyList', $companyList);
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
        if(Auth::user()->userrole == 2){
            $columns = array( 
                0 =>'custom_module.id', 
                1 => 'company_info.company_name',
                2 =>'mfr',
                3 =>'model'
            );
            $handler = new CustomModule;
        } else {
            $columns = array( 
                0 =>'custom_module.id', 
                1 =>'mfr',
                2 =>'model'
            );
            $handler = CustomModule::where('client_no', Auth::user()->companyid);
        }

        $handler = $handler->leftjoin('company_info', "company_info.id", "=", "custom_module.client_no");

        if(Auth::user()->userrole == 2 && !empty($request->input("columns.1.search.value")))
            $handler = $handler->where('company_info.company_name', 'LIKE', "%{$request->input("columns.1.search.value")}%");
        
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
                ->get(
                    array(
                        'custom_module.id as id', 'company_info.company_name as companyname', 'mfr', 'model', 'rating', 'length', 'width', 'depth', 'weight', 'Voc', 'Vmp', 'Isc', 'Imp', 'Mtg_Hole_1', 'Mtg_Hole_2', 'lead_len', 'lead_guage', 'Vdc_max', 'Tmp_Factor_Pmax', 'Tmp_Factor_Voc', 'Tmp_Factor_Isc', 'Fuse_Size_max', 'efficiency', 'rev_date', 'product_literature', 'url', 'favorite'
                    )
                );
        }
        else {
            $search = $request->input('search.value'); 
            $modules =  $handler->where('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orwhere('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array(
                                'custom_module.id as id', 'company_info.company_name as companyname', 'mfr', 'model', 'rating', 'length', 'width', 'depth', 'weight', 'Voc', 'Vmp', 'Isc', 'Imp', 'Mtg_Hole_1', 'Mtg_Hole_2', 'lead_len', 'lead_guage', 'Vdc_max', 'Tmp_Factor_Pmax', 'Tmp_Factor_Voc', 'Tmp_Factor_Isc', 'Fuse_Size_max', 'efficiency', 'rev_date', 'product_literature', 'url', 'favorite'
                            )
                        );

            $totalFiltered = $handler->where('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
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
                    </button>" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='js-swal-confirm btn btn-danger' style='margin-left:5px;' onclick='delModule(this,{$module['id']})'><i class='fa fa-trash'></i></button>" : "")
                    . "</div>";
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
                
                $tmp = unpack("l", pack("l", crc32($module['mfr'] . $module['model'] . $module['Voc'] . $module['Isc'] . $module['Mtg_Hole_1'] . $module['lead_len'] . $module['client_no'])));
                $module['crc32'] = reset($tmp);
                $module->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $tmp = unpack("l", pack("l", crc32($data['mfr'] . $data['model'] . $data['Voc'] . $data['Isc'] . $data['Mtg_Hole_1'] . $data['lead_len'])));
                $data['crc32'] = reset($tmp);
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
        if(Auth::user()->userrole == 2){
            if(!empty($request['moduleId'])){
                $module = CustomModule::where('id', $request['moduleId'])->first();
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
            $companyList = Company::orderBy('company_name', 'asc')->get();
            return view('equipment.inverter.list')->with('companyList', $companyList);
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
        if(Auth::user()->userrole == 2){
            $columns = array( 
                0 =>'custom_inverter.id', 
                1 => 'company_info.company_name',
                2 =>'mfr',
                3 =>'model',
            );
            $handler = new CustomInverter;
        } else {
            $columns = array( 
                0 =>'custom_inverter.id', 
                1 =>'mfr',
                2 =>'model',
            );
            $handler = CustomInverter::where('client_no', Auth::user()->companyid);
        }

        $handler = $handler->leftjoin('company_info', "company_info.id", "=", "custom_inverter.client_no");

        if(Auth::user()->userrole == 2 && !empty($request->input("columns.1.search.value")))
            $handler = $handler->where('company_info.company_name', 'LIKE', "%{$request->input("columns.1.search.value")}%");
        
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
                ->get(
                    array('custom_inverter.id', 'company_info.company_name as companyname', 'mfr', 'model', 'rating', 'MPPT_Channels', 'kW_MPPT_max', 'Sys_Vol_max', 'Oper_DC_Vol_min', 'Oper_DC_Vol_max', 'Imp_max', 'Input_MPPT_max', 'Isc_max', 'Isc_MPPT_max', 'DC_Input_max', 'DC_Input_MPPT', 'DC_Wire_max', 'BiPolar', 'Rated_Out_Power', 'AC_Power_max', 'Rated_Out_Volt', 'AC_Low_Vol', 'AC_High_Vol', 'Out_Calc_max', 'Out_max', 'Inverter_Phasing', 'AC_Phases', 'AC_Wires', 'Neut_Ref_Vol', 'AC_max_Wires', 'AC_Wire_Size_max', 'Efficiency_max', 'CEC_Efficiency', 'Power_Factor_Lead', 'Power_Factor_Lag', 'Breaker_min', 'Breaker_max', 'Wire_Ins_Vol_min', 'Lug_Temp', 'xForm_VA_Multiplier', 'AC_Volt_Drop_max', 'Oper_Temp_min', 'Oper_Temp_max', 'Available_Fault', 'Install_Angle_Horiz_min', 'height', 'width', 'depth', 'weight', 'url', 'status', 'product_literature', 'cost', 'rev_date', 'DC_Start_Vol', 'MPPT2_Input_max', 'MPPT2_Short_Circuit_max', 'Input_kW_min', 'MPP_Vol_Low', 'MPP_Vol_High', 'favorite')
                );
        }
        else {
            $search = $request->input('search.value'); 
            $inverters =  $handler->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array('custom_inverter.id', 'company_info.company_name as companyname', 'mfr', 'model', 'rating', 'MPPT_Channels', 'kW_MPPT_max', 'Sys_Vol_max', 'Oper_DC_Vol_min', 'Oper_DC_Vol_max', 'Imp_max', 'Input_MPPT_max', 'Isc_max', 'Isc_MPPT_max', 'DC_Input_max', 'DC_Input_MPPT', 'DC_Wire_max', 'BiPolar', 'Rated_Out_Power', 'AC_Power_max', 'Rated_Out_Volt', 'AC_Low_Vol', 'AC_High_Vol', 'Out_Calc_max', 'Out_max', 'Inverter_Phasing', 'AC_Phases', 'AC_Wires', 'Neut_Ref_Vol', 'AC_max_Wires', 'AC_Wire_Size_max', 'Efficiency_max', 'CEC_Efficiency', 'Power_Factor_Lead', 'Power_Factor_Lag', 'Breaker_min', 'Breaker_max', 'Wire_Ins_Vol_min', 'Lug_Temp', 'xForm_VA_Multiplier', 'AC_Volt_Drop_max', 'Oper_Temp_min', 'Oper_Temp_max', 'Available_Fault', 'Install_Angle_Horiz_min', 'height', 'width', 'depth', 'weight', 'url', 'status', 'product_literature', 'cost', 'rev_date', 'DC_Start_Vol', 'MPPT2_Input_max', 'MPPT2_Short_Circuit_max', 'Input_kW_min', 'MPP_Vol_Low', 'MPP_Vol_High', 'favorite')
                        );

            $totalFiltered = $handler->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
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
                    </button>" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='js-swal-confirm btn btn-danger' style='margin-left:5px;' onclick='delInverter(this,{$inverter['id']})'>
                        <i class='fa fa-trash'></i>
                    </button>" : "")
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

                $tmp = unpack("l", pack("l", crc32($inverter['mfr'] . $inverter['model'] . $inverter['client_no'])));
                $inverter['crc32'] = reset($tmp);
                
                $inverter->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $data['client_no'] = Auth::user()->companyid;
                $tmp = unpack("l", pack("l", crc32($data['mfr'] . $data['model'])));
                $data['crc32'] = reset($tmp);
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
        if(Auth::user()->userrole == 2){
            if(!empty($request['inverterId'])){
                $inverter = CustomInverter::where('id', $request['inverterId'])->first();
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
            } else
                return response()->json(['success' => false, 'message' => 'Cannot find inverter.']);
        } else
            return response()->json(['success' => false, 'message' => 'Empty inverterId.']);
    }
    
    /**
     * Show the custom solar racking page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customRacking()
    {
        if(Auth::user()->userrole == 2 || Auth::user()->userrole == 1){
            $companyList = Company::orderBy('company_name', 'asc')->get();
            return view('equipment.racking.list')->with('companyList', $companyList);
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
        if(Auth::user()->userrole == 2){
            $columns = array( 
                0 =>'custom_solar_racking.id', 
                1 => 'company_info.company_name',
                2 =>'mfr',
                3 =>'model',
            );
            $handler = new CustomRacking;
        } else {
            $columns = array( 
                0 =>'custom_solar_racking.id', 
                1 =>'mfr',
                2 =>'model',
            );
            $handler = CustomRacking::where('client_no', Auth::user()->companyid);
        }
        $handler = $handler->leftjoin('company_info', "company_info.id", "=", "custom_solar_racking.client_no");

        if(Auth::user()->userrole == 2 && !empty($request->input("columns.1.search.value")))
            $handler = $handler->where('company_info.company_name', 'LIKE', "%{$request->input("columns.1.search.value")}%");
        
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
                ->get(
                    array('custom_solar_racking.id', 'company_info.company_name as companyname', 'mfr', 'model', 'style', 'angle', 'rack_weight', 'width', 'depth', 'lowest_height', 'module_spacing_EW', 'module_spacing_NS', 'url', 'favorite')
                );
        }
        else {
            $search = $request->input('search.value'); 
            $rackings =  $handler->where('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array('custom_solar_racking.id', 'company_info.company_name as companyname', 'mfr', 'model', 'style', 'angle', 'rack_weight', 'width', 'depth', 'lowest_height', 'module_spacing_EW', 'module_spacing_NS', 'url', 'favorite')
                        );

            $totalFiltered = $handler->where('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
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
                    </button>" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='js-swal-confirm btn btn-danger' style='margin-left: 5px;' onclick='delRacking(this,{$racking['id']})'>
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
                
                $tmp = unpack("l", pack("l", crc32($racking['mfr'] . $racking['model'] . $racking['client_no'])));
                $racking['crc32'] = reset($tmp);
                
                $racking->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $data['client_no'] = Auth::user()->companyid;
                $tmp = unpack("l", pack("l", crc32($data['mfr'] . $data['model'])));
                $data['crc32'] = reset($tmp);

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
        if(Auth::user()->userrole == 2){
            if(!empty($request['rackingId'])){
                $racking = CustomRacking::where('id', $request['rackingId'])->first();
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
            $companyList = Company::orderBy('company_name', 'asc')->get();
            return view('equipment.stanchion.list')->with('companyList', $companyList);
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
        if(Auth::user()->userrole == 2){
            $columns = array( 
                0 =>'custom_stanchions.id', 
                1 =>'company_info.company_name',
                2 =>'mfr',
                3 =>'model',
            );
            $handler = new CustomStanchion;
        } else {
            $columns = array( 
                0 =>'custom_stanchions.id', 
                1 =>'mfr',
                2 =>'model',
            );
            $handler = CustomStanchion::where('client_no', Auth::user()->companyid);
        }
        $handler = $handler->leftjoin('company_info', "company_info.id", "=", "custom_stanchions.client_no");

        if(Auth::user()->userrole == 2 && !empty($request->input("columns.1.search.value")))
            $handler = $handler->where('company_info.company_name', 'LIKE', "%{$request->input("columns.1.search.value")}%");
        
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
                ->get(
                    array('custom_stanchions.id', 'company_info.company_name as companyname', 'mfr', 'model', 'pullout', 'Z_moment_max', 'Lateral_Pullout', 'Plate_X', 'Plate_Y', 'Height_z', 'Bolt_Holes_Total', 'X1_Bolts', 'X1_Dist_Edge', 'X2_Bolts', 'X2_Dist_Edge', 'X3_Bolts', 'X3_Dist_Edge', 'X4_Bolts', 'X4_Dist_Edge', 'Y1_Bolts', 'Y1_Dist_Edge', 'Y2_Bolts', 'Y2_Dist_Edge', 'Y3_Bolts', 'Y3_Dist_Edge', 'Y4_Bolts', 'Y4_Dist_Edge', 'material', 'weight', 'url', 'favorite')
                );
        }
        else {
            $search = $request->input('search.value'); 
            $stanchions =  $handler->where('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array('custom_stanchions.id', 'company_info.company_name as companyname', 'mfr', 'model', 'pullout', 'Z_moment_max', 'Lateral_Pullout', 'Plate_X', 'Plate_Y', 'Height_z', 'Bolt_Holes_Total', 'X1_Bolts', 'X1_Dist_Edge', 'X2_Bolts', 'X2_Dist_Edge', 'X3_Bolts', 'X3_Dist_Edge', 'X4_Bolts', 'X4_Dist_Edge', 'Y1_Bolts', 'Y1_Dist_Edge', 'Y2_Bolts', 'Y2_Dist_Edge', 'Y3_Bolts', 'Y3_Dist_Edge', 'Y4_Bolts', 'Y4_Dist_Edge', 'material', 'weight', 'url', 'favorite')
                        );

            $totalFiltered = $handler->where('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('mfr', 'LIKE',"%{$search}%")
                        ->orWhere('model', 'LIKE',"%{$search}%")
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
                    </button>" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='js-swal-confirm btn btn-danger' style='margin-left: 5px;' onclick='delStanchion(this,{$stanchion['id']})'>
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

                $tmp = unpack("l", pack("l", crc32($stanchion['mfr'] . $stanchion['model'] . $stanchion['client_no'])));
                $stanchion['crc32'] = reset($tmp);
                
                $stanchion->save();
                return response()->json(['success' => true]);
            } else {
                $data = $request['data'];
                $data['client_no'] = Auth::user()->companyid;
                $tmp = unpack("l", pack("l", crc32($data['mfr'] . $data['model'])));
                $data['crc32'] = reset($tmp);
                
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
        if(Auth::user()->userrole == 2){
            if(!empty($request['stanchionId'])){
                $stanchion = CustomStanchion::where('id', $request['stanchionId'])->first();
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
