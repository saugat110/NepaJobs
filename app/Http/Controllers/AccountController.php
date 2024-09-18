<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class AccountController extends Controller
{
    //this will show user registration page/form
    public function registration()
    {
        return view('front.account.registration');
    }

    //this will save the user and redirect to login page, ajax used
    public function processRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|alpha',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:5',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();

            session()->flash('registrationsuccess', 'You have registered successfully');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    //this will show login page/form
    public function login()
    {
        return view('front.account.login');
    }

    //this will authenticate the user, no ajax
    //first validate input then authenticate
    //if authenticated route to profile page else to login page
    public function processLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // if($validator -> passes()){
        //     if(Auth::attempt(['email' => $request->email, 'password' => $request-> password])){
        //         // echo "matched";
        //         return redirect() -> route('account.profile');
        //     //for login error redirect
        //     }else{
        //         session() -> flash('loginerror', 'Invalid email or password');
        //         return redirect() 
        //             -> route('account.login') 
        //             -> withInput($request->only('email')); //with input for old('email'
        //     }
        // //for validation error redirect
        // }else{
        //     return redirect() -> route('account.login') 
        //         -> withErrors($validator) 
        //         -> withInput($request->only('email')); //with input for old('email')
        // }

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // echo "matched";
                if (Auth::user()->status == 1) {
                    return redirect()->route('account.profile');
                    //if user is blocked redirect to login page, status = 0
                } else {
                    Auth::logout();
                    session()->flash('loginerror', 'Your account is currently disabled');
                    return redirect()->route('account.login')->withInput($request->only('email'));
                }
                //for login error redirect
            } else {
                session()->flash('loginerror', 'Invalid email or password');
                return redirect()
                    ->route('account.login')
                    ->withInput($request->only('email')); //with input for old('email')
            }
            //for validation error redirect
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email')); //with input for old('email')
        }
    }

    //this will show profile page
    public function profile()
    {
        $id = Auth::id();
        $user = User::find($id);

        return view('front.account.profile', ['user' => $user]);
    }

    //update personal info in my profile
    public function updateProfile(Request $request)
    {
        $id = Auth::id();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'email' =>  'required|email|unique:users,email,' . $id . 'id',
            'designation' => ['nullable', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:20'],
            'mobile' => 'nullable|numeric|digits:10'
        ]);

        if ($validator->passes()) {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->save();

            session()->flash('updatedProfile', 'Profile Updated Successfully');

            return response()
                ->json([
                    'status' => true,
                    'errors' => []
                ]);
        } else {
            return response()
                ->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
        }
    }

    //change password
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validator->passes()) {
            if (Hash::check($request->old_password, Auth::user()->password)) {
                $user = User::find(Auth::id());
                $user->password = Hash::make($request->new_password);
                $user->save();
                session()->flash('passwordChanged', 'Password Changed Successfully');
                return response()->json([
                    'status' => true
                ]);
            } else {
                session()->flash('passwordChangeError', "Old Password doesn't Match");
                return response()->json([
                    'status' => true
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    //update profile picture
    public function updateProfilePic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        if ($validator->passes()) {
            //delete previous picture
            $imagePath = public_path('profilepic/' . Auth::user()->image);
            $thumbImagePath = public_path('profilepic/thumb/' . Auth::user()->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            if (File::exists($thumbImagePath)) {
                File::delete($thumbImagePath);
            }
            //save new picture
            $image = $request->image;
            $extension = $image->extension();
            $imgName = Auth::id() . '.' . $extension;
            $succesfully_moved = $image->move(public_path('/profilepic'), $imgName);

            //profilepic ma pic save vayo vne matra crop garne
            if ($succesfully_moved) {
                //create thumbnail and save in thumb, package used image intervention
                $source_path = public_path('profilepic/' . $imgName);
                $manager = new ImageManager(Driver::class);
                $image2 = $manager->read($source_path);
                //crop image
                $image2->cover(150, 150);
                $image2->save(public_path('profilepic/thumb/' . $imgName));
            }

            //save image name in DB 
            $user = User::find(Auth::id());
            $user->image = $imgName;
            $user->save();

            session()->flash('pp_updated', "Profile Picture updated Succesfully.");
            return response()->json(['status' => true]);
        }
    }

    //return create jobs page
    public function createJobs(Request $request)
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobtypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();
        return view('front.account.job.create')
            ->with(compact('categories', 'jobtypes'));
    }

    //process create jobs form, save data
    public function processCreateJobs(Request $request)
    {
        $rules = [
            'title' => 'required|min:5|max:60',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:5|max:50',
            'experience' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $job = new Job();
            $job->title  = $request->title;
            $job->category_id  = $request->category;
            $job->job_type_id  = $request->jobType;
            $job->user_id  = Auth::id();
            $job->vacancy  = $request->vacancy;
            $job->salary  = $request->salary;
            $job->location  = $request->location;
            $job->description  = $request->description;
            $job->benefits  = $request->benefits;
            $job->responsilibity  = $request->responsibility;
            $job->qualifications  = $request->qualifications;
            $job->keywords  = $request->keywords;
            $job->experience  = $request->experience;
            $job->company_name  = $request->company_name;
            $job->company_location  = $request->company_location;
            $job->company_website  = $request->company_website;
            $job->save();
            session()->flash('jobsadded', 'Job Created Successfully.');

            return response()
                ->json([
                    'status' => true,
                    'errors' => []
                ]);
        } else {
            return response()
                ->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
        }
    }

    //return myjobs page
    public function myJobs()
    {
        $myjobs = Job::where('user_id', Auth::id())->with('jobType')->orderBy('id', 'desc')->paginate(5);
        // dd($myjobs);
        return view('front.account.job.my-jobs', [
            'myjobs' => $myjobs
        ]);
    }

    //return edit jobs page
    public function editJob(Request $request, $job_id)
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobtypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();

        // dd($job_id);
        $job = Job::where([
            'id' => $job_id,
            'user_id' => Auth::id()
        ])->first();

        //maile mero job matra edit garna milnu paryo
        if ($job == null) {
            abort(404);
        }

        return view('front.account.job.editjob', [
            'categories' => $categories,
            'jobtypes' => $jobtypes,
            'job' => $job
        ]);
    }

    //process edit job data, save data
    public function processEditJob(Request $request, $job_id)
    {
        $rules = [
            'title' => 'required|min:5|max:60',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:5|max:50',
            'experience' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $job = Job::find($job_id);
            $job->title  = $request->title;
            $job->category_id  = $request->category;
            $job->job_type_id  = $request->jobType;
            // $job->user_id  = Auth::id();
            $job->vacancy  = $request->vacancy;
            $job->salary  = $request->salary;
            $job->location  = $request->location;
            $job->description  = $request->description;
            $job->benefits  = $request->benefits;
            $job->responsilibity  = $request->responsibility;
            $job->qualifications  = $request->qualifications;
            $job->keywords  = $request->keywords;
            $job->experience  = $request->experience;
            $job->company_name  = $request->company_name;
            $job->company_location  = $request->company_location;
            $job->company_website  = $request->company_website;
            $job->status = $request->status;
            $job->save();
            session()->flash('jobUpdated', 'Job Updated Successfully.');

            //if status of job=0,)(vacancy banda) then saved jobs bata ni hatune
            if ($request->status == 0) {
                $savedJobs = SavedJob::where('job_id', $job_id)->get();
                if ($savedJobs) {
                    foreach ($savedJobs as $savedJob) {
                        $savedJob->delete();
                    }
                }
            }

            return response()
                ->json([
                    'status' => true,
                    'errors' => []
                ]);
        } else {
            return response()
                ->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
        }
    }

    //delete job and redirect to myjobs page
    public function deleteJob(Request $request)
    {
        $job_id = $request->job_id;
        $job = Job::where([
            'id' => $job_id,
            'user_id' => Auth::id()
        ])->first();

        if ($job == null) {
            session()->flash('jobnotdeleted', "Job Not Found.");
            return response()->json(['status' => false]);
        } else {
            $job->delete();
            session()->flash('jobdeleted', "Job Deleted Successfully.");
            return response()->json(['status' => true]);
        }
    }

    //view myapplied jobs page
    public function myappliedjobs()
    {
        $jobapps = JobApplication::where('user_id', Auth::id())->with('job')->paginate(4);

        return view('front.account.job.myapplied-jobs', [
            'jobapps' => $jobapps
        ]);
    }

    //unapply job
    public function unapplyjob(Request $request)
    {
        $jobapplied = JobApplication::where([
            'id' => $request->jobapp_id,
            'user_id' => Auth::id()
        ]);

        $jobapplied->delete();
        session()->flash('jobunapplysuccess', "Job Application Withdrawed Successfully.");
        return response()->json(['status' => true]);
    }

    //return saved jobs page
    public function savedJobs()
    {
        // $savedjobs = SavedJob::where([
        //     'user_id' => Auth::id()
        // ])-> orderBy('created_at', 'desc') -> paginate(4);

        //job ko owner disable, xa vne tyo user ko jobs lai  saved jobs ma na dekhaune
        $savedjobs = SavedJob::where('user_id', Auth::id())
            ->whereHas('job.user', function ($query) {
                $query->where('status', 1);
            })->orderBy('created_at', 'desc')->paginate(4);

        return view('front.account.job.saved-jobs', [
            'savedjobs' => $savedjobs
        ]);
    }

    //unsave job
    public function unSave(Request $request)
    {
        $savedjob = SavedJob::where([
            'id' => $request->savedjob_id,
            'user_id' => Auth::id()
        ]);

        $savedjob->delete();
        session()->flash('jobunsavesuccess', "Job removed Successfully.");
        return response()->json(['status' => true]);
    }

    //return forgot password form
    public function forgotPassword()
    {
        return view('front.account.forgot_password');
    }

    //process forgotPassword data
    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->passes()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            //generate token
            $token = Str::random(10);

            //insert token in db
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]);

            //send mail
            $user = User::where('email', $request->email)->first();
            $mailData = [
                'email' => $request->email,
                'token' => $token,
                'user' => $user
            ];
            Mail::to($request->email)->send(new ResetPasswordEmail($mailData));
            return redirect()->route('forgotPassword')->with("resetEmail", "Please check your email");
        } else {
            return redirect()
                ->route('forgotPassword')
                ->withInput()
                ->withErrors($validator);
        }
    }

    //verify token from email link then show reset password form
    public function resetPassword($token, $email)
    {
        $token_found = DB::table("password_reset_tokens")->where('email', $email)->where('token', $token)->first();
        if ($token_found != null) {
            return view('front.account.resetPassword')->with(['token' => $token, 'email' => $email]);
        } else {
            return redirect()->route('forgotPassword')->with("tokenMismatch", "Invalid Token");
        }
    }

    //process resetPassword form
    public function processResetPassword(Request $request)
    {
        $token_found = DB::table("password_reset_tokens")->where('email', $request->email)->where('token', $request->token)->first();
        if ($token_found == null) {
            return redirect()->route('forgotPassword')->with("tokenMismatch", "Invalid Token");
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:5',
            'confirm_password' => 'nullable|required_with:new_password|min:5|same:new_password'
        ]);

        if ($validator->passes()) {
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->new_password);
            $user->save();

            $token = Str::random(10);
            // reset the token for the given email
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->update([
                    'token' => $token,
                    'created_at' => now()
                ]);

            return redirect()->route('account.login')->with("resetSuccess", "Password reset successfully");
        } else {
            return redirect()->route('resetPassword', ['token' => $request->token, 'email' => $request->email])->withErrors($validator);
        }
    }

    //logout user and redirect to login page
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }
}
