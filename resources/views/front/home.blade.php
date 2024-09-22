@extends('front.layouts.app')

@section('main')

    {{-- hero section  --}}
    <section class="section-0 lazy d-flex bg-image-style dark align-items-center " class=""
        data-bg="{{ asset('assets/images/banner7.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12 col-xl-8">
                    <h1>Land your dream career</h1>
                    <p>Your best job partner.</p>
                    <div class="banner-btn mt-5"><a href="{{ route('jobs') }}" class="btn btn-primary mb-4 mb-sm-0">Explore Now</a></div>
                </div>
            </div>
        </div>
    </section>

    {{-- select a category -- SEARCH --}}
    <section class="section-1 py-5 ">
        <div class="container">
            <div class="card border-0 shadow p-5">
                <form action="{{ route('jobs') }}" method="GET">
                    <div class="row">

                        <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                            <input type="text" class="form-control"  id="keyword" name="keyword"
                                placeholder="Keywords">
                        </div>

                        <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                            <input type="text" class="form-control" name="location" id="location"
                                placeholder="Location">
                        </div>
                        
                        <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                            <select name="category" id="category" class="form-control">
                                <option value="">Select a Category</option>
                                @if ($newcategories->isNotEmpty())
                                    @foreach ($newcategories as $newcategory)
                                        <option value="{{ $newcategory->id }}">{{ $newcategory->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class=" col-md-3 mb-xs-3 mb-sm-3 mb-lg-0">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-block">Search</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>


    {{--  popular categories --}}
    <section class="section-2 bg-2 py-5">
        <div class="container">
            <h2>Popular Categories</h2>
            <div class="row pt-5">
                @if ($categories->isNotEmpty())
                    @foreach ($categories as $category)
                        <div class="col-lg-4 col-xl-3 col-md-6 text-center">
                            <div class="single_catagory rounded-pill">
                                <a href="{{ route('jobs') }}?category={{ $category->id }}">
                                    <h4 class="pb-2">{{ $category->name }}</h4>
                                </a>
                                <p class="mb-0"> <span>{{ $category->total_positions}}</span> Available positions</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>


    {{-- Featured Jobs --}}
    <section class="section-3  py-5">
        <div class="container">
            <h2>Featured Jobs</h2>
            <div class="row pt-5">
                <div class="job_listing_area">
                    <div class="job_lists">
                        <div class="row">
                            @if ($featuredjobs->isNotEmpty())
                                @foreach ($featuredjobs as $fjob)
                                    <div class="col-md-4">
                                        <div class="card border-0 p-3 shadow mb-4 rounded-lg">
                                            <div class="card-body">
                                                <h3 class="border-0 fs-5 pb-2 mb-0">{{ $fjob->title }}</h3>
                                                <p>{{ Str::words($fjob->description, 5) }}</p>
                                                <div class="bg-light p-3 border rounded">
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                        <span class="ps-1">{{ $fjob->location }}</span>
                                                    </p>
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                        <span class="ps-1">{{ $fjob->jobType->name }}</span>
                                                    </p>
                                                    @if (!is_null($fjob->salary))
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                            <span class="ps-1">{{ $fjob->salary }}</span>
                                                        </p>
                                                    @else
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                            <span class="ps-1">---</span>
                                                        </p>
                                                    @endif

                                                </div>

                                                <div class="mt-3">
                                                    <a href="{{ route('jobDetail', ['jobid' => $fjob->id]) }}"
                                                        class="btn btn-primary btn-sm">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- Latest Jobs --}}
    <section class="section-3 bg-2 py-5">
        <div class="container">
            <h2>Latest Jobs</h2>
            <div class="row pt-5">
                <div class="job_listing_area">
                    <div class="job_lists">
                        <div class="row">
                            @if ($latestjobs->isNotEmpty())
                                @foreach ($latestjobs as $ljob)
                                    <div class="col-md-4">
                                        <div class="card border-0 p-3 shadow mb-4">
                                            <div class="card-body">
                                                <h3 class="border-0 fs-5 pb-2 mb-0">{{ $ljob->title }}</h3>
                                                <p>{{ Str::words($ljob->description, 5) }}</p>
                                                <div class="bg-light p-3 border rounded">
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                        <span class="ps-1">{{ $ljob->location }}</span>
                                                    </p>
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                        <span class="ps-1">{{ $ljob->jobType->name }}</span>
                                                    </p>
                                                    @if (!is_null($ljob->salary))
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                            <span class="ps-1">{{ $ljob->salary }}</span>
                                                        </p>
                                                    @else
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                            <span class="ps-1">---</span>
                                                        </p>
                                                    @endif

                                                </div>

                                                <div class=" mt-3">
                                                    <a href="{{ route('jobDetail', ['jobid' => $ljob->id]) }}"
                                                        class="btn btn-primary btn-sm">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
