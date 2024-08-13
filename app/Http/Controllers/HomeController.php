<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //this method will show home page
    public function index(){
        // $categories = Category::where('status', 1) -> orderBy('name', 'asc') -> take(8) -> get();

        $categories = Category::withCount(['jobs as total_vacancies' => function ($query) {
            $query->select(DB::raw('SUM(vacancy)'));
        }])
        ->where('status', 1)
        ->orderBy('total_vacancies', 'desc')
        ->take(8)
        ->get();
        

        //for search
        $newcategories = Category::where('status', 1) -> orderBy('name', 'asc')->get();


        $featuredjobs  = Job::where([
            'status' => 1,
            'isFeatured' => 1,
        ])  -> orderby('created_at', 'desc')
            ->take(6) 
            -> get();
            
        $latestjobs = Job::where('status', 1) -> orderBy('created_at', 'desc') -> take(6) -> get();


        return view ('front.home',[
            'categories' => $categories,
            'featuredjobs' => $featuredjobs,
            'latestjobs' => $latestjobs,
            'newcategories' => $newcategories,
        ]);
    }

    
}
