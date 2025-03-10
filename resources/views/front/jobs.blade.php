@extends('front.layouts.app')

@section('main')
    <section class="section-3 py-5 bg-2 ">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-10 ">
                    <h2>Find Jobs</h2>
                </div>
                <div class="col-6 col-md-2">
                    <div class="align-end">
                        <select name="sort" id="sort" class="form-control form-control-sm">
                            <option {{ Request::get('sort')=='0'?'selected':'' }} value="0">Latest</option>
                            <option {{ Request::get('sort')=='1'?'selected':'' }} value="1">Oldest</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row pt-5">
                <div class="col-md-4 col-lg-3 sidebar mb-4">
                    <form action="" name="searchForm" id="searchForm">
                        <div class="card border-0 shadow p-4">
                            <div class="mb-4">
                                <h2>Keyword</h2>
                                <input type="text" value="{{ Request::get('keyword') }}" placeholder="Keyword" name="keyword" id="keyword" class="form-control">
                            </div>

                            <div class="mb-4">
                                <h2>Location</h2>
                                <input type="text" value="{{ Request::get('location') }}" name="location" id="location"  placeholder="Location" class="form-control">
                            </div>

                            <div class="mb-4">
                                <h2>Category</h2>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select a Category</option>
                                    @if ($categories)
                                        @foreach ($categories as $category)
                                            <option {{ Request::get('category')==$category->id?'selected':'' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-4">
                                <h2>Job Type</h2>
                                @if ($jobtypes)
                                    @foreach ($jobtypes as $jobtype)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input " name="jobType" type="checkbox"
                                                value="{{ $jobtype->id }}" id="job-type-{{ $jobtype->id }}"
                                                {{ in_array($jobtype->id, $jobTypeArray)?'checked':'' }}>
                                            <label class="form-check-label "
                                                for="job-type-{{ $jobtype->id }}">{{ $jobtype->name }}</label>
                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <div class="mb-4">
                                <h2>Experience</h2>
                                <select name="experience" id="experience" class="form-control">
                                    <option value="">Select Experience</option>
                                    <option value="1" {{ Request::get('experience')=="1"?'selected':'' }}>1 Year</option>
                                    <option value="2" {{ Request::get('experience')=="2"?'selected':'' }}>2 Years</option>
                                    <option value="3" {{ Request::get('experience')=="3"?'selected':'' }}>3 Years</option>
                                    <option value="4" {{ Request::get('experience')=="4"?'selected':'' }}>4 Years</option>
                                    <option value="5" {{ Request::get('experience')=="5"?'selected':'' }}>5 Years</option>
                                    <option value="6" {{ Request::get('experience')=="6"?'selected':'' }}>6 Years</option>
                                    <option value="7" {{ Request::get('experience')=="7"?'selected':'' }}>7 Years</option>
                                    <option value="8" {{ Request::get('experience')=="8"?'selected':'' }}>8 Years</option>
                                    <option value="9" {{ Request::get('experience')=="9"?'selected':'' }}>9 Years</option>
                                    <option value="10" {{ Request::get('experience')=="10"?'selected':'' }}>10 Years</option>
                                    <option value="10_plus" {{ Request::get('experience')=="10_plus"?'selected':'' }}>10+ Years</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('jobs') }}" class="btn btn-primary mt-3" >Reset</a>
                        </div>
                    </form>
                </div>



                <div class="col-md-8 col-lg-9 ">
                    <div class="job_listing_area">
                        <div class="job_lists">
                            <div class="row">
                                @if ($jobs->isNotEmpty())
                                    @foreach ($jobs as $job)
                                        <div class="col-md-4">
                                            <div class="card border-0 p-3 shadow mb-4">
                                                <div class="card-body">
                                                    <h3 class="border-0 fs-5 pb-2 mb-0">{{ $job->title }}</h3>
                                                    <p>{{ Str::limit($job->description, 40) }}</p>
                                                    <div class="bg-light p-3 border rounded">
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                            <span class="ps-1">{{ $job->location }}</span>
                                                        </p>
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                            <span class="ps-1">{{ $job->jobType->name }}</span>
                                                        </p>
                                                        @if (!is_null($job->salary))
                                                            <p class="mb-0">
                                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                                <span class="ps-1">{{ $job->salary }}</span>
                                                            </p>
                                                        @else
                                                            <p class="mb-0">
                                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                                <span class="ps-1">---</span>
                                                            </p>
                                                        @endif
                                                    </div>

                                                    <div class="mt-3">
                                                        <a href="{{ route('jobDetail', ['jobid' => $job->id]) }}" class="btn btn-primary btn-sm">View Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <h4><div class="col-md-12">Jobs Not Found</div><h4>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(Request::get('sort'))
                        {{ $jobs->appends(['sort' => Request::get('sort')])->links() }}
                    @else
                        {{ $jobs->links() }}
                    @endif
                </div>

            </div>
        </div>
    </section>
@endsection

@section('customJs')
<script>
    $('#searchForm').submit(function(e){
        e.preventDefault();

        var url = "{{ route('jobs') }}?";

        const keyword = $('#keyword').val();
        const location = $('#location').val();
        const category = $('#category').val();
        const experience = $('#experience').val();
        const checkedJobTypes = $("input[type='checkbox'][name='jobType']:checked").map(function(){
            return $(this).val();
        }).get();
        const sort = $('#sort').val();


        //if keyword is present
        if(keyword != ''){
            url += "&keyword=" + keyword;
        }

        //if location is present
        if(location != ''){
            url += "&location=" + location;
        }

        //if category is present
        if(category != ''){
            url += "&category=" + category;
        }

        //if experience is present
        if(experience != ''){
            url += "&experience=" + experience;
        }

        // console.log(checkedJobTypes);
        //if user has checked job types
        if(checkedJobTypes.length > 0){
            url += "&jobType=" + checkedJobTypes;
        }

        url += "&sort=" + sort;

        window.location.href=url;
    });

    $('#sort').change(function(){
        $('#searchForm').submit();
    });
</script>
@endsection
