@extends('front.layouts.app')


@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">

            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">My Jobs</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">

                {{-- sidebar --}}
                <div class="col-lg-3">
                    @include('front.account.sidebar')
                </div>

                {{--  --}}
                <div class="col-lg-9">
                    {{-- for sucess error alert messages --}}
                    @include('front.message')
                    <div class="card border-0 shadow mb-4 p-3">
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1">My Jobs</h3>
                                </div>
                                <div style="margin-top: -10px;">
                                    <a href="{{ route('account.createJob') }}" class="btn btn-primary">Post a Job</a>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Job Created</th>
                                            <th scope="col">Applicants</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if ($myjobs->isNotEmpty())
                                            @foreach ($myjobs as $myjob)
                                                <tr class="active">
                                                    <td>
                                                        <div class="job-name fw-500">{{ $myjob -> title }} </div>
                                                        <div class="info1">{{ $myjob -> jobType ->name }}. {{ $myjob->location }}</div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($myjob -> created_at)->format('d M, Y') }}</td>
                                                    {{-- implement this later --}}
                                                    {{-- <td> <a href="{{ Route('account.jobApplications',['jobid'=>$myjob->id]) }}"> {{ $myjob->applications->where('application_status', '!=', -1)->count() }} Applications </a></td> --}}
                                                    <td> <a href="{{ Route('account.jobApplications',['jobid'=>$myjob->id]) }}"> {{ $myjob->applications->count() }} Applications </a></td>

                                                    <td>
                                                        @if ($myjob -> status == 1)
                                                            <div class="job-status text-capitalize">Active</div>
                                                        @else
                                                            <div class="job-status text-capitalize">Blocked</div>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div class="action-dots float-end">
                                                            <button href="#" class="btn" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="{{ route('jobDetail',['jobid'=>$myjob->id]) }}"> <i
                                                                            class="fa fa-eye" aria-hidden="true"></i>
                                                                        View</a></li>
                                                                <li><a class="dropdown-item" href="{{ route('account.editJob', ['job_id' => $myjob->id]) }}"><i
                                                                            class="fa fa-edit" aria-hidden="true"></i>
                                                                        Edit</a></li>
                                                                <li><a class="dropdown-item" onclick="deleteJob( {{ $myjob -> id }} )"><i
                                                                            class="fa fa-trash" aria-hidden="true" style=""></i>
                                                                        Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">You have not posted any jobs yet.</td>
                                            </tr>
                                        @endif
                                    </tbody>

                                </table>
                            </div>
                            <div>
                                {{ $myjobs -> links() }}
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
    function deleteJob(job_id){
        $.ajax({
            url: "{{ route('account.deleteJob') }}",
            type:'post',
            dataType: 'json',
            data: {job_id: job_id},
            success:function(response){
                console.log(response.status);
                window.location.href = "{{ route('account.myJobs') }}";
            }
        });
    }
</script>
@endsection
