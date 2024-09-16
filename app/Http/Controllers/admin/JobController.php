<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(){
        $jobs = Job::orderBy('created_at', 'DESC')->paginate(5);
        return view('admin.jobs.list')->with(['jobs' => $jobs]);
    }

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

}
