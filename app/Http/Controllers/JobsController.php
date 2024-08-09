<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    //return jobs page, find jobs page, handle search, filter
    public function index(Request $request){
        $categories = Category::where('status', 1) -> get();
        $jobtypes = JobType::where('status', 1) -> get();

        $jobs = Job::where('status', 1);

        //search using keyword
        if(!empty($request -> keyword)){
            // $jobs = $jobs -> where(function($query) use ($request){
            //     $query -> orWhere('title', 'like', '%' . $request->keyword. '%');
            //     $query -> orWhere('keywords', 'like', '%'.$request->keyword. '%');
            // });
            $kword = $request -> keyword;
            $jobs = $jobs->where(function($query) use ($kword) {
                $query->where('title', 'like', '%' . $kword . '%');
                $query->orWhere(function($subQuery) use ($kword) {
                          $subQuery->whereRaw('FIND_IN_SET(?, keywords) > 0', [$kword]);
                });
            });  
        }

        //search using location
        if(!empty($request -> location)){
            $jobs = $jobs -> where('location', $request->location);
        }

        //search using category
        if(!empty($request -> category)){
            $jobs = $jobs -> where('category_id', $request->category);
        }

        $jobTypeArray = [];

        //search using jobType
        if(!empty($request -> jobType)){
            $jobTypeArray = explode(',', $request->jobType);
            $jobs = $jobs -> whereIn('job_type_id', $jobTypeArray);
        }

         //search using experience
         if(!empty($request -> experience)){
            $jobs = $jobs -> where('experience', $request->experience);
        }


        if($request->sort == 0){
            $jobs = $jobs -> orderBy('created_at', 'desc');
        }else{
            $jobs = $jobs -> orderBy('created_at', 'asc');
        }

        $jobs = $jobs -> paginate(6);
        
        return view('front.jobs',[
            'categories' => $categories,
            'jobtypes' => $jobtypes,
            'jobs' => $jobs,
            'jobTypeArray' => $jobTypeArray,
        ]);
    }

    //return job detail page
    public function jobDetail($jobid){
        $job = Job::where(['id'=>$jobid, 'status' => 1]) -> first();
        // dd($job);
        if($job == null){
            abort(404);
        }
        return view('front.jobDetail',['job' => $job]);

    }
}
