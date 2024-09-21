<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //list all categories
    public function index(){
        $categories = Category::orderBy('id', 'desc') -> paginate(5);
        return view('admin.category.list') -> with(['categories'=>$categories]);
    }

    //add category
    public function addCategory(Request $request){
        $validator = Validator::make($request -> all(), [
            'category_name' => 'required|string|max:20|regex:/^[a-zA-Z\s]+$/|unique:categories,name',            
        ]);

        if($validator -> passes()){
            $category = new Category();
            $category->name = $request->category_name;
            $category->save();
            session() -> flash("categoryAdded", 'Category Added Successfully');
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

    //category enable, disable
    public function categoryStatus(Request $request){
        $category = Category::find($request->id);
        if($category){
            if($request -> status == 'enable'){
                $category -> status = 1;
                $category -> save();
                session() -> flash("categoryStatus", "Category enabled Successfully");
                return response() -> json([
                    'status' => true
                ]);
            }else{
                $category -> status = 0;
                $category -> save();
                session() -> flash("categoryStatus", "Category disabled Successfully");
                return response() -> json([
                    'status' => true
                ]);
            }
        }else{
            return response() -> json([
                'status' => false,
                'error' => "category not found"
            ]);
        }
    }

    //delete category
    public function deleteCategory(Request $request){
        $category = Category::find($request->id);
        if($category){
            $category -> delete();
            session() -> flash("categoryDeleted", 'Category deleted successfully');
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
