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
                            <li class="breadcrumb-item active">Users</li>
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
                                    <h3 class="fs-4 mb-1 ps-2">Users</h3>
                                </div>
                                

                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name Created</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Mobile</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            {{-- <th scope="col">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if ($users->isNotEmpty())
                                            @foreach ($users as $user)
                                                <tr class="active">
                                                    <td>{{ $user->id }}</td>
                                                    <td><a href="{{ route('admin.viewUserProfile',['id'=>$user->id]) }}">{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @if ($user->mobile!=null && $user->mobile!='')
                                                            {{ $user->mobile }}
                                                        @else
                                                            ----------
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($user->status == 1)
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
                                                                @if ($user->status == 0)
                                                                    <li><a class="dropdown-item"
                                                                        onclick="userState({{ $user->id }}, 1)">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        Enable</a></li>
                                                                @endif

                                                                @if ($user->status == 1)
                                                                    <li><a class="dropdown-item"
                                                                        onclick="userState({{ $user->id }}, 0)">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        Disable</a></li>
                                                                @endif
                                                                
                                                                <li><a class="dropdown-item"
                                                                        onclick="deleteUser({{ $user->id }})"><i
                                                                            class="fa fa-edit" aria-hidden="true"></i>
                                                                        Delete</a></li>
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
                                {{ $users->links() }}
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
    function userState(userid, status){
        $.ajax({
            url: "{{ route('admin.updateUserStatus') }}",
            method: "PUT",
            data:{userid:userid, status:status==1?'active':'block'},
            dataType: 'json',
            success: function(response){
                console.log(response.status);
                if(urlParams.has('page')){
                    window.location.href = "{{ url() -> current() }}?page={{ Request::get('page') }}"
                }else{
                    window.location.href="{{ url() -> current() }}"
                }
            }
        });
    }

    function deleteUser(userid){
        $.ajax({
            url: "{{ route('admin.deleteUser') }}",
            method: "POST",
            data:{userid:userid},
            dataType: 'json',
            success: function(response){
                console.log(response.status);
                if(urlParams.has('page')){
                    window.location.href = "{{ url() -> current() }}?page={{ Request::get('page') }}"
                }else{
                    window.location.href="{{ url() -> current() }}"
                }
            }
        });
    }
</script>
@endsection
