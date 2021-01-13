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
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                $query = new JobRequest;
                if(isset($request['dateFrom']))
                    $query = $query->where('createdTime', '>=', $request['dateFrom']);
                if(isset($request['dateTo']))
                {
                    $dateTo = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($request['dateTo'])));
                    $query = $query->where('createdTime', '<=', $dateTo);
                }
                if(isset($request['clientIdFrom']))
                    $query = $query->where('companyId', '>=', $request['clientIdFrom']);
                if(isset($request['clientIdTo']))
                    $query = $query->where('companyId', '<=', $request['clientIdTo']);

                $data = $query->get();
                if(isset($request['sortAlphabetical']) && ($request['sortAlphabetical'] == 'false' ))
                    $data = $data->sortBy('clientProjectNumber', SORT_REGULAR, true);
                else
                    $data = $data->sortBy('clientProjectNumber', SORT_REGULAR, false);
                
                return response()->json(['success' => true, 'message' => 'Success', 'data' => $data]);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Update Project Data
     *
     * @return JSON
     */
    public function updateJobData(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
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
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Download Project JSON File
     *
     * @return JSON
     */
    public function downloadFile(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                if(isset($request['fileName'])){
                    if( Storage::disk('local')->exists($request['fileName']) )
                        return response()->download(storage_path('/app/' . $request['fileName']));
                    else
                        return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Cannot find file.']);
                }
                else
                    return response()->json(['success' => false, 'message' => 'Fail', 'reason' => 'Wrong File Name.']);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }

    /**
     * Get User and Client Admin Email / Name
     *
     * @return JSON
     */
    public function getUserInfo(Request $request){
        if(isset($request['username']) && isset($request['password'])){
            $user = User::where('username', '=', $request['username'])->where('password', '=', $request['password'])->first();
            if($user && $user->userrole == 2)
            {
                $data = array();
                if(isset($request['companyId'])){
                    $data['ClientAdmin'] = User::select('username', 'email')->where('userrole', '=', '1')->where('companyid', '=', $request['companyId'])->first();
                }
                else
                    return response()->json(['success' => false, 'message' => 'Fail', 'message' => 'Wrong File Name.']);

                if(isset($request['userId'])){
                    $data['Client'] = User::select('username', 'email')->where('usernumber', '=', $request['userId'])->where('companyid', '=', $request['companyId'])->first();
                }
                return response()->json(['success' => true, 'data' => $data]);
            }
            else
                return response()->json(['success' => false, 'message' => 'Auth failed.']);
        }
        else
            return response()->json(['success' => false, 'message' => 'Auth required.']);
    }
}
