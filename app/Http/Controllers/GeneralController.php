<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Company;
use App\PVModule;
use App\PVInverter;
use App\Stanchion;
use App\RailSupport;
use App\JobRequest;

class GeneralController extends Controller
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
        if( Auth::user()->userrole == 2 )
            return view('admin.home');
        else if( Auth::user()->userrole == 1 )
            return view('clientadmin.home');
        else if( Auth::user()->userrole == 0 )
            return view('user.home');
    }

    /**
     * Show the rf multiple input form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function rsinput()
    {
        $company = Company::where('id', Auth::user()->companyid)->first();
        $pv_modules = PVModule::all();
        $pv_inverters = PVInverter::all();
        $stanchions = Stanchion::all();
        $railsupport = RailSupport::all();
        if( $company )
        {
            $companymembers = User::where('companyid', $company['id'])->get();
            return view('rsinput.body')
                    ->with('companyName', $company['company_name'])
                    ->with('companyNumber', $company['company_number'])
                    ->with('companyMembers', $companymembers)
                    ->with('pv_modules', $pv_modules)
                    ->with('pv_inverters', $pv_inverters)
                    ->with('stanchions', $stanchions)
                    ->with('railsupport', $railsupport);
        }
        else
        {
            $companymembers = array();
            return view('rsinput.body')
                    ->with('companyName', "")
                    ->with('companyNumber', "")
                    ->with('companyMembers', $companymembers)
                    ->with('pv_modules', $pv_modules)
                    ->with('pv_inverters', $pv_inverters)
                    ->with('stanchions', $stanchions)
                    ->with('railsupport', $railsupport);
        }
    }

    /**
     * Get the same company member data
     *
     * @return JSON
     */
    public function getUserData(Request $request){
        if($request['userId'])
        {
            $user = User::where('companyid', Auth::user()->companyid)->where('id', $request['userId'])->first();
            if( $user )
                return response()->json($user);
            else
                return response()->json([]);
        }
        else
            return response()->json([]);
    }

    /**
     * Save Input Datas as files
     *
     * @return JSON
     */
    public function submitInput(Request $request){
        $current_time = time();
        $company = Company::where('id', Auth::user()->companyid)->first();

        $filename = ($company ? sprintf("%06d", $company['company_number']) : "000000") . "-" . sprintf("%04d", Auth::user()->id) . "-" . $current_time . ".json";
        try {
            $company = Company::where('id', Auth::user()->companyid)->first();
            $data = $this->inputToJson($request['data'], $request['caseCount']);
            Storage::disk('local')->put($filename, json_encode($data));
            $available = 0;
            if($request['status'] == 'Saved')
                $available = 1;
            else if($request['status'] == 'Data Check')
                $available = 2;
            else if($request['status'] == 'Submitted')
                $available = 3;
            JobRequest::create([
                'companyName' => $company['company_name'],
                'companyId' => Auth::user()->companyid,
                'userId' => Auth::user()->usernumber,
                'clientProjectName' => $request['data']['txt-project-name'],
                'clientProjectNumber' => $request['data']['txt-project-number'],
                'requestFile' => $filename,
                'available' => $available,
                'projectState' => 0,
                'analysisType' => 0,
                'createdTime' => gmdate("Y-m-d\TH:i:s", $current_time),
                'submittedTime' => gmdate("Y-m-d\TH:i:s", 0),
                'timesDownloaded' => 0,
                'timesEmailed' => 0,
                'timesComputed' => 0
            ]);
        }
        catch(Exception $e) {
            return response()->json(["message" => "Failed to generate RS json data file", "status" => false]);
        }
        return response()->json(["message" => "Success!", "status" => true]);
    }

    /**
     * Save Input Datas as files
     *
     * @return JSON
     */
    private function inputToJson($input, $caseCount){
        $data = array();

        //ProgramData
        $data['ProgramData'] = array();
        $data['ProgramData']['Name'] = "iRoof TM";
        $data['ProgramData']['Vendor'] = "Princeton Engineering";
        $data['ProgramData']['Department'] = "Web Services";
        $data['ProgramData']['Version'] = array("Rev" => "3.0.0", "RevDate" => gmdate("m/d/Y", time()));
        $data['ProgramData']['Description'] = "Data Input form for roof structural analysis for Residential Solar Projects";
        $data['ProgramData']['Copyright'] = "Copyright © 2020 Richard Pantel. All Rights Reserved  No parts of this data input form or related calculation reports may be copied in format, content or intent, or reproduced in any form or by any electronic or mechanical means, including information storage and retrieval systems, without permission in writing from the author.  Further, dis-assembly or reverse engineering of this data input form or related calculation reports is strictly prohibited. The author's contact information is: RPantel@Princeton-Engineering.com, web-site: www.Princeton-Engineering.com; tel: 908-507-5500";

        $company = Company::where('id', Auth::user()->companyid)->first();
        
        //CompanyInfo
        $data['CompanyInfo'] = array();
        $data['CompanyInfo']['Name'] = $company['company_name'];
        $data['CompanyInfo']['Number'] = $company['company_number'];
        $data['CompanyInfo']['UserId'] = $company['company_number'] . "." . Auth::user()->usernumber;
        $data['CompanyInfo']['Username'] = Auth::user()->username;
        $data['CompanyInfo']['UserEmail'] = Auth::user()->email;

        //ProjectInfo
        $data['ProjectInfo'] = array();
        $data['ProjectInfo']['Number'] = $input['txt-project-number'];
        $data['ProjectInfo']['Name'] = $input['txt-project-name'];
        $data['ProjectInfo']['Street'] = $input['txt-street-address'];
        $data['ProjectInfo']['City'] = $input['txt-city'];
        $data['ProjectInfo']['State'] = $input['option-state'];
        $data['ProjectInfo']['Zip'] = $input['txt-zip'];
        
        //Personnel
        $data['Personnel'] = array();
        $data['Personnel']['Name'] = $input['txt-name-of-field-personnel'];
        $data['Personnel']['DateOfFieldVisit'] = $input['date-of-field-visit'];
        $data['Personnel']['DateOfPlanSet'] = $input['date-of-plan-set'];

        //BuildingAge
        $data['BuildingAge'] = $input['txt-building-age'];
        
        //Equipment
        $data['Equipment'] = array();
        $data['Equipment']['PVModule'] = array('Type' => $input['option-module-type'], 'SubType' => $input['option-module-subtype'], 'Option1' => number_format(floatval($input['option-module-option1']), 2), 'Option2' => $input['option-module-option2']);
        $data['Equipment']['PVInverter'] = array('Type' => $input['option-inverter-type'], 'SubType' => $input['option-inverter-subtype'], 'Option1' => number_format(floatval($input['option-inverter-option1']), 2), 'Option2' => $input['option-inverter-option2']);
        $data['Equipment']['Stanchion'] = array('Type' => $input['option-stanchion-type'], 'SubType' => $input['option-stanchion-subtype'], 'Option1' => number_format(floatval($input['option-stanchion-option1']), 2), 'Option2' => $input['option-stanchion-option2']);
        $data['Equipment']['RailSupportSystem'] = array('Type' => $input['option-railsupport-type'], 'SubType' => $input['option-railsupport-subtype'], 'Option1' => number_format(floatval($input['option-railsupport-option1']), 2), 'Option2' => $input['option-railsupport-option2']);

        //NumberLoadingConditions = CaseCount
        $data['NumberLoadingConditions'] = $caseCount;

        //LoadingCase Data
        $data['LoadingCase'] = array();
        $number = 1;
        foreach($input['caseInputs'] as $caseInput)
        {
            $caseData = array();
            $caseData['LC_Number'] = $number;
            $caseData['TrussFlag'] = filter_var($caseInput['TrussFlag'], FILTER_VALIDATE_BOOLEAN);
            $caseData['RoofDataInput'] = array("A1" => $number, "A2" => number_format(floatval($caseInput['a-2-1']), 2), "A3" => number_format(floatval($caseInput['a-3-1']), 2), "A4" => number_format(floatval($caseInput['a-4-1']), 2), "A5" => $caseInput['a-5-1'], "A6" => $caseInput['a-6-1'], "A7" => number_format(floatval( isset($caseInput['a-7-1']) ? $caseInput['a-7-1'] : 0 ), 2), "A7calc" => number_format(floatval(isset($caseInput['ac-7-1']) ? $caseInput['ac-7-1'] : 0), 2), "A8" => number_format(floatval(isset($caseInput['a-8-1']) ? $caseInput['a-8-1'] : 0), 2), "A8calc" => number_format(floatval(isset($caseInput['ac-8-1']) ? $caseInput['ac-8-1'] : 0), 2), "A9" => number_format(floatval(isset($caseInput['a-9-1']) ? $caseInput['a-9-1'] : 0), 2), "A9calc" => number_format(floatval(isset($caseInput['ac-9-1']) ? $caseInput['ac-9-1'] : 0), 2), "A10" => number_format(floatval(isset($caseInput['a-10-1']) ? $caseInput['a-10-1'] : 0), 2), "A10calc" => number_format(floatval(isset($caseInput['ac-10-1']) ? $caseInput['ac-10-1'] : 0), 2), "A11" => number_format(floatval(isset($caseInput['a-11-1']) ? $caseInput['a-11-1'] : 0), 2));
            $caseData['RafterDataInput'] = array("B1" => number_format(floatval(isset($caseInput['b-1-1']) ? $caseInput['b-1-1'] : 0), 2), "B2" => number_format(floatval(isset($caseInput['b-2-1']) ? $caseInput['b-2-1'] : 0), 2), "B3" => number_format(floatval($caseInput['b-3-1']), 2), "B4" => $caseInput['b-4-1']);
            $caseData['CollarTieInformation'] = array("C1" => isset($caseInput['c-1-1']) ? $caseInput['c-1-1'] : "", "C2" => number_format(floatval(isset($caseInput['c-2-1']) ? $caseInput['c-2-1'] : 0), 2), "C3" => number_format(floatval(isset($caseInput['c-3-1']) ? $caseInput['c-3-1'] : 0), 2), "C4" => number_format(floatval(isset($caseInput['c-4-1']) ? $caseInput['c-4-1'] : 0), 2));
            $caseData['RoofDeckSurface'] = array("D1" => number_format(floatval($caseInput['d-1-1']), 2), "D2" => $caseInput['d-2-1'], "D3" => number_format(floatval($caseInput['d-3-1']), 2));
            $caseData['Location'] = array("E1" => number_format(floatval($caseInput['e-1-1']), 2), "E2" => number_format(floatval($caseInput['e-2-1']), 2));
            $caseData['NumberOfModules'] = array("F1" => number_format(floatval($caseInput['f-1-1']), 2));
            $caseData['NSGap'] = array("G1" => number_format(floatval($caseInput['g-1-1']), 2));
            $caseData['RotateModuleOrientation'] = array("H1" => filter_var($caseInput['h-1-1'], FILTER_VALIDATE_BOOLEAN), "H2" => filter_var($caseInput['h-2-1'], FILTER_VALIDATE_BOOLEAN), "H3" => filter_var($caseInput['h-3-1'], FILTER_VALIDATE_BOOLEAN), "H4" => filter_var($caseInput['h-4-1'], FILTER_VALIDATE_BOOLEAN), "H5" => filter_var($caseInput['h-5-1'], FILTER_VALIDATE_BOOLEAN), "H6" => filter_var($caseInput['h-6-1'], FILTER_VALIDATE_BOOLEAN), "H7" => filter_var($caseInput['h-7-1'], FILTER_VALIDATE_BOOLEAN), "H8" => filter_var($caseInput['h-8-1'], FILTER_VALIDATE_BOOLEAN), "H9" => filter_var($caseInput['h-9-1'], FILTER_VALIDATE_BOOLEAN), "H10" => filter_var($caseInput['h-10-1'], FILTER_VALIDATE_BOOLEAN), "H11" => filter_var($caseInput['h-11-1'], FILTER_VALIDATE_BOOLEAN), "H12" => filter_var($caseInput['h-12-1'], FILTER_VALIDATE_BOOLEAN));
            $caseData['Notes'] = array("I1" => $caseInput['i-1-1'] ? $caseInput['i-1-1'] : "");

            $caseData['TrussDataInput'] = array();
            $caseData['TrussDataInput']['RoofSlope'] = array('Type' => $caseInput['option-roof-slope'], 'Degree' => number_format(floatval($caseInput['txt-roof-degree']), 2), 'UnknownDegree' => number_format(floatval($caseInput['td-unknown-degree1']), 2), 'CalculatedRoofPlaneLength' => number_format(floatval($caseInput['td-calculated-roof-plane-length']), 2), 'td-diff-between-measured-and-calculated' => number_format(floatval($caseInput['td-diff-between-measured-and-calculated']), 2));
            $caseData['TrussDataInput']['RoofPlane'] = array('MemberType' => $caseInput['option-roof-member-type'], 'Length' => number_format(floatval($caseInput['txt-length-of-roof-plane']), 2), 'NumberOfSegments' => number_format(floatval($caseInput['option-number-segments1']), 2), 'SumOfLengthsEntered' => number_format(floatval($caseInput['td-sum-of-length-entered']), 2), 'ChecksumOfChordLength' => $caseInput['td-checksum-of-segment1']);
            if( isset($caseInput['txt-roof-segment1-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment1'] = number_format(floatval($caseInput['txt-roof-segment1-length']), 2);
            if( isset($caseInput['txt-roof-segment2-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment2'] = number_format(floatval($caseInput['txt-roof-segment2-length']), 2);
            if( isset($caseInput['txt-roof-segment3-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment3'] = number_format(floatval($caseInput['txt-roof-segment3-length']), 2);
            if( isset($caseInput['txt-roof-segment4-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment4'] = number_format(floatval($caseInput['txt-roof-segment4-length']), 2);
            if( isset($caseInput['txt-roof-segment5-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment5'] = number_format(floatval($caseInput['txt-roof-segment5-length']), 2);
            if( isset($caseInput['txt-roof-segment6-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment6'] = number_format(floatval($caseInput['txt-roof-segment6-length']), 2);
            $caseData['TrussDataInput']['FloorPlane'] = array('MemberType' => $caseInput['option-floor-member-type'], 'Length' => number_format(floatval($caseInput['txt-length-of-floor-plane']), 2), 'NumberOfSegments' => number_format(floatval($caseInput['option-number-segments2']), 2), 'SumOfLengthsEntered' => number_format(floatval($caseInput['td-total-length-entered']), 2), 'ChecksumOfChordLength' => $caseInput['td-checksum-of-segment2']);
            if( isset($caseInput['txt-floor-segment1-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment1'] = number_format(floatval($caseInput['txt-roof-segment1-length']), 2);
            if( isset($caseInput['txt-floor-segment2-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment2'] = number_format(floatval($caseInput['txt-roof-segment2-length']), 2);
            if( isset($caseInput['txt-floor-segment3-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment3'] = number_format(floatval($caseInput['txt-roof-segment3-length']), 2);
            if( isset($caseInput['txt-floor-segment4-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment4'] = number_format(floatval($caseInput['txt-roof-segment4-length']), 2);
            if( isset($caseInput['txt-floor-segment5-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment5'] = number_format(floatval($caseInput['txt-roof-segment5-length']), 2);
            if( isset($caseInput['txt-floor-segment6-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment6'] = number_format(floatval($caseInput['txt-roof-segment6-length']), 2);

            $caseData['Diagonal1'] = array();
            if( isset($caseInput['option-diagonals-mem1-1-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-1'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-1-type'], "memId" => intval($caseInput['td-diag-1-1'])) );
            if( isset($caseInput['option-diagonals-mem1-2-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-2'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-2-type'], "memId" => intval($caseInput['td-diag-1-2'])) );
            if( isset($caseInput['option-diagonals-mem1-3-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-3'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-3-type'], "memId" => intval($caseInput['td-diag-1-3'])) );
            if( isset($caseInput['option-diagonals-mem1-4-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-4'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-4-type'], "memId" => intval($caseInput['td-diag-1-4'])) );
            if( isset($caseInput['option-diagonals-mem1-5-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-5'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-5-type'], "memId" => intval($caseInput['td-diag-1-5'])) );
            if( isset($caseInput['option-diagonals-mem1-6-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-6'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-6-type'], "memId" => intval($caseInput['td-diag-1-6'])) );

            $caseData['Diagonal2'] = array();
            if( isset($caseInput['option-diagonals-mem1-1-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-1'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-1'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-1'])) );
            if( isset($caseInput['option-diagonals-mem1-2-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-2'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-2'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-2'])) );
            if( isset($caseInput['option-diagonals-mem1-3-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-3'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-3'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-3'])) );
            if( isset($caseInput['option-diagonals-mem1-4-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-4'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-4'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-4'])) );
            if( isset($caseInput['option-diagonals-mem1-5-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-5'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-5'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-5'])) );
            if( isset($caseInput['option-diagonals-mem1-6-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-6'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-6'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-6'])) );

            array_push($data['LoadingCase'], $caseData);
            $number ++;
        }

        return $data;
    }

    /**
     * Show the list of projects.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function projectList(){
        $projects = array();
        if(Auth::user()->userrole == 2)
            $projects = JobRequest::all();
        else if(Auth::user()->userrole == 1)
            $projects = JobRequest::where('companyId', Auth::user()->companyid)->all();
        else
            $projects = JobRequest::where('companyId', Auth::user()->companyid)->where('userId', Auth::user()->usernumber)->all();
        
        return view('general.projectlist')->with('projects', $projects);
    }

    /**
     * Show the list of projects.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function requestFile(Request $request){
        $job = JobRequest::where('id', $request['jobId'])->first();
        if($job)
        {
            if((Auth::user()->userrole == 0 && (Auth::user()->usernumber != $job['userId'] || Auth::user()->companyid != $job['companyId'])) || (Auth::user()->userrole == 1 && Auth::user()->companyid != $job['companyId']))
                return response()->json("You do not have any role to view this project.");
            if( Storage::disk('local')->exists($job['requestFile']) )
                return Storage::disk('local')->get($job['requestFile']);
            else
                return "Sorry, We cannot find the file.";
        }
        else
            return "Sorry, We cannot find the file.";
    }
}
