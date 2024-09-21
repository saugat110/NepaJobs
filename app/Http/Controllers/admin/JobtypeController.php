<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobTypeController extends Controller
{
    //list all jobtypes
    public function index(){
        $jobtypes = JobType::orderBy('id', 'desc') -> paginate(5);
        return view('admin.jobtype.list') -> with(['jobtypes'=>$jobtypes]);
    }

    //add jobtype
    public function addjobType(Request $request){
        $validator = Validator::make($request -> all(), [
            'jobtype_name' => 'required|string|max:20|regex:/^[a-zA-Z\s]+$/|unique:job_types,name',            
        ]);

        if($validator -> passes()){
            $jobtype = new JobType();
            $jobtype->name = $request->jobtype_name;
            $jobtype->save();
            session() -> flash("jobtypeAdded", 'JobType Added Successfully');
            return response() -> json([
                'status' => true
            ]);
        }else{
            return response() -> json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    //jobtype enable, disable
    public function jobTypeStatus(Request $request){
        $jobtype = JobType::find($request->id);
        if($jobtype){
            if($request -> status == 'enable'){
                $jobtype -> status = 1;
                $jobtype -> save();
                session() -> flash("jobtypeStatus", "JobType enabled Successfully");
                return response() -> json([
                    'status' => true
                ]);
            }else{
                $jobtype -> status = 0;
                $jobtype -> save();
                session() -> flash("jobtypeStatus", "JobType disabled Successfully");
                return response() -> json([
                    'status' => true
                ]);
            }
        }else{
            return response() -> json([
                'status' => false,
                'error' => "jobtype not found"
            ]);
        }
    }

    //delete jobtype
    public function deletejobType(Request $request){
        $jobtype = JobType::find($request->id);
        if($jobtype){
            $jobtype -> delete();
            session() -> flash("jobtypeDeleted", 'JobType deleted successfully');
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
