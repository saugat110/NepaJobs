@extends('front.layouts.app')

@push('head')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('main')
    <section class="section-5 bg-2" style="min-height: 55vh !important">
        <div class="container py-5">
            <div class="row d-flex justify-content-between">
                <div class="col-auto">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.Dashboard') }}">Admin</a></li>
                            <li class="breadcrumb-item active">Categories</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add Category
                    </button>
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
                                    <h3 class="fs-4 mb-1 ps-2">Categories</h3>
                                </div>


                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Title</th>
                                            <th scope="col" class="text-center">No of Jobs</th>
                                            <th scope="col">Created_At</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            {{-- <th scope="col">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <tr class="active">
                                                    <td>{{ $category->id }}</td>
                                                    <td><a href="{{ route('jobs') }}?category={{ $category->id }}">{{ $category->name }}</a></td>
                                                    <td class="text-center">{{ $category->jobs->count() }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($category->created_at)->format('d M, Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($category->status == 1)
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

                                                                @if($category->status==0)
                                                                <li><a class="dropdown-item"
                                                                        onclick="categoryStatus({{ $category->id }}, 'enable')">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        Enable</a></li>

                                                                @endif

                                                                @if($category->status==1)
                                                                <li><a class="dropdown-item"
                                                                        onclick="categoryStatus({{ $category->id }}, 'disable')">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        Disable</a></li>
                                                                @endif

                                                                <li><a class="dropdown-item"
                                                                    onclick = "deleteCategory({{ $category -> id }})">
                                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                                    Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">No Categories.</td>
                                            </tr>
                                        @endif
                                    </tbody>

                                </table>
                            </div>
                            <div>
                                {{ $categories->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Enter Category:</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="category_name" id="category_name" class="form-control">
          <p class=""></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="addCategory()">Add</button>
        </div>
      </div>
    </div>
  </div>
  
    </section>
@endsection

@section('customJs')
    <script>
        const urlParams = new URLSearchParams(window.location.search);

        function addCategory(){
            var cname = $('#category_name').val();
            console.log(cname);

            $.ajax({
                url: "{{ route('admin.addCategory') }}",
                method: 'post',
                data: {category_name: cname},
                dataType:'json',
                success: function(response){
                    if(response.status){
                        if(urlParams.has('page')){
                            window.location.href = "{{ url() -> current() }}?page={{ Request::get('page') }}";
                        }else{
                            window.location.href = "{{ url() -> current() }}";
                        }
                    }else{
                        var errors = response.errors;
                        $('#category_name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.category_name);
                    }
                },
            });
        }

        function categoryStatus(id, status){
            $.ajax({
                url: "{{ route('admin.categoryStatus') }}",
                method : 'post',
                data: {id: id, status: status},
                dataType: 'json',
                success: function(response){
                    if(response.status){
                        if(urlParams.has('page')){
                            window.location.href = "{{ url() -> current() }}?page={{ Request::get('page') }}";
                        }else{
                            window.location.href = "{{ url() -> current() }}";
                        }
                    }
                }
            });
        }

        function deleteCategory(id){
            $.ajax({
                url: "{{ route('admin.deleteCategory') }}",
                method: 'delete',
                data: {id: id},
                dataType: 'json',
                success: function(response){
                    if(response.status){
                        window.location.href = "{{ url() -> current() }}"
                    }
                }
            });
        }
            
    </script>
@endsection
