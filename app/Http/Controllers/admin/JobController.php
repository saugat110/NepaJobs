<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    //list all jobs
    public function index(){
        $jobs = Job::orderBy('created_at', 'DESC')->paginate(5);
        return view('admin.jobs.list')->with(['jobs' => $jobs]);
    }

    //feature, unfeature job
    public function jobFeatureManage(Request $request){
        if($request->status == 'Feature'){
            $job = Job::find($request ->id);
            $job->isFeatured = 1;
            $job -> save();
            session() -> flash("jobFeatured", "Job Featured Successfully");
            return response() -> json([
                'status' => true,
                'type' => $request->status,
                'id' => $request->id
            ]) ;
        }else if($request -> status == 'Unfeature'){
            $job = Job::find($request->id);
            $job->isFeatured = 0;
            $job -> save();
            session() -> flash("jobUnFeatured", "Job UnFeatured Successfully");
            return response() -> json([
                'status' => true,
                'type' => $request->status,
                'id' => $request->id
            ]) ;
        }
    }

    //job status manage, enable, disable
    public function jobStatus(Request $request){
        $job = Job::find($request -> jobid);
        if($job){
            if($request->status == 'enable'){
                $job -> status = 1;
                session() -> flash('jobStatus', 'Job status changed successfully');
            }else{
                $job -> status = 0;
                session() -> flash('jobStatus', 'Job status changed successfully');
            }
            $job -> save();
            return response() -> json([
                'status' => true
            ]);
        }else{
            return response() -> json([
                'status' => false,
                'error' => 'job not found'
            ]);
        }
    }

    //delete job
    public function deleteJob(Request $request){
        $job = Job::find($request->id);
        if($job){
            $job -> delete();
            session() -> flash("jobDeleted", 'Job deleted successfully');
            return response() -> json([
                'status' => true
            ]);
        }else{
            return response() -> json([
                'status' => false
            ]);
        }
    }

}
