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
                                    <h3 class="fs-4 mb-1">Jobs Applied</h3>
                                </div>


                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Applied Date</th>
                                            <th scope="col">Applicants</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if ($jobapps->isNotEmpty())
                                            @foreach ($jobapps as $jobapp)
                                                <tr class="active">
                                                    <td>
                                                        <div class="job-name fw-500">{{ $jobapp->job->title }} </div>
                                                        <div class="info1">{{ $jobapp->job->jobType->name }}.
                                                            {{ $jobapp->job->location }}</div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($jobapp->applied_at)->format('d M, Y') }}
                                                    </td>
                                                    {{-- implement this later --}}
                                                    <td>{{ $jobapp ->job->applications -> count() }} Applications</td>
                                                    <td>
                                                        @if ($jobapp->job->status == 1)
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
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('jobDetail', ['jobid' => $jobapp->job->id]) }}">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        View</a></li>

                                                                <li><a class="dropdown-item"
                                                                        onclick="unapply( {{ $jobapp->id }} )"><i
                                                                            class="fa fa-trash" aria-hidden="true"
                                                                            style=""></i>
                                                                        Withdraw</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5">You have not applied for any job yet.</td>
                                        </tr>
                                        @endif
                                    </tbody>

                                </table>
                            </div>
                            <div>
                                {{ $jobapps->links() }}
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
        function unapply(jobapp_id) {
            if (confirm("Are you sure you want to withdraw?")) {
                $.ajax({
                    url: "{{ route('account.jobUnapply') }}",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        jobapp_id: jobapp_id
                    },
                    success: function(response) {
                        console.log(response.status);
                        window.location.href = "{{ route('account.jobsApplied') }}";
                    }
                });
            }
        }
    </script>
@endsection
