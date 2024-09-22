<!DOCTYPE html>
<html class="no-js" lang="en_AU" />

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>NepaJobs</title>
    <meta name="description" content="" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <meta name="csrf-token" content="lcybzWw2lWUYwzDdA0XVplU3d0yYBbg9BTETfB4M"> --}}

    <meta name="pinterest" content="nopin" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
    <!-- Fav Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="#" />
    @stack('head')
</head>

<body data-instant-intensity="mousedown">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow py-3">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">NepaJobs</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-0 ms-sm-0 me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('jobs') }}">Find Jobs</a>
                        </li>
                    </ul>

                    @if (Auth::check() && Auth::user()->role == 'admin')
                        <a class="btn btn-outline-primary me-2" href="{{ route('admin.Dashboard') }}"
                            type="submit">Admin</a>
                    @endif

                    @if (!Auth::check())
                        <a class="btn btn-outline-primary me-2" href="{{ route('account.login') }}"
                            type="submit">Login</a>
                    @else
                        <a class="btn btn-outline-primary me-2" href="{{ route('account.profile') }}" type="submit">My
                            Account</a>
                    @endif

                    @if (Auth::check() && Auth::user()->payment == 'paid')
                        <a class="btn btn-primary" href="{{ route('account.createJob') }}" type="submit">Post a Job</a>
                    @elseif(Auth::check() && Auth::user()->payment == 'notpaid' && Auth::user()->role=='user')
                        @php
                            $trans_id = uniqid('tx_', true);
                            $signature = hash_hmac(
                                'sha256',
                                "total_amount=510,transaction_uuid=$trans_id,product_code=EPAYTEST",
                                '8gBm/:&EnhH.1/q',
                                true,
                            );
                            $signature = base64_encode($signature);
                        @endphp
                        <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
                            <input type="hidden" id="amount" name="amount" value="500" required>
                            <input type="hidden" id="tax_amount" name="tax_amount" value ="10" required>
                            <input type="hidden" id="total_amount" name="total_amount" value="510" required>
                            <input type="hidden" id="transaction_uuid" name="transaction_uuid"
                                value="{{ $trans_id }}"required>
                            <input type="hidden" id="product_code" name="product_code" value ="EPAYTEST" required>
                            <input type="hidden" id="product_service_charge" name="product_service_charge"
                                value="0" required>
                            <input type="hidden" id="product_delivery_charge" name="product_delivery_charge"
                                value="0" required>
                            <input type="hidden" id="success_url" name="success_url"
                                value="{{ route('payment.Success') }}" required>
                            <input type="hidden" id="failure_url" name="failure_url"
                                value="{{ route('payment.Failure') }}" required>
                            <input type="hidden" id="signed_field_names" name="signed_field_names"
                                value="total_amount,transaction_uuid,product_code" required>
                            <input type="hidden" id="signature" name="signature" value="{{ $signature }}"
                                required>
                            <input value="Post a Job" type="submit" class="btn btn-primary">
                        </form>
                    @else
						<a class="btn btn-primary" href="{{ route('account.createJob') }}" type="submit">Post a Job</a>
					@endif
                </div>
            </div>
        </nav>
    </header>


    @yield('main')






    {{-- update profile picture form --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pb-0" id="exampleModalLabel">Change Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="profilePicForm" name="profilePicForm" enctype="multipart/form-data" action="">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="image" name="image"
                                accept="image/*" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mx-3">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <footer class="bg-dark py-3 bg-2">
        <div class="container">
            <p class="text-center text-white pt-3 fw-bold fs-6">© 2024 SaugatCompany, All rights reserved</p>
        </div>
    </footer>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
    <script src="{{ asset('assets/js/instantpages.5.1.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/lazyload.17.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // to update profile picture
        $('#profilePicForm').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('account.updateProfilePic') }}",
                type: 'post',
                dataType: 'json',
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = "{{ route('account.profile') }}";
                    }
                }
            });
        });
    </script>

    @yield('customJs')
</body>

</html>
