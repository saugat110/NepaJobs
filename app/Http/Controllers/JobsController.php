<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    //return jobs page, find jobs page
    public function index(){
        $categories = Category::where('status', 1) -> get();
        $jobtypes = JobType::where('status', 1) -> get();
        $jobs = Job::where('status', 1) -> orderBy('created_at', 'desc') -> paginate(6);

        return view('front.jobs',[
            'categories' => $categories,
            'jobtypes' => $jobtypes,
            'jobs' => $jobs,
        ]);
    }
}
