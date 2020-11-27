<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use App\User;
use App\JobRequest;

class APIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

    }

    /**
     * Return the list of projects in json
     *
     * @return JSON
     */
    public function getJobList(Request $request){
        $query = JobRequest::where('available', '!=', 'Removed');
        if(isset($request['dateFrom']))
            $query->where('createdTime', '>=', $request['dateFrom']);
        if(isset($request['dateTo']))
        {
            $dateTo = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($request['dateTo'])));
            $query->where('createdTime', '<=', $dateTo);
        }
        if(isset($request['clientIdFrom']))
            $query->where('companyId', '>=', $request['clientIdFrom']);
        if(isset($request['clientIdTo']))
            $query->where('companyId', '<=', $request['clientIdTo']);
        
        return response()->json(['success' => true, 'message' => 'Success', 'data' => $query->get()]);
    }

    /**
     * Update Project Data
     *
     * @return JSON
     */
    public function updateJobData(Request $request){
        if(isset($request['requestFile']))
        {
            $job = JobRequest::where('requestFile', $request['requestFile'])->first();
            if($job){
                $columns = Schema::getColumnListing('job_request');
                foreach($columns as $column){
                    if($column != 'requestFile' && isset($request[$column]))
                        $job[$column] = $request[$column];
                }
                if($job->save())
                    return response()->json(['success' => true, 'message' => 'Success']);
                else
                    return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Database Error.']);
            }
            else
                return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Cannot find file.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Wrong File Name.']);
    }

    /**
     * Download Project JSON File
     *
     * @return JSON
     */
    public function downloadFile(Request $request){
        if(isset($request['fileName'])){
            if( Storage::disk('local')->exists($request['fileName']) )
                return response()->download(storage_path('/app/' . $request['fileName']));
            else
                return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Cannot find file.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Wrong File Name.']);
    }
}
