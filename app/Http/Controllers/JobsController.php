<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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


    //apply job
    public function applyJob(Request $request){
        $jobid = $request->jobid;
        $userid = Auth::id();
        $employerid = Job::find($jobid) -> user_id;

        // return response() -> json([
        //     'jobid' => $jobid,
        //     'employeeid' => $userid,
        //     'employerid' => $employerid,
        // ]);

        //user cant appply on his own job
        if($userid == $employerid){
            session() -> flash('applyjoberror', "You can't apply on your own Job.");
            return response() -> json([
                'status' => false,
            ]);
        }

        //user cant apply twice
        $alreadyapplied = JobApplication::where([
            'job_id' => $jobid,
            'user_id' => $userid,
        ]) -> count();

        if($alreadyapplied  > 0){
            session() -> flash('applyjoberror2', "You already applied for this Job.");
            return response() -> json([
                'status' => false,
            ]);
        }

        $jobapplication = new JobApplication();
        $jobapplication -> job_id = $jobid;
        $jobapplication -> user_id = $userid;
        $jobapplication -> employer_id = $employerid;
        $jobapplication->applied_date = now();
        $jobapplication -> save();


        //send job notification email to employer
        $job = Job::find($jobid);
        $employer = User::find($employerid);
        $mailData = [
            'job' => $job,
            'employer' => $employer,
            'employee' => User::find($userid),
        ];

            try{
            Mail::to($employer->email) -> send(new JobNotificationEmail($mailData));
            }catch(Exception $e){
            }
         session() -> flash('applyjobsuccess', "Applied for Job Successfully.");
            return response() -> json([
                'status' => true,
            ]);
    }

    //save job
    public function saveJob(Request $request){
        
        $count = SavedJob::where([
            'job_id' => $request->jobid,
            'user_id' => Auth::id()
        ]) -> count();

        if($count > 0){
            session() -> flash('savejoberror', "You already saved this Job.");
              return response() -> json([
                'status' => false,
            ]);
        }else{
            $savedjob = new SavedJob();
            $savedjob -> job_id = $request->jobid;
            $savedjob -> user_id = Auth::id();
            $savedjob -> save();
            session() -> flash('savejobsuccess', "Job Saved Successfully.");
            return response() -> json([
                'status' => true,
            ]);
        }
    }

    
}
