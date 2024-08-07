<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class AccountController extends Controller
{
    //this will show user registration page/form
    public function registration(){
        return view('front.account.registration');
    }   

    //this will save the user and redirect to login page, ajax used
    public function processRegistration(Request $request){
        $validator = Validator::make($request -> all(),[
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:5',
            'confirm_password' => 'required|same:password'
        ]);

        if($validator -> passes()){
            $user = new User();
            $user -> name = $request -> name;
            $user -> email = $request -> email;
            $user -> password = $request -> password;
            $user -> save();

            session() -> flash('registrationsuccess', 'You have registered successfully');

            return response() -> json([
                'status' => true,
                'errors' => []
            ]);
        }else{
            return response() -> json([
                'status' => false,
                'errors' => $validator -> errors()
            ]);
        }
    }

    //this will show login page/form
    public function login(){
        return view('front.account.login');
    }

    //this will authenticate the user, no ajax
        //first validate input then authenticate
        //if authenticated route to profile page else to login page
    public function processLogin(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator -> passes()){
            if(Auth::attempt(['email' => $request->email, 'password' => $request-> password])){
                // echo "matched";
                return redirect() -> route('account.profile');
            //for login error redirect
            }else{
                return redirect() 
                    -> route('account.login') 
                    -> with('loginerror', 'Credentials Do Not Match, Try again')
                    -> withInput($request->only('email')); //with input for old('email'
            }
        //for validation error redirect
        }else{
            return redirect() -> route('account.login') 
                -> withErrors($validator) 
                -> withInput($request->only('email')); //with input for old('email')
        }
    }

    //this will show profile page
    public function profile(){
        $id = Auth::id();
        $user = User::find($id);

        return view('front.account.profile', ['user' => $user]);
    }   

    //update personal info in my profile
    public function updateProfile(Request $request){
        $id = Auth::id();
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:20',
            'email' =>  'required|email|unique:users,email,' .$id. 'id',
            'designation' => ['nullable', 'string', 'regex:/^[a-zA-Z\s]+$/','max:20'],
            'mobile' => 'nullable|numeric|digits:10'
        ]);

        if($validator -> passes()){
            $user = User::find($id);
            $user -> name = $request -> name;
            $user -> email = $request -> email;
            $user -> designation = $request -> designation;
            $user -> mobile = $request -> mobile;
            $user -> save();

            session() -> flash('updatedProfile', 'Profile Updated Successfully');

            return response() 
                    ->json([
                        'status' => true,
                        'errors' => []
                    ]);
        }else{
            return response() 
                    ->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ]);
        }
    }

    //update profile picture
    public function updateProfilePic(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        if($validator -> passes()){
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
            $image = $request -> image;
            $extension = $image -> extension();
            $imgName = Auth::id(). '.' . $extension;
            $succesfully_moved = $image -> move(public_path('/profilepic'), $imgName);

            //profilepic ma pic save vayo vne matra crop garne
            if($succesfully_moved){
                //create thumbnail and save in thumb
                $source_path = public_path('profilepic/'.$imgName);
                $manager = new ImageManager(Driver::class);
                $image2 = $manager->read($source_path);
                    //crop image
                    $image2 -> cover(150, 150);
                    $image2 -> save(public_path('profilepic/thumb/'.$imgName));
            }
                
            //save image name in DB 
            $user = User::find(Auth::id());
            $user -> image = $imgName;
            $user-> save();

            session() -> flash('pp_updated', "Profile Picture updated Succesfully.");
            return response() -> json(['status' => true]);
        }
    }

    

    //logout user and redirect to login page
    public function logout(){
        Auth::logout();
        return redirect() -> route('account.login');
    }

    

}
