<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            }else{
                return redirect() 
                    -> route('account.login') 
                    -> with('loginerror', 'Credentials Do Not Match, Try again')
                    -> withInput($request->only('email'));
            }
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

    //
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

    //logout user and redirect to login page
    public function logout(){
        Auth::logout();
        return redirect() -> route('account.login');
    }

    

}
