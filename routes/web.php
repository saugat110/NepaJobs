<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\JobtypeController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use App\Mail\JobNotificationEmail;
use App\Models\Job;
use App\Models\User;
use Barryvdh\Debugbar\DataCollector\JobsCollector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


//PUBLIC routes
//returns home page
Route::get('/', [HomeController::class, 'index'])->name('home');

//returns jobs page, find jobs
Route::get('/jobs', [JobsController::class, 'index']) -> name('jobs');

//return job detail page
Route::get('/jobs/detail/{jobid}', [JobsController::class, 'jobDetail']) -> name('jobDetail');

//apply job
Route::post('job/apply', [JobsController::class, 'applyJob']) -> name('jobApply');

//forgot password form
Route::get('/forgotPassword', [AccountController::class, 'forgotPassword'])->name('forgotPassword');

//process forgotPassword
Route::post('/processForgotPassword', [AccountController::class, 'processForgotPassword']) ->name('processForgotPassword');

//reset password form link, form will only open when token is verified
Route::get('/reset-password/{token}/{email}', [AccountController::class, 'resetPassword']) ->name('resetPassword');

//process resetPassword
Route::post('/process-reset-password', [AccountController::class, 'processResetPassword']) ->name('processResetPassword');

// Route::get('/routes', function () {
//     //returns registration page
//     // Route::get('/account/register', [AccountController::class, 'registration']) -> name('account.registration');

//     //handles registration data
//     // Route::post('/account/process-register', [AccountController::class, 'processRegistration']) -> name('account.processRegistration');

//     //returns login page
//     // Route::get('/account/login', [AccountController::class, 'login']) -> name('account.login');

//     //handle login data and authenticate user
//     // Route::post('/account/process-login', [AccountController::class, 'processLogin']) -> name('account.processLogin');

//     //return user profile page
//     // Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');

//     //logout user and redirect to login page
//     // Route::get('/account/logout', [AccountController::class, 'logout'])->name('account.logout');
// });





//routes with prefix account
Route::prefix('/account')->group(function () {

    //Guest routes, prevent authenticated users from accessing these routes
    Route::middleware('guest')->group(function () {

        //returns registration page
        Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');

        //handles registration data
        Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');

        //returns login page
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');

        //handle login data and authenticate user
        Route::post('/process-login', [AccountController::class, 'processLogin'])->name('account.processLogin');
    });


    //authenticated routes
    Route::middleware('auth')->group(function () {
        //return user profile page
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');

        //update user profile info
        Route::put('/profile/update', [AccountController::class, 'updateProfile']) ->name('account.updateProfile');

        //update profile picture
        Route::post('/profile/updatePic', [AccountController::class, 'updateProfilePic']) -> name('account.updateProfilePic');

        //update password
        Route::put('/updatePassword', [AccountController::class, 'changePassword']) -> name('account.changePassword');

        //return create job page
        Route::get('/create-job', [AccountController::class, 'createJobs']) -> name('account.createJob');

        //process form of create-job page
        Route::post('/process-createJob', [AccountController::class, 'processCreateJobs']) -> name('account.processCreateJob');

        //return myjobs page
        Route::get('/my-jobs', [AccountController::class, 'myJobs']) -> name('account.myJobs');

        //return edit jobs page
        Route::get('/my-jobs/edit/{job_id}', [AccountController::class, 'editJob']) -> name('account.editJob');

        //process edit job form data
        Route::put('/process-editJob/{job_id}', [AccountController::class,'processEditJob']) -> name('account.processEditJob');

        //delete job, ajax, redirect to myjobs page
        Route::post('/delete-job', [AccountController::class, 'deleteJob']) ->name('account.deleteJob');

        //view my applied jobs page
        Route::get('/my-applied-jobs', [AccountController::class, 'myappliedjobs']) -> name('account.jobsApplied');

        //unapply job
        Route::post('/job/unapply', [AccountController::class, 'unapplyjob']) ->name('account.jobUnapply');

        //save job
        Route::post('/job/save', [JobsController::class, 'saveJob'])->name('account.saveJob');

        //return saved jobs page
        Route::get('/saved-jobs', [AccountController::class, 'savedJobs']) -> name('account.savedJobs');

        //unsave job
        Route::post('/job/unsave', [AccountController::class, 'unSave']) ->name('account.jobunSave');

        //get job applications for a job
        Route::get('job/applications/{jobid}', [JobsController::class, 'jobApplications']) ->name('account.jobApplications');

        //job application handler, accept, reject, pending
        Route::post('/job/applicationhandler/', [JobsController::class, 'applicationHandler']) -> name('account.applicationHandler');

        //job applicant profile
        Route::get('/job/applicantProfile/{jobid}/{employeeid}/', [JobsController::class, 'applicantProfile']) -> name('account.applicantProfile');

        //delete rejected job applications
        Route::post('job/remove-rejected/',[JobsController::class, 'removeRejectedApplications']) -> name('account.removeRejectedApplications');

        //logout user and redirect to login page
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
    });

});


