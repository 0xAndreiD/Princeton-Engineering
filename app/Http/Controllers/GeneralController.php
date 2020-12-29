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

use DateTime;
use DateTimeZone;

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
    public function rsinput(Request $request)
    {
        $company = Company::where('id', Auth::user()->companyid)->first();
        if( $company )
        {
            $companymembers = User::where('companyid', $company['id'])->get();
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            return view('rsinput.body')
                    ->with('companyName', $company['company_name'])
                    ->with('companyNumber', $company['company_number'])
                    ->with('companyMembers', $companymembers)
                    ->with('projectState', $project ? $project->projectState : 0)
                    ->with('projectId', $request['projectId'] ? $request['projectId'] : -1);
        }
        else
        {
            $companymembers = array();
            return view('rsinput.body')
                    ->with('companyName', "")
                    ->with('companyNumber', "")
                    ->with('companyMembers', $companymembers)
                    ->with('projectState', 0)
                    ->with('projectId', $request['projectId'] ? $request['projectId'] : -1);
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
        if($request['data'] && $request['data']['projectId'] && $request['data']['projectId'] > 0){
            $project = JobRequest::where('id', '=', $request['data']['projectId'])->first();
            if($project){
                if(Auth::user()->userrole == 2 || $project->companyId == Auth::user()->companyid)
                {
                    $data = $this->inputToJson($request['data'], $request['caseCount']);
                    if( Storage::disk('local')->exists($project['requestFile']) )
                        Storage::disk('local')->delete($project['requestFile']);
                    Storage::disk('local')->put($project['requestFile'], json_encode($data));
                    
                    $projectState = 0;
                    if($request['status'] == 'Saved')
                        $projectState = 1;
                    else if($request['status'] == 'Data Check')
                        $projectState = 2;
                    else if($request['status'] == 'Submitted')
                        $projectState = 4;
                    $project->projectState = $projectState;
                    $project->clientProjectName = $request['data']['txt-project-name'];
                    $project->clientProjectNumber = $request['data']['txt-project-number'];
                    $project->submittedTime = gmdate("Y-m-d\TH:i:s", time());
                    $project->save();

                    return response()->json(["message" => "Success!", "status" => true, "projectId" => $project->id]);
                }
                else
                    return response()->json(["message" => "You don't have permission to edit this project.", "status" => false]);
            }
            else
                return response()->json(["message" => "Cannot find project.", "status" => false]);
        }
        else
        {
            $current_time = time();
            $company = Company::where('id', Auth::user()->companyid)->first();
            $projectId = -1;
            $filename = ($company ? sprintf("%06d", $company['company_number']) : "000000") . "-" . sprintf("%04d", Auth::user()->id) . "-" . $current_time . ".json";
            try {
                $company = Company::where('id', Auth::user()->companyid)->first();
                $data = $this->inputToJson($request['data'], $request['caseCount']);
                Storage::disk('local')->put($filename, json_encode($data));
                $projectState = 0;
                if($request['status'] == 'Saved')
                    $projectState = 1;
                else if($request['status'] == 'Data Check')
                    $projectState = 2;
                else if($request['status'] == 'Submitted')
                    $projectState = 4;
                $project = JobRequest::create([
                    'companyName' => $company['company_name'],
                    'companyId' => Auth::user()->companyid,
                    'userId' => Auth::user()->usernumber,
                    'clientProjectName' => $request['data']['txt-project-name'],
                    'clientProjectNumber' => $request['data']['txt-project-number'],
                    'requestFile' => $filename,
                    'planStatus' => 0,
                    'projectState' => $projectState,
                    'analysisType' => 0,
                    'createdTime' => gmdate("Y-m-d\TH:i:s", $current_time),
                    'submittedTime' => gmdate("Y-m-d\TH:i:s", $current_time),
                    'timesDownloaded' => 0,
                    'timesEmailed' => 0,
                    'timesComputed' => 0
                ]);
                $projectId = $project->id;
            }
            catch(Exception $e) {
                return response()->json(["message" => "Failed to generate RS json data file", "status" => false]);
            }
            return response()->json(["message" => "Success!", "status" => true, "projectId" => $projectId]);
        }
    }

    /**
     * Save Input Datas as files
     *
     * @return JSON
     */
    private function inputToJson($input, $caseCount){
        date_default_timezone_set("America/New_York");
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
        $data['Equipment']['PVModule'] = array('Type' => $input['option-module-type'], 'SubType' => $input['option-module-subtype'], 'Option1' => number_format(floatval($input['option-module-option1']), 2), 'Option2' => $input['option-module-option2'], 'Quantity' => $input['option-module-quantity']);
        $data['Equipment']['PVInverter'] = array('Type' => $input['option-inverter-type'], 'SubType' => $input['option-inverter-subtype'], 'Option1' => number_format(floatval($input['option-inverter-option1']), 2), 'Option2' => $input['option-inverter-option2'], 'Quantity' => $input['option-inverter-quantity']);
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
            $caseData['RoofDeckSurface'] = array("D1" => number_format(floatval($caseInput['d-1-1']), 2), "D2" => $caseInput['d-2-1'], "D3" => $caseInput['d-3-1']);
            $caseData['Location'] = array("E1" => number_format(floatval($caseInput['e-1-1']), 2), "E2" => number_format(floatval($caseInput['e-2-1']), 2));
            $caseData['NumberOfModules'] = array("F1" => $caseInput['f-1-1']);
            $caseData['NSGap'] = array("G1" => number_format(floatval($caseInput['g-1-1']), 2));
            $caseData['RotateModuleOrientation'] = array("H1" => filter_var($caseInput['h-1-1'], FILTER_VALIDATE_BOOLEAN), "H2" => filter_var($caseInput['h-2-1'], FILTER_VALIDATE_BOOLEAN), "H3" => filter_var($caseInput['h-3-1'], FILTER_VALIDATE_BOOLEAN), "H4" => filter_var($caseInput['h-4-1'], FILTER_VALIDATE_BOOLEAN), "H5" => filter_var($caseInput['h-5-1'], FILTER_VALIDATE_BOOLEAN), "H6" => filter_var($caseInput['h-6-1'], FILTER_VALIDATE_BOOLEAN), "H7" => filter_var($caseInput['h-7-1'], FILTER_VALIDATE_BOOLEAN), "H8" => filter_var($caseInput['h-8-1'], FILTER_VALIDATE_BOOLEAN), "H9" => filter_var($caseInput['h-9-1'], FILTER_VALIDATE_BOOLEAN), "H10" => filter_var($caseInput['h-10-1'], FILTER_VALIDATE_BOOLEAN), "H11" => filter_var($caseInput['h-11-1'], FILTER_VALIDATE_BOOLEAN), "H12" => filter_var($caseInput['h-12-1'], FILTER_VALIDATE_BOOLEAN));
            $caseData['Notes'] = array("I1" => $caseInput['i-1-1'] ? $caseInput['i-1-1'] : "");

            $caseData['TrussDataInput'] = array();
            $caseData['TrussDataInput']['RoofSlope'] = array('Type' => $caseInput['option-roof-slope'], 'Degree' => number_format(floatval($caseInput['txt-roof-degree']), 2), 'UnknownDegree' => number_format(floatval($caseInput['td-unknown-degree1']), 2), 'CalculatedRoofPlaneLength' => number_format(floatval($caseInput['td-calculated-roof-plane-length']), 2), 'td-diff-between-measured-and-calculated' => number_format(floatval($caseInput['td-diff-between-measured-and-calculated']), 2));
            $caseData['TrussDataInput']['RoofPlane'] = array('MemberType' => $caseInput['option-roof-member-type'], 'Length' => number_format(floatval($caseInput['txt-length-of-roof-plane']), 2), 'NumberOfSegments' => $caseInput['option-number-segments1'], 'SumOfLengthsEntered' => number_format(floatval($caseInput['td-sum-of-length-entered']), 2), 'ChecksumOfChordLength' => $caseInput['td-checksum-of-segment1']);
            if( isset($caseInput['txt-roof-segment1-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment1'] = number_format(floatval($caseInput['txt-roof-segment1-length']), 2);
            if( isset($caseInput['txt-roof-segment2-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment2'] = number_format(floatval($caseInput['txt-roof-segment2-length']), 2);
            if( isset($caseInput['txt-roof-segment3-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment3'] = number_format(floatval($caseInput['txt-roof-segment3-length']), 2);
            if( isset($caseInput['txt-roof-segment4-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment4'] = number_format(floatval($caseInput['txt-roof-segment4-length']), 2);
            if( isset($caseInput['txt-roof-segment5-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment5'] = number_format(floatval($caseInput['txt-roof-segment5-length']), 2);
            if( isset($caseInput['txt-roof-segment6-length']) ) $caseData['TrussDataInput']['RoofPlane']['LengthOfSegment6'] = number_format(floatval($caseInput['txt-roof-segment6-length']), 2);
            $caseData['TrussDataInput']['FloorPlane'] = array('MemberType' => $caseInput['option-floor-member-type'], 'Length' => number_format(floatval($caseInput['txt-length-of-floor-plane']), 2), 'NumberOfSegments' => $caseInput['option-number-segments2'], 'SumOfLengthsEntered' => number_format(floatval($caseInput['td-total-length-entered']), 2), 'ChecksumOfChordLength' => $caseInput['td-checksum-of-segment2']);
            if( isset($caseInput['txt-floor-segment1-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment1'] = number_format(floatval($caseInput['txt-floor-segment1-length']), 2);
            if( isset($caseInput['txt-floor-segment2-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment2'] = number_format(floatval($caseInput['txt-floor-segment2-length']), 2);
            if( isset($caseInput['txt-floor-segment3-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment3'] = number_format(floatval($caseInput['txt-floor-segment3-length']), 2);
            if( isset($caseInput['txt-floor-segment4-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment4'] = number_format(floatval($caseInput['txt-floor-segment4-length']), 2);
            if( isset($caseInput['txt-floor-segment5-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment5'] = number_format(floatval($caseInput['txt-floor-segment5-length']), 2);
            if( isset($caseInput['txt-floor-segment6-length']) ) $caseData['TrussDataInput']['FloorPlane']['LengthOfSegment6'] = number_format(floatval($caseInput['txt-floor-segment6-length']), 2);

            $caseData['Diagonal1'] = array();
            if( isset($caseInput['option-diagonals-mem1-1-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-1'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-1-type'], "memId" => intval($caseInput['td-diag-1-1'])) );
            if( isset($caseInput['option-diagonals-mem1-2-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-2'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-2-type'], "memId" => intval($caseInput['td-diag-1-2'])) );
            if( isset($caseInput['option-diagonals-mem1-3-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-3'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-3-type'], "memId" => intval($caseInput['td-diag-1-3'])) );
            if( isset($caseInput['option-diagonals-mem1-4-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-4'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-4-type'], "memId" => intval($caseInput['td-diag-1-4'])) );
            if( isset($caseInput['option-diagonals-mem1-5-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-5'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-5-type'], "memId" => intval($caseInput['td-diag-1-5'])) );
            if( isset($caseInput['option-diagonals-mem1-6-type']) ) array_push( $caseData['Diagonal1'], array("include" => $caseInput['diag-1-6'] == 'on' ? false : true, "memType" => $caseInput['option-diagonals-mem1-6-type'], "memId" => intval($caseInput['td-diag-1-6'])) );

            $caseData['Diagonal2'] = array();
            if( isset($caseInput['option-diagonals-mem2-1-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-1'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-1'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-1'])) );
            if( isset($caseInput['option-diagonals-mem2-2-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-2'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-2'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-2'])) );
            if( isset($caseInput['option-diagonals-mem2-3-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-3'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-3'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-3'])) );
            if( isset($caseInput['option-diagonals-mem2-4-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-4'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-4'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-4'])) );
            if( isset($caseInput['option-diagonals-mem2-5-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-5'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-5'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-5'])) );
            if( isset($caseInput['option-diagonals-mem2-6-type']) ) array_push( $caseData['Diagonal2'], array("include" => $caseInput['diag-2-6'] == 'on' ? false : true, "reverse" => $caseInput['diag-2-reverse-6'] == 'on' ? true : false, "memType" => $caseInput['option-diagonals-mem2-1-type'], "memId" => intval($caseInput['td-diag-2-6'])) );

            array_push($data['LoadingCase'], $caseData);
            $number ++;
        }

        //Overrides
        $data['Wind'] = number_format(floatval($input['wind-speed']), 1);
        $data['WindCheckbox'] = $input['wind-speed-override'] == "true" ? true : false;
        $data['Snow'] = number_format(floatval($input['ground-snow']), 1);
        $data['SnowCheckbox'] = $input['ground-snow-override'] == "true" ? true : false;

        $data['Units'] = $input['override-unit'];

        return $data;
    }

    /**
     * Show the list of projects.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function projectList(){
        return view('general.projectlist');
    }

    protected $globalStates = array("None", "Saved", "Check Requested", "Reviewed", "Submitted", "Report Prepared", "Plan Requested", "Plan Reviewed", "Link Sent", "Completed");
    protected $globalStatus = array("No action", "Plans uploaded to portal", "Plans reviewed", "Comments issued", "Updated plans uploaded to portal", "Revised comments issued", "Final plans uploaded to portal", "PE sealed plans link sent");
    protected $stateColors = array("danger", "primary", "info", "warning", "primary", "info", "primary", "dark", "secondary", "success");
    protected $statusColors = array("danger", "primary", "info", "warning", "primary", "dark", "secondary", "success");

    /**
     * Return the result of server-side rendering
     *
     * @return JSON
     */
    public function getProjectList(Request $request){
        if(Auth::user()->userrole == 2)
        {
            $handler = new JobRequest;
            $columns = array( 
                0 =>'id', 
                1 =>'companyId',
                2 =>'userId',
                3 =>'clientProjectName',
                4 =>'clientProjectNumber',
                5 =>'requestFile',
                6 =>'createdTime',
                7 =>'submittedTime',
                8 =>'planStatus',
                9 =>'projectState'
            );
        }
        else
        {
            $handler = JobRequest::where('job_request.companyId', Auth::user()->companyid);
            $columns = array( 
                0 =>'id', 
                1 =>'userId',
                2 =>'clientProjectName',
                3 =>'clientProjectNumber',
                4 =>'createdTime',
                5 =>'submittedTime',
                6 =>'planStatus',
                7 =>'projectState'
            );
        }
        
        $totalData = $handler->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $handler = $handler->offset($start)
            ->leftjoin('company_info', "company_info.id", "=", "job_request.companyId")
            ->leftjoin('users', function($join){
                $join->on('job_request.companyId', '=', 'users.companyid');
                $join->on('job_request.userId', '=', 'users.usernumber');
            });

        if(!empty($request->input("created_from")) && $request->input("created_from") != "")
        {
            $date = new DateTime($request->input("created_from"), new DateTimeZone('EST'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $handler = $handler->where('job_request.createdTime', '>=', $date->format("Y-m-d H:i:s"));
        }
        if(!empty($request->input("created_to")) && $request->input("created_to") != "")
        {
            $date = new DateTime($request->input("created_to"), new DateTimeZone('EST'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $handler = $handler->where('job_request.createdTime', '<=', $date->format("Y-m-d H:i:s"));
        }
        if(!empty($request->input("submitted_from")) && $request->input("submitted_from") != "")
        {
            $date = new DateTime($request->input("submitted_from"), new DateTimeZone('EST'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $handler = $handler->where('job_request.submittedTime', '>=', $date->format("Y-m-d H:i:s"));
        }
        if(!empty($request->input("submitted_to")) && $request->input("submitted_to") != "")
        {
            $date = new DateTime($request->input("submitted_to"), new DateTimeZone('EST'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $handler = $handler->where('job_request.submittedTime', '<=', $date->format("Y-m-d H:i:s"));
        }

        if(Auth::user()->userrole == 2){
            if(!empty($request->input("columns.1.search.value")))
                $handler = $handler->where('company_info.company_name', 'LIKE', "%{$request->input("columns.1.search.value")}%");
            if(!empty($request->input("columns.2.search.value")))
                $handler = $handler->where('users.username', 'LIKE', "%{$request->input('columns.2.search.value')}%");
            if(!empty($request->input("columns.3.search.value")))
                $handler = $handler->where('job_request.clientProjectName', 'LIKE', "%{$request->input('columns.3.search.value')}%");
            if(!empty($request->input("columns.4.search.value")))
                $handler = $handler->where('job_request.clientProjectNumber', 'LIKE', "%{$request->input('columns.4.search.value')}%");
        }
        else{
            if(!empty($request->input("columns.1.search.value")))
                $handler = $handler->where('users.username', 'LIKE', "%{$request->input('columns.1.search.value')}%");
            if(!empty($request->input("columns.2.search.value")))
                $handler = $handler->where('job_request.clientProjectName', 'LIKE', "%{$request->input('columns.2.search.value')}%");
            if(!empty($request->input("columns.3.search.value")))
                $handler = $handler->where('job_request.clientProjectNumber', 'LIKE', "%{$request->input('columns.3.search.value')}%");
        }

        if(empty($request->input('search.value')))
        {            
            $jobs = $handler->limit($limit)
                ->orderBy($order,$dir)
                ->get(
                    array(
                        'job_request.id as id',
                        'company_info.company_name as companyname',
                        'users.username as username',
                        'job_request.clientProjectName as projectname',
                        'job_request.clientProjectNumber as projectnumber',
                        'job_request.requestFile as requestfile',
                        'job_request.createdTime as createdtime',
                        'job_request.submittedTime as submittedtime',
                        'job_request.planStatus as planstatus',
                        'job_request.projectState as projectstate',
                    )
                );
            if($handler->count() > 0)
                $totalFiltered = $handler->count();
        }
        else {
            $search = $request->input('search.value'); 
            $jobs =  $handler->where('job_request.id','LIKE',"%{$search}%")
                        ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('users.username', 'LIKE',"%{$search}%")
                        ->orWhere('job_request.clientProjectName', 'LIKE',"%{$search}%")
                        ->orWhere('job_request.clientProjectNumber', 'LIKE',"%{$search}%")
                        ->orWhere('job_request.createdTime', 'LIKE',"%{$search}%")
                        ->orWhere('job_request.submittedTime', 'LIKE',"%{$search}%")
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get(
                            array(
                                'job_request.id as id',
                                'company_info.company_name as companyname',
                                'users.username as username',
                                'job_request.clientProjectName as projectname',
                                'job_request.clientProjectNumber as projectnumber',
                                'job_request.requestFile as requestfile',
                                'job_request.createdTime as createdtime',
                                'job_request.submittedTime as submittedtime',
                                'job_request.planStatus as planstatus',
                                'job_request.projectState as projectstate',
                            )
                        );

            $totalFiltered = $handler->where('job_request.id','LIKE',"%{$search}%")
                        ->orWhere('company_info.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('users.username', 'LIKE',"%{$search}%")
                        ->orWhere('job_request.clientProjectName', 'LIKE',"%{$search}%")
                        ->orWhere('job_request.clientProjectNumber', 'LIKE',"%{$search}%")
                        ->orWhere('job_request.createdTime', 'LIKE',"%{$search}%")
                        ->orWhere('job_request.submittedTime', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();

        if(!empty($jobs))
        {
            foreach ($jobs as $job)
            {
                $nestedData['id'] = $job->id;
                if(Auth::user()->userrole == 2)
                {
                    $nestedData['companyname'] = $job->companyname;
                    $nestedData['requestfile'] = $job->requestfile;
                }
                $nestedData['username'] = $job->username;
                $nestedData['projectname'] = $job->projectname;
                $nestedData['projectnumber'] = $job->projectnumber;
                $date = new DateTime($job->createdtime, new DateTimeZone('UTC'));
                $date->setTimezone(new DateTimeZone('EST'));
                $nestedData['createdtime'] = $date->format("Y-m-d H:i:s");

                $date = new DateTime($job->submittedtime, new DateTimeZone('UTC'));
                $date->setTimezone(new DateTimeZone('EST'));
                $nestedData['submittedtime'] = $date->format("Y-m-d H:i:s");
                
                if(Auth::user()->userrole == 2){
                    $nestedData['projectstate'] = "<span class='badge badge-{$this->stateColors[intval($job->projectstate)]} dropdown-toggle job-dropdown' id='state_{$job->id}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> {$this->globalStates[intval($job->projectstate)]} </span>";

                    $nestedData['projectstate'] .= "<div class='dropdown-menu' aria-labelledby='state_{$job->id}'>";
                    $i = 0;
                    foreach($this->globalStates as $state){
                        $nestedData['projectstate'] .= "<a class='dropdown-item' href='javascript:changeState({$job->id}, {$i})'>{$state}</a>";
                        $i ++;
                    }
                    $nestedData['projectstate'] .= "</div>";

                    $nestedData['planstatus'] = "<span class='badge badge-{$this->statusColors[intval($job->planstatus)]} dropdown-toggle job-dropdown' id='status_{$job->id}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> {$this->globalStatus[intval($job->planstatus)]} </span>";

                    $nestedData['planstatus'] .= "<div class='dropdown-menu' aria-labelledby='status_{$job->id}'>";
                    $i = 0;
                    foreach($this->globalStatus as $status){
                        $nestedData['planstatus'] .= "<a class='dropdown-item' href='javascript:changeStatus({$job->id}, {$i})'>{$status}</a>";
                        $i ++;
                    }
                    $nestedData['planstatus'] .= "</div>";
                }
                else{
                    $nestedData['projectstate'] = "<span class='badge badge-{$this->stateColors[intval($job->projectstate)]}' style='white-space: pre-wrap;'> {$this->globalStates[intval($job->projectstate)]} </span>";                
                    $nestedData['planstatus'] = "<span class='badge badge-{$this->statusColors[intval($job->planstatus)]}' style='white-space: pre-wrap;'> {$this->globalStatus[intval($job->planstatus)]} </span>";                
                }

                $nestedData['actions'] = "
                <div class='text-center'>
                    <a href='rsinput?projectId={$nestedData['id']}' class='btn btn-primary'>
                        <i class='fa fa-pencil-alt'></i>
                    </a>" . 
                    (Auth::user()->userrole == 2 ? "<button type='button' class='js-swal-confirm btn btn-danger' onclick='delProject(this,{$nestedData['id']})'>
                        <i class='fa fa-trash'></i>
                    </button>" : "") . "</div>";
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
     * Return the json file content.
     *
     * @return JSON or TEXT
     */
    public function requestFile(Request $request){
        $job = JobRequest::where('id', $request['jobId'])->first();
        if($job)
        {
            if(Auth::user()->userrole != 2 && Auth::user()->companyid != $job['companyId'])
                return response()->json("You do not have any role to view this project.");
            if( Storage::disk('local')->exists($job['requestFile']) )
                return Storage::disk('local')->get($job['requestFile']);
            else
                return "Sorry, We cannot find the file.";
        }
        else
            return "Sorry, We cannot find the file.";
    }

    /**
     * Return the list of pv modules.
     *
     * @return JSON
     */
    public function getPVModules(Request $request){
        $pv_modules = PVModule::all();
        return json_encode($pv_modules);
    }

    /**
     * Return the list of pv inverters.
     *
     * @return JSON
     */
    public function getPVInverters(Request $request){
        $pv_inverters = PVInverter::all();
        return json_encode($pv_inverters);
    }

    /**
     * Return the list of stanchions.
     *
     * @return JSON
     */
    public function getStanchions(Request $request){
        $stanchions = Stanchion::all();
        return json_encode($stanchions);
    }

    /**
     * Return the list of railsupports.
     *
     * @return JSON
     */
    public function getRailsupport(Request $request){
        $railsupport = RailSupport::all();
        return json_encode($railsupport);
    }

    /**
     * Return the json contents of project.
     *
     * @return JSON
     */
    public function getProjectJson(Request $request){
        if($request['projectId'])
        {
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project)
            {
                if(Auth::user()->userrole == 2 || Auth::user()->companyid == $project['companyId'])
                {
                    if( Storage::disk('local')->exists($project['requestFile']) )
                        return response()->json(['success' => true, 'data' => Storage::disk('local')->get($project['requestFile'])]);
                    else
                        return response()->json(['success' => false, 'message' => "Cannot find the project file."] );
                }
                else
                {
                    return response()->json(['success' => false, 'message' => "You don't have any permission to view this project."] );
                }
            }
            else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong Project Id.'] );
    }

    /**
     * Delete Project
     *
     * @return JSON
     */
    function delProject(Request $request){
        $id = $request->input('data');
        $res = JobRequest::where('id', $id)->delete();
        return $res;
    }

    /**
     * Set the project state of the project.
     *
     * @return JSON
     */
    public function setProjectState(Request $request){
        if($request['projectId'])
        {
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project)
            {
                if(Auth::user()->userrole == 2 || (Auth::user()->companyid == $project['companyId'] && intval($request['state']) <= 2))
                {
                    if( isset($request['state']) )
                    {
                        $project->projectState = $request['state'];
                        $project->save();
                        return response()->json(['success' => true, 'stateText' => "{$this->globalStates[intval($request['state'])]}", 'stateColor' => "{$this->stateColors[intval($request['state'])]}"]);
                    }
                    else
                        return response()->json(['success' => false, 'message' => "Wrong state value."] );
                }
                else
                    return response()->json(['success' => false, 'message' => "You don't have any permission to set state of this project."] );
            }
            else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong Project Id.'] );
    }

    /**
     * Set the plan status of the project.
     *
     * @return JSON
     */
    public function setPlanStatus(Request $request){
        if($request['projectId'])
        {
            $project = JobRequest::where('id', '=', $request['projectId'])->first();
            if($project)
            {
                if(Auth::user()->userrole == 2)
                {
                    if( isset($request['status']) )
                    {
                        $project->planStatus = $request['status'];
                        $project->save();
                        return response()->json(['success' => true, 'statusText' => "{$this->globalStatus[intval($request['status'])]}", 'statusColor' => "{$this->statusColors[intval($request['status'])]}"]);
                    }
                    else
                        return response()->json(['success' => false, 'message' => "Wrong status value."] );
                }
                else
                    return response()->json(['success' => false, 'message' => "You don't have any permission to set status of this project."] );
            }
            else
                return response()->json(['success' => false, 'message' => 'Cannot find the project.'] );
        }
        else
            return response()->json(['success' => false, 'message' => 'Wrong Project Id.'] );
    }
}
