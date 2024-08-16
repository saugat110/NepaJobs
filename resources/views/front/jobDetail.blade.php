@extends('front.layouts.app')

@section('main')
    <section class="section-4 bg-2">
        <div class="container pt-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left"
                                        aria-hidden="true"></i>
                                    &nbsp;Back to Jobs</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container job_details_area">
            <div class="row pb-5">
                <div class="col-md-8">
                    @include('front.message')
                    <div class="card shadow border-0">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between pb-3">
                                <div class="jobs_left d-flex align-items-center">

                                    <div class="jobs_conetent">
                                        <a href="#">
                                            <h3 class="fs-2">{{ $job->title }}</h3>
                                        </a>
                                        <div class="links_locat d-flex align-items-center">
                                            <div class="location">
                                                <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                            </div>
                                            <div class="location">
                                                <p> <i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                                            </div>
                                            @if (Auth::id() == $job->user_id)
                                                <div class="location">
                                                    <p> <i class="fa fa-user-o"></i> Posted by You</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="jobs_right">
                                    <div class="apply_now">
                                        @if (Auth::check() && (Auth::id()!=$job->user_id))
                                            <a class="heart_mark" onclick="saveJob({{ $job->id }})"> <i class="fa fa-heart-o"
                                            aria-hidden="true"></i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg ">
                            <div class="single_wrap">
                                <h4>Job description</h4>
                                <p style="white-space:pre-wrap;text-align:justify;">{{$job->description}}</p>
                            </div>
                            <div class="single_wrap">
                                @if (!empty($job->responsilibity))
                                    <h4>Responsibility</h4>
                                    <p style="white-space:pre-wrap;text-align:justify;">{{$job->responsilibity}}</p>
                                @endif
                            </div>
                            <div class="single_wrap">
                                @if (!empty($job->qualifications))
                                    <h4>Qualifications</h4>
                                    <p style="white-space:pre-wrap;text-align:justify;">{{$job->qualifications}}</p>
                                @endif
                            </div>
                            <div class="single_wrap" >
                                @if (!empty($job->benefits))
                                    <h4>Benefits</h4>
                                    <p style="white-space:pre-wrap;text-align:justify;">{{$job->benefits}}</p>
                                @endif
                            </div>
                            <div class="border-bottom"></div>
                            <div class="pt-3 text-end">

                                @if (Auth::check() && (Auth::id()!=$job->user_id))
                                    <a onclick="saveJob({{  $job->id }})" class="btn btn-secondary" id="sv-btn">Save</a>
                                @elseif(!Auth::check())
                                    <a class="btn btn-secondary disabled">Login to Save</a>
                                @endif

                                @if (Auth::check() && (Auth::id()!=$job->user_id))
                                    <a onclick="applyJob({{ $job->id }})" class="btn btn-primary"
                                        id="apply-btn">Apply</a>
                                @elseif(!Auth::check())
                                    <a href="#" class="btn btn-primary disabled">Login to Apply</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- added applications--}}
                    {{-- @if($jobapplications)
                    <div class="card shadow border-0 mt-3">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">
                                    <div class="jobs_conetent">
                                            <h4>Applicants</h4>
                                    </div>
                                </div>
                                <div class="jobs_right"></div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            <table class="table table-striped">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Applied Date</th>
                                </tr>
                                    @foreach ($jobapplications as $jobapplication)
                                        <tr>
                                            <td>{{ $jobapplication->user->name }}</td>
                                            <td>{{ $jobapplication->user->email }}</td>
                                            <td>{{ \Carbon\Carbon::parse($jobapplication->applied_data)->format('d M, Y') }}</td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                    @endif --}}
                    
                </div>

                <div class="col-md-4 mt-4 mt-md-0">
                    <div class="card shadow border-0">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Job Summary</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Published on:
                                        <span>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</span></li>
                                    <li>Vacancy: <span>{{ $job->vacancy }} Position</span></li>
                                    @if (!empty($job->salary))
                                        <li>Salary: <span>{{ $job->salary }}</span></li>
                                    @endif
                                    <li>Location: <span>{{ $job->location }}</span></li>
                                    <li>Job Nature: <span> {{ $job->jobType->name }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow border-0 my-4">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Company Details</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Name: <span>{{ $job->company_name }}</span></li>
                                    @if (!empty($job->company_location))
                                        <li>Location: <span>{{ $job->company_location }}</span></li>
                                    @endif
                                    @if (!empty($job->company_website))
                                        <li>Website: <span><a href="{{ $job->company_website }}"
                                                    target="_blank">{{ $job->company_website }}</a></span></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection








@section('customJs')
    <script>
        function applyJob(jobid) {
            if (confirm("Are u sure u want to apply?")) {
                var applyButton = document.getElementById('apply-btn');
                applyButton.innerHTML =
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Applying...`;
                applyButton.disabled = true;

                $.ajax({
                    url: '{{ route('jobApply') }}',
                    type: 'POST',
                    data: {
                        jobid: jobid
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = "{{ url()->current() }}";
                    }
                });
            }
        }

        function saveJob(jobid) {
                var svButton = document.getElementById('sv-btn');
                svButton.innerHTML =
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...`;
                svButton.disabled = true;

                $.ajax({
                    url: '{{ route("account.saveJob") }}',
                    type: 'POST',
                    data: {
                        jobid: jobid
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = "{{ url()->current() }}";
                    }
                });
        }
    </script>
@endsection
