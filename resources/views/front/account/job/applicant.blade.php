@extends('front.layouts.app')


@section('main')
<div class="container-fluid ms-3 mt-4">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('account.myJobs') }}">My Jobs</a></li>
                    <li class="breadcrumb-item active">Job Applications</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
    <div class="container-fluid job_details_area mt-3 ps-0 pe-0">
        <div class="row pb-5 justify-content-center">
            <div class="col-md-8">
                @include('front.message')
                <div class="card shadow border-0 " style="min-height:80vh !important;">

                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                <div class="jobs_conetent ms-3 mt-4">
                                    <a href="#">
                                        <h4 class="mb-3">Job Applications</h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="descript_wrap white-bg px-3 mt-1">
                        <div class="border-bottom mb-3"></div>
                        <table class="table ">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Applied Date</th>
                                <th>Action</th>
                            </tr>
                            @if ($jobapplications->count() > 0)
                                @foreach ($jobapplications as $jobapplication)
                                    <tr @if($jobapplication->application_status == 1) style="color:limegreen !important;" @elseif($jobapplication->application_status == -1) style="color:#e68781 !important;" @endif>
                                        <td>
                                            <a href="{{ route('account.applicantProfile',['jobid'=>$jobapplication->job_id,'employeeid'=>$jobapplication->user_id]) }}"> {{ $jobapplication->user->name }} </a>
                                        </td>
                                        <td>{{ $jobapplication->user->email }}</td>
                                        <td>{{ \Carbon\Carbon::parse($jobapplication->created_at)->format('d M, Y') }}
                                        </td>
                                        
                                        <td>
                                            <div class="action-dots">
                                                <button href="#" class="btn" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @if ($jobapplication->application_status != 1)
                                                        <li><a  class="dropdown-item" onclick="accept({{ $jobapplication->job->id }}, {{ $jobapplication->user->id }})" > <i class="fa fa-eye"
                                                        aria-hidden="true"></i>
                                                        Accept</a></li>
                                                    @endif
                                                    
                                                    @if ($jobapplication->application_status !=-1)
                                                        <li><a class="dropdown-item" onclick="reject({{ $jobapplication->job->id }}, {{ $jobapplication->user->id }})" > <i class="fa fa-eye"
                                                        aria-hidden="true"></i>
                                                        Reject</a></li>
                                                    @endif
                                                    
                                                </ul>

                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No applications Found.</td>
                                </tr>
                            @endif
                        </table>
                        {{ $jobapplications->links() }}

                        @if ($jobapplications-> where('application_status', -1) -> count() > 0)
                            <div class="pt-3 text-end">
                                <button class="btn btn-danger btn-sm  py-1" onclick="removeRejected({{ $jid }})" id="rejectedbtn">Clear Rejected</button>
                            </div>
                        @endif
                        
                    </div>
                </div>
            </div>


            {{-- <div class="col-md-8 mt-2">
                <button class="btn btn-danger btn-sm px-1 py-2">Remove Rejected</button>
            </div> --}}
        </div>
    @endsection



    @section('customJs')
    @if($jobapplications->count() > 0)
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        function accept(jobid, userid){
            $.ajax({
                url: "{{ route('account.applicationHandler') }}",
                type: 'POST',
                data: {jobid:jobid, userid:userid, status:1},
                dataType:'json',
                success: function(response){
                    console.log(response.status);
                    if(urlParams.has('page')){
                        window.location.href = "{{ url()->current() }}?page={{ Request::get('page') }}";
                    }else{
                        window.location.href = "{{ url()->current() }}";
                    }

                }
            });
        }

        function reject(jobid, userid){
            $.ajax({
                url: "{{ route('account.applicationHandler') }}", 
                type: 'POST',
                data: {jobid:jobid, userid:userid, status:-1},
                dataType:'json',
                success: function(response){
                    console.log(response.status);

                    if(urlParams.has('page')){
                        window.location.href = "{{ url()->current() }}?page={{ Request::get('page') }}";
                    }else{
                        window.location.href = "{{ url()->current() }}";
                    }
                }
            });
        }

        function removeRejected(jid){
            console.log('fjalk;df');
            $.ajax({
                url: "{{ route('account.removeRejectedApplications') }}", 
                type: 'POST',
                data: {jobid: jid},
                dataType:'json',
                success: function(response){
                    console.log(response.jobid);
                    if(urlParams.has('page')){
                        window.location.href = "{{ url()->current() }}?page={{ Request::get('page') }}";
                    }else{
                        window.location.href = "{{ url()->current() }}";
                    }
                }
            });
        }
    </script>
    @endif
    @endsection