//admin routes
Route::prefix('admin')->group(function(){

    Route::middleware('auth', 'checkAdmin')->group(function(){

        //returns admin dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.Dashboard');



        //get all users
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.list');

        //enable, disable user
        Route::put('/update-user-status',[UserController::class, 'userStateManage'])->name('admin.updateUserStatus');

        //delete the user
        Route::post('/delete-user', [UserController::class, 'deleteUser']) ->name('admin.deleteUser');



        //list all jobs
        Route::get('/jobs', [JobController::class, 'index']) -> name('admin.jobs');

        //feature , unfeature job
        Route::put('/jobs/feature', [JobController::class, 'jobFeatureManage']) -> name('admin.jobFeatureManage');

        //job status manage
        Route::put('/job/status', [JobController::class, 'jobStatus']) -> name('admin.jobStatus');

        //delete job
        Route::delete('/job/delete', [JobController::class, 'deleteJob']) ->name('admin.deleteJob');


        //list categories
        Route::get('/categories', [CategoryController::class, 'index']) -> name('admin.categories');

        //add category
        Route::post('/add/category', [CategoryController::class, 'addCategory']) ->name('admin.addCategory');

        //category status manage
        Route::post('/category/status', [CategoryController::class, 'categoryStatus'])->name('admin.categoryStatus');

        //delete category
        Route::delete('/category/delete', [CategoryController::class, 'deleteCategory']) ->name('admin.deleteCategory');



         //list jobtypes
         Route::get('/jobtypes', [JobtypeController::class, 'index']) -> name('admin.jobTypes');

         //add jobtype
         Route::post('/add/jobtype', [JobtypeController::class, 'addjobType']) ->name('admin.addjobType');
 
         //jobtype status manage
         Route::post('/jobtype/status', [JobtypeController::class, 'jobTypeStatus'])->name('admin.jobTypeStatus');
 
         //delete jobtype
         Route::delete('/jobtype/delete', [JobtypeController::class, 'deletejobType']) ->name('admin.deletejobType');

         //view user profile
         Route::get('/userprofile/{id}', [UserController::class, 'viewProfile']) -> name('admin.viewUserProfile');
    });
});


Route::get('/test',function(){
    echo public_path('profilepic');
});

Route::get('/test2',function(){
        $job = Job::find(23);
        $employer = User::find(1);
        $mailData = [
            'job' => $job,
            'employer' => $employer,
            'employee' => 4,
        ];
    Mail::to("saugatsthapit3@gmail.com") -> send(new JobNotificationEmail($mailData));

});

Route::get('/test3', function(){
    echo "<pre>";
    print_r(session()->all());
    echo "</pre>";
});

Route::get('/test4', function(){
    $user = User::find(21);
    $user->status = 1;
    $user->save();
    echo "hi";
});





