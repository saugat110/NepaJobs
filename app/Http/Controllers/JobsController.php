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
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    //return jobs page, find jobs page, handle search, filter
    public function index(Request $request)
    {
        $categories = Category::where('status', 1)->get();
        $jobtypes = JobType::where('status', 1)->get();

        // Fetch jobs with eager loading
        $jobs = Job::where('status', 1)
            ->whereHas('user', function ($query) {
                $query->where('status', 1);
            })
            ->whereHas('category', function($query){
                $query -> where('status', 1);
            })
            ->whereHas('jobtype', function($query){
                $query -> where('status', 1);
            });
            // ->with(['jobType'])
            // ->get();
            
        // Apply filters based on request parameters
        if (!empty($request->keyword)) {
            // $kword = $request->keyword;
            // $jobs = $jobs->filter(function ($job) use ($kword) {
            //     return stripos($job->title, $kword) !== false || stripos($job->keywords, $kword) !== false;
            // });
            // $jobs = $jobs -> unique('id');
            $kword = $request -> keyword;
            $jobs = $jobs->where(function($query) use ($kword) {
                $query->where('title', 'like', '%' . $kword . '%');
                $query->orWhere(function($subQuery) use ($kword) {
                          $subQuery->whereRaw('FIND_IN_SET(?, keywords) > 0', [$kword]);
                });
            });  
        }
        
        //convert to collection, to filter
        $jobs = $jobs -> get();

        if (!empty($request->location)) {
            $jobs = $jobs->filter(function ($job) use ($request) {
                return stripos($job->location, $request->location) !== false;
            });    
        }

        if (!empty($request->category)) {
            $jobs = $jobs->filter(function ($job) use ($request) {
                return ($job->category_id == $request->category);
            });
        }

        if (!empty($request->jobType)) {
            $jobTypeArray = explode(',', $request->jobType);
            $jobs = $jobs->filter(function ($job) use ($jobTypeArray) {
                return in_array($job->job_type_id, $jobTypeArray);
            });
        }

        if (!empty($request->experience)) {
            $jobs = $jobs->filter(function ($job) use ($request) {
                return $job->experience == $request->experience;
            });
        }

        $jobs = $jobs -> unique('id') -> values();
        
        // Convert collection to array for sorting
        $jobsArray = $jobs->toArray();

        //Merge sort algorithm
        if (count($jobsArray) > 1) {
            $middle = intdiv(count($jobsArray), 2);
            $left = array_slice($jobsArray, 0, $middle);
            $right = array_slice($jobsArray, $middle);

            // Sort left half
            for ($i = 0; $i < count($left); $i++) {
                for ($j = $i; $j > 0 && $left[$j - 1]['created_at'] > $left[$j]['created_at']; $j--) {
                    $temp = $left[$j];
                    $left[$j] = $left[$j - 1];
                    $left[$j - 1] = $temp;
                }
            }

            // Sort right half
            for ($i = 0; $i < count($right); $i++) {
                for ($j = $i; $j > 0 && $right[$j - 1]['created_at'] > $right[$j]['created_at']; $j--) {
                    $temp = $right[$j];
                    $right[$j] = $right[$j - 1];
                    $right[$j - 1] = $temp;
                }
            }

            // Merge sorted halves
            $i = 0;
            $j = 0;
            $k = 0;
            while ($i < count($left) && $j < count($right)) {
                if ($left[$i]['created_at'] <= $right[$j]['created_at']) {
                    $jobsArray[$k] = $left[$i];
                    $i++;
                } else {
                    $jobsArray[$k] = $right[$j];
                    $j++;
                }
                $k++;
            }

            while ($i < count($left)) {
                $jobsArray[$k] = $left[$i];
                $i++;
                $k++;
            }

            while ($j < count($right)) {
                $jobsArray[$k] = $right[$j];
                $j++;
                $k++;
            }
        }

        // Reverse order if requested
        if ($request->sort == 0) {
            $jobsArray = array_reverse($jobsArray);
        }

        // Convert back to collection
        $jobs = collect($jobsArray)->map(function ($jobArray) {
            return Job::find($jobArray['id']);
        });

        // Paginate
        $page = $request->input('page', 1); // Get the current page number
        $perPage = 6; // Number of items per page
        $offset = ($page - 1) * $perPage;
        $currentPath = url()->current(); // Get the current URL path
        $paginatedJobs = new LengthAwarePaginator(
            $jobs->slice($offset, $perPage)->values(),
            $jobs->count(),
            $perPage,
            $page,
            ['path' => $currentPath]
        );

        return view('front.jobs', [
            'categories' => $categories,
            'jobtypes' => $jobtypes,
            'jobs' => $paginatedJobs,
            'jobTypeArray' => $jobTypeArray ?? [],
        ]);
    }

    //return job detail page
    public function jobDetail($jobid)
    {
        $job = Job::where(['id' => $jobid, 'status' => 1])->first();
        // dd($job);
        if ($job == null) {
            abort(404);
        }

        //fetch job applications, first check if the job is posted by the user, and second
        //check if there are applications for this job in job_applications table

        // $job_posted_byuser = Job::where([
        //     'id' => $jobid,
        //     'user_id' => Auth::id()
        // ])->exists();

        // if($job_posted_byuser){
        //     $job_has_applications = JobApplication::where('job_id', $jobid) -> exists();
        //     if($job_has_applications){
        //         $jobapplications = JobApplication::where('job_id', $jobid) -> get();
        //         return view('front.jobDetail',[
        //             'job' => $job,
        //             'jobapplications' => $jobapplications,
        //         ]);
        //     }
        // }

        return view('front.jobDetail', ['job' => $job, 'jobapplications' => '']);
    }

    //apply job
    public function applyJob(Request $request)
    {
        $jobid = $request->jobid;
        $userid = Auth::id();
        $employerid = Job::find($jobid)->user_id;

        // return response() -> json([
        //     'jobid' => $jobid,
        //     'employeeid' => $userid,
        //     'employerid' => $employerid,
        // ]);

        //user cant appply on his own job
        if ($userid == $employerid) {
            session()->flash('applyjoberror', "You can't apply on your own Job.");
            return response()->json([
                'status' => false,
            ]);
        }

        //user cant apply twice
        $alreadyapplied = JobApplication::where([
            'job_id' => $jobid,
            'user_id' => $userid,
        ])->count();

        if ($alreadyapplied  > 0) {
            session()->flash('applyjoberror2', "You already applied for this Job.");
            return response()->json([
                'status' => false,
            ]);
        }

        $jobapplication = new JobApplication();
        $jobapplication->job_id = $jobid;
        $jobapplication->user_id = $userid;
        $jobapplication->employer_id = $employerid;
        $jobapplication->applied_date = now();
        $jobapplication->save();


        //send job notification email to employer
        $job = Job::find($jobid);
        $employer = User::find($employerid);
        $mailData = [
            'job' => $job,
            'employer' => $employer,
            'employee' => User::find($userid),
        ];

        try {
            Mail::to($employer->email)->send(new JobNotificationEmail($mailData));
        } catch (Exception $e) {
        }
        session()->flash('applyjobsuccess', "Applied for Job Successfully.");
        return response()->json([
            'status' => true,
        ]);
    }

    //save job
    public function saveJob(Request $request)
    {

        $count = SavedJob::where([
            'job_id' => $request->jobid,
            'user_id' => Auth::id()
        ])->count();

        if ($count > 0) {
            session()->flash('savejoberror', "You already saved this Job.");
            return response()->json([
                'status' => false,
            ]);
        } else {
            $savedjob = new SavedJob();
            $savedjob->job_id = $request->jobid;
            $savedjob->user_id = Auth::id();
            $savedjob->save();
            session()->flash('savejobsuccess', "Job Saved Successfully.");
            return response()->json([
                'status' => true,
            ]);
        }
    }

    //return job applications page for a job
    public function jobApplications(Request $request, $jobid)
    {
        //paila jobapplication ma requested job ko application xa ki xaina check
        $jobapplicationpresent = JobApplication::where('job_id', $jobid)->exists();
        if (!$jobapplicationpresent) {
            return abort(404);
        }

        //job application xa, tara requested job ko applications, cahi requesting user lai belong garxa?
        $job_posted_byuser = Job::where([
            'id' => $jobid,
            'user_id' => Auth::id()
        ])->exists();

        //aru ko jobs ko job application herna milnu vaena
        if ($job_posted_byuser) {
            // $jobapplications = JobApplication::where('job_id', $jobid)
            //                 ->where('application_status', '!=', -1)
            //                 ->orderBy('created_at', 'desc')
            //                 ->paginate(5);

            $currentPage = request('page', 1);
            $jobapplications = JobApplication::where('job_id', $jobid)
                ->orderByRaw("CASE WHEN application_status = -1 THEN 1 ELSE 0 END")
                ->orderBy('created_at', 'desc')
                ->paginate(5, ['*'], 'page', $currentPage);
            return view('front.account.job.applicant', ['jobapplications' => $jobapplications, 'jid' => $jobid]);
        }
    }

    //handle application accept and reject
    public function applicationHandler(Request $request)
    {
        $jobapplication = JobApplication::where([
            'job_id' => $request->jobid,
            'user_id' => $request->userid
        ])->first();

        //job application xa vne
        if ($jobapplication != null) {
            if ($request->status == 1) {
                $jobapplication->application_status = 1;
                $jobapplication->save();

                $jobapplication->job->decrement('vacancy', 1);
                session()->flash('jobaccepted', "Job Accepted");
                return response()->json(['status' => 'accepted']);
            }
            if ($request->status == -1) {
                if ($jobapplication->application_status != 0) {
                    $jobapplication->job->increment('vacancy', 1);
                }

                $jobapplication->application_status = -1;
                $jobapplication->save();

                if ($request->fromprofile != null) {
                    session()->flash("jobrejected", "Application Rejected");
                }
                return response()->json(['status' => 'rejected']);
            }
        } else {
            return response()->json(['status' => 'jobnotfound']);
        }
    }

    //remove rejected applications
    public function removeRejectedApplications(Request $request)
    {
        // echo $request->jobid;

        $jobapplications = JobApplication::where([
            'job_id' => $request->jobid,
            'application_status' => -1
        ])->get();
        foreach ($jobapplications as $jobapplication) {
            $jobapplication->delete();
            session()->flash('rejecteddeleted', "Cleared rejected Applications");
        }
        return response()->json([
            'jobid' => $request->jobid
        ]);
    }

    //job applicatant profile handler
    public function applicantProfile($jobid, $employeeid)
    {

        //first check if the employee belongs to the employer
        //jsko ni profile access garna milnu vaena
        $validtoaccess = JobApplication::where([
            'job_id' => $jobid,
            'user_id' => $employeeid,
            'employer_id' => Auth::id()
        ])->exists();

        if ($validtoaccess) {
            $applicant = User::where('id', $employeeid)->first();
            // if($applicant!=null){
            //     echo "yes";
            // }
            return view('front.account.job.applicantprofile', ['applicant' => $applicant, 'jID' => $jobid]);
        } else {
            abort(404);
        }
    }
}
