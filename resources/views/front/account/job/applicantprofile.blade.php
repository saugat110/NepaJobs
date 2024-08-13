@extends('front.layouts.app')


@section('main')


<div class="container-fluid ms-3 mt-4">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('account.jobApplications',['jobid'=>$jID]) }}">Job Applications</a></li>
                    <li class="breadcrumb-item active">Applicant Profile</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


    <div class="container-fluid job_details_area mt-1 ps-0 pe-0">
        <div class="row pb-5 justify-content-center">
            <div class="col-md-8">
                @include('front.message')
                <div class="card shadow border-0 " style="min-height:80vh !important;">

                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                <div class="jobs_conetent ms-3 mt-4">
                                    @if ($applicant->image!=null && $applicant->image!='')
                                        <img src="{{ asset('/profilepic/thumb')}}/{{  $applicant->image }} " alt="avatar"  class="rounded-circle img-fluid" style="width: 110px;">
                                    @else
                                        <img src="{{ asset('assets/images/avatar7.png') }}" alt="avatar"  class="rounded-circle img-fluid" style="width: 110px;">
                                    @endif
                                    <a href="#" class="d-inline-block">
                                        <h4 class="mb-3 ms-3">Profile Details</h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="descript_wrap white-bg px-3 mt-2">
                        <div class="border-bottom mb-3"></div>
                        <div class="ps-4">
                            <p> Name:<span class="ps-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $applicant->name }}</span></p>
                            <p>Email:<span class="ps-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $applicant->email }}</span></p>
                            <p>Phone:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="ps-3">{{ $applicant->mobile }}</span></p>
                            <p>Designation:&nbsp;&nbsp;{{ $applicant -> designation }}</p>

                            {{-- forskills --}}
                            <div>
                                <p>Skills:</p>
                            </div>
                        </div>
                        <div class="pt-3 text-end"></div>
                    </div>
                </div>
            </div>
        </div>
@endsection



@section('customJs')
@endsection
