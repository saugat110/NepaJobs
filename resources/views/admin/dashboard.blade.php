@extends('front.layouts.app')

@push('head')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')
                <div class="card border-0 shadow mb-4">
                   <div class="card-body">
                        <p style="font-weight: 400 !important;" id="admin_title">Welcome Admin</p>
                        <div class="border-bottom mb-3 mt-3"></div>
                        {{-- <p>hi</p> --}}
                        {{-- <div class="row justify-content-center dash_item gy-3 text-center">
                            <div class="col-10 col-md-5 me-2">Users: {{ App\Models\User::count() }}</div>
                            <div class="col-10 col-md-5">Jobs: {{ App\Models\Job::count() }}</div>
                            <div class="col-10  col-md-5 me-2">Job Applications: {{ App\Models\JobApplication::count() }}</div>
                            <div class="col-10 col-md-5">Job Types: {{ App\Models\JobType::count() }}</div>
                            <div class="col-10 col-md-5">Categories: {{ App\Models\Category::count() }}</div>
                        </div> --}}
                        <div class="container my-4">
                            <div class="row justify-content-center gy-3 text-center">
                                <!-- Users Card -->
                                <div class="col-10 col-md-5 col-lg-3">
                                    <div class="card shadow-sm border-primary">
                                        <div class="card-body">
                                            <h7 class="card-title"><i class="bi bi-person"></i> Users</h7>
                                            <p class="card-text display-4">{{ App\Models\User::count() }}</p>
                                        </div>
                                    </div>
                                </div>
                        
                                <!-- Jobs Card -->
                                <div class="col-10 col-md-5 col-lg-3">
                                    <div class="card shadow-sm border-success">
                                        <div class="card-body">
                                            <h7 class="card-title"><i class="bi bi-briefcase"></i> Jobs</h7>
                                            <p class="card-text display-4">{{ App\Models\Job::count() }}</p>
                                        </div>
                                    </div>
                                </div>
                        
                               
                        
                                <!-- Job Types Card -->
                                <div class="col-10 col-md-5 col-lg-3">
                                    <div class="card shadow-sm border-info">
                                        <div class="card-body">
                                            <h7 class="card-title"><i class="bi bi-tags"></i> Job Types</h7>
                                            <p class="card-text display-4">{{ App\Models\JobType::count() }}</p>
                                        </div>
                                    </div>
                                </div>
                        
                                <!-- Categories Card -->
                                <div class="col-10 col-md-5 col-lg-3">
                                    <div class="card shadow-sm border-secondary">
                                        <div class="card-body">
                                            <h7 class="card-title"><i class="bi bi-folder"></i> Categories</h7>
                                            <p class="card-text display-4">{{ App\Models\Category::count() }}</p>
                                        </div>
                                    </div>
                                </div>

                                 <!-- Job Applications Card -->
                                 <div class="col-10 col-md-5 col-lg-3">
                                    <div class="card shadow-sm border-warning">
                                        <div class="card-body">
                                            <h7 class="card-title"><i class="bi bi-file-earmark-text"></i> Job Applications</h7>
                                            <p class="card-text display-4">{{ App\Models\JobApplication::count() }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                   </div>
                </div>                          
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
@endsection