@extends('front.layouts.app')

@push('head')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('main')
    <section class="section-5 bg-2" style="min-height: 55vh !important">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.Dashboard') }}">Admin</a></li>
                            <li class="breadcrumb-item active">Jobs</li>
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

                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1 ps-2">Jobs</h3>
                                </div>


                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Created By</th>
                                            <th scope="col">Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            {{-- <th scope="col">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if ($jobs->isNotEmpty())
                                            @foreach ($jobs as $job)
                                                <tr class="active">
                                                    <td>{{ $job->id }}</td>
                                                    <td>{{ $job->title }}</td>
                                                    <td>{{ $job->user->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($job->status == 1)
                                                            <div class="job-status text-capitalize">Active</div>
                                                        @else
                                                            <div class="job-status text-capitalize">Blocked</div>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div class="action-dots">
                                                            <button href="#" class="btn" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('jobDetail', ['jobid' => $job->id]) }}">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        View</a></li>

                                                                @if($job->isFeatured != 1)
                                                                <li><a class="dropdown-item"
                                                                        onclick="jobFeatureManager({{ $job->id }}, 'Feature')"><i
                                                                            class="fa fa-edit" aria-hidden="true"></i>
                                                                        Feature</a></li>
                                                                @endif

                                                                @if($job->isFeatured != 0)
                                                                <li><a class="dropdown-item"
                                                                        onclick="jobFeatureManager({{ $job->id }}, 'Unfeature')"><i
                                                                            class="fa fa-edit" aria-hidden="true"></i>
                                                                        UnFeature</a></li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">No Users.</td>
                                            </tr>
                                        @endif
                                    </tbody>

                                </table>
                            </div>
                            <div>
                                {{ $jobs->links() }}
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
        // $(document).on('contextmenu', function(e) {
        //     e.preventDefault();
        // });
        const urlParams = new URLSearchParams(window.location.search);

        function jobFeatureManager(jobid, type) {
            if (type == 'Feature') {
                console.log("feature");
                $.ajax({
                    url: "{{ route('admin.jobFeatureManage') }}",
                    method: 'put',
                    data: {
                        status: type,
                        id: jobid
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            if (urlParams.has('page')) {
                                window.location.href =
                                    "{{ url()->current() }}?page={{ Request::get('page') }}"
                            } else {
                                window.location.href = "{{ url()->current() }}"
                            }
                        }
                    }
                });
            }else if(type == 'Unfeature'){
                console.log("unfeature");
                $.ajax({
                    url: "{{ route('admin.jobFeatureManage', ['id' => $job->id]) }}",
                    method: 'put',
                    data: {
                        status: type,
                        id: jobid
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            if (urlParams.has('page')) {
                                window.location.href =
                                    "{{ url()->current() }}?page={{ Request::get('page') }}"
                            } else {
                                window.location.href = "{{ url()->current() }}"
                            }
                        }
                    }
                });
            }
            // $.ajax({
            //     url: "{{ route('admin.updateUserStatus') }}",
            //     method: "PUT",
            //     data: {
            //         userid: userid,
            //         status: status == 1 ? 'active' : 'block'
            //     },
            //     dataType: 'json',
            //     success: function(response) {
            //         console.log(response.status);
            //         if (urlParams.has('page')) {
            //             window.location.href = "{{ url()->current() }}?page={{ Request::get('page') }}"
            //         } else {
            //             window.location.href = "{{ url()->current() }}"
            //         }
            //     }
            // });
        }
    </script>
@endsection
