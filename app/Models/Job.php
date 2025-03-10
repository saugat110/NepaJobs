<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    public function jobType(){
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function applications(){
         return $this->hasMany(JobApplication::class, 'job_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
