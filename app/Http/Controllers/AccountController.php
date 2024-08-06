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

            session() -> flash('success', 'You have registered successfully');

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

    //this will authenticate the user
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
        return view('front.account.profile');
    }   

    //logout user and redirect to login page
    public function logout(){
        Auth::logout();
        return redirect() -> route('account.login');
    }


}
