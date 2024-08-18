<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //return all users except the admin
    public function index(){
        $users = User::where("id", "!=", Auth::id()) -> orderBy('created_at', 'desc')->paginate(3);
        return view('admin.user.list',['users'=>$users]);
    }

    //
    public function userStateManage(Request $request){
        if($request->status == 'active'){
            $user = User::find($request->userid);
            if(!empty($user)){
                session() -> flash('userStateManage', "User status changed to Active.");
                $user->status = 1;
                $user->save();
                return response() -> json(['status'=>true]);
            }else{
                session() -> flash('userStateError', "User not Found");
                return response() -> json(['status'=>false]);
            }
        }

        if($request->status == 'block'){
            $user = User::find($request->userid);
            if(!empty($user)){
                session() -> flash('userStateManage', "User status changed to Blocked.");
                $user->status = 0;
                $user->save();
                return response() -> json(['status'=>true]);
            }else{
                session() -> flash('userStateError', "User not Found");
                return response() -> json(['status'=>false]);
            }
        }
    }

    //
    public function deleteUser(Request $request){
        if(Auth::user() -> role == 'admin'){
            $user = User::find($request->userid);
            if(!empty($user)){
                $user->delete();
                session() -> flash('deletedUser', "User deleted Successfully.");
                return response() -> json(['status'=>'deleted']);
            }else{
                return response() -> json(['status'=>"User doesn't exist"]);
            }
        }else{
            return response() -> json(['status'=>"Not authorized to delete user"]);
        }
    }


}
