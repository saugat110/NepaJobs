@extends('front.layouts.app')


@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">

            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Account Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">

                {{-- sidebar --}}
                <div class="col-lg-3">
                    @include('front.account.sidebar')
                </div>

                {{-- update personal info --}}
                <div class="col-lg-9">
                    {{-- for sucess error alert messages --}}
                        @include('front.message')
                    <div class="card border-0 shadow mb-4">
                        <form action="" id="updateProfileForm" name="updateProfileForm">
                            <div class="card-body  p-4">
                                <h3 class="fs-4 mb-1">My Profile</h3>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Name*</label>
                                    <input type="text" name="name" id="name" placeholder="Enter Name"
                                        class="form-control" value="{{ $user->name }}">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Email*</label>
                                    <input type="text" name="email" id="email" placeholder="Enter Email"
                                        class="form-control" value="{{ $user->email }}">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Designation</label>
                                    <input type="text" name="designation" id="designation" placeholder="Designation"
                                        class="form-control" value="{{ $user->designation }}">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Mobile</label>
                                    <input type="text" name="mobile" id="mobile" placeholder="Mobile"
                                        class="form-control" value="{{ $user->mobile }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary" id="upt_pro_btn">Update</button>
                            </div>
                        </form>
                    </div>


                    {{-- update password --}}
                    <div class="card border-0 shadow mb-4">
                        <form action="" id="changePasswordForm" name="changePasswordForm">
                            <div class="card-body p-4">
                                <h3 class="fs-4 mb-1">Change Password</h3>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Old Password*</label>
                                    <input type="password"  id="old_password" name="old_password" placeholder="Old Password" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">New Password*</label>
                                    <input type="password" id="new_password" name="new_password" placeholder="New Password" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Confirm Password*</label>
                                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="form-control">
                                    <p></p>
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary" id="upt_pwd_btn">Update</button>
                            </div>
                        </form>
                    </div>



                </div>
            </div>
        </div>
    </section>
@endsection


@section('customJs')
    <script>
        //personal info update
        $('#updateProfileForm').submit(function(e) {
            e.preventDefault();
            var updateProButton = document.getElementById('upt_pro_btn');
            updateProButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...`;
            updateProButton.disabled = true;

            $.ajax({
                url: '{{ route('account.updateProfile') }}',
                type: 'put',
                dataType: 'json',
                data: $('#updateProfileForm').serializeArray(),
                success: function(response) {
                    if (response.status == true) {
                        $('#name').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        $('#email').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        $('#designation').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        $('#mobile').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        window.location.href = "{{ route('account.profile') }}";

                        //some error in form
                    }else {
                        updateProButton.disabled = false;
                        updateProButton.innerHTML = 'Update';
                        var errors = response.errors;

                        if (errors.name) {
                            $('#name').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.name);
                        } else {
                            $('#name').removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.email) {
                            $('#email').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.email);
                        } else {
                            $('#email').removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.designation) {
                            $('#designation').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.designation);
                        } else {
                            $('#designation').removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.mobile) {
                            $('#mobile').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.mobile);
                        } else {
                            $('#mobile').removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                    }
                }
            });
        });

        //update password
        $('#changePasswordForm').submit(function(e){
            e.preventDefault();
            var updatePwdButton = document.getElementById('upt_pwd_btn');
            updatePwdButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...`;
            updatePwdButton.disabled = true;
            $.ajax({
                url: '{{ route('account.changePassword') }}',
                method: 'PUT',
                dataType: 'json',
                data:$('#changePasswordForm').serializeArray(),
                success:function(response){
                    // console.log(response.errors)

                    if(response.status == false){
                        updatePwdButton.disabled = false;
                        updatePwdButton.innerHTML = 'Update';

                        let errors = response.errors;
                        if (errors.old_password) {
                            $('#old_password').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.old_password);
                        } else {
                            $('#old_password').removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                        if (errors.new_password) {
                            $('#new_password').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.new_password);
                        } else {
                            $('#new_password').removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                        if (errors.confirm_password) {
                            $('#confirm_password').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.confirm_password);
                        } else {
                            $('#confirm_password').removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                    }else{
                        window.location.href = "{{ url() -> current() }}";
                    }
                }
            });
        });


    </script>
@endsection
