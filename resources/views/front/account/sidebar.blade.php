<div class="card border-0 shadow mb-4 p-3">
    <div class="s-body text-center mt-3">

        @if (Auth::user()->image == null || Auth::user()->image == '')
            <img src="{{ asset('assets/images/avatar7.png') }}" alt="avatar" class="rounded-circle img-fluid"
                style="width: 150px;">
        @else
            <img src="{{ asset('/profilepic/thumb') }}/{{ Auth::user()->image }} " alt="avatar"
                class="rounded-circle img-fluid" style="width: 150px;">
        @endif

        <h5 class="mt-3 pb-0">{{ Auth::user()->name }}</h5>
        <p class="text-muted mb-1 fs-6">{{ Auth::user()->designation }}</p>

        {{-- model for profile pic --}}
        <div class="d-flex justify-content-center mb-2">
            <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" class="btn btn-primary">Change
                Profile Picture</button>
        </div>

    </div>
</div>
<div class="card account-nav border-0 shadow mb-4 mb-lg-0">
    <div class="card-body p-0">
        <ul class="list-group list-group-flush ">
            <li class="list-group-item d-flex justify-content-between p-3">
                <a href="{{ route('account.profile') }}">Account Settings</a>
            </li>


            @if (Auth::check() && ( Auth::user()->payment == 'paid' || Auth::user()->role == 'admin') )
                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <a href="{{ route('account.createJob') }}">Post Job</a>
                </li>
            @elseif(Auth::check() && Auth::user()->payment == 'notpaid' && Auth::user()->role == 'user')
                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
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
                    <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST"
                        style="margin:0px;padding:0px;height:fit-content">
                        <input type="hidden" id="amount" name="amount" value="500" required>
                        <input type="hidden" id="tax_amount" name="tax_amount" value ="10" required>
                        <input type="hidden" id="total_amount" name="total_amount" value="510" required>
                        <input type="hidden" id="transaction_uuid" name="transaction_uuid"
                            value="{{ $trans_id }}"required>
                        <input type="hidden" id="product_code" name="product_code" value ="EPAYTEST" required>
                        <input type="hidden" id="product_service_charge" name="product_service_charge" value="0"
                            required>
                        <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0"
                            required>
                        <input type="hidden" id="success_url" name="success_url" value="{{ route('payment.Success') }}"
                            required>
                        <input type="hidden" id="failure_url" name="failure_url" value="{{ route('payment.Failure') }}"
                            required>
                        <input type="hidden" id="signed_field_names" name="signed_field_names"
                            value="total_amount,transaction_uuid,product_code" required>
                        <input type="hidden" id="signature" name="signature" value="{{ $signature }}" required>
                        <a><input type="submit" value="Post Job"
                                style="border:0px;background-color:white;margin:0px;padding:0px"> </a>
                    </form>
                </li>
            @endif

            @if ( Auth::check() && (Auth::user()->payment == 'paid' || Auth::user()->role == 'admin') )
                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <a href="{{ route('account.myJobs') }}">My Jobs</a>
                </li>
            @elseif(Auth::check() && Auth::user()->payment == 'notpaid' && Auth::user()->role == 'user')
                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
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
                    <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST"
                        style="margin:0px;padding:0px;height:fit-content">
                        <input type="hidden" id="amount" name="amount" value="500" required>
                        <input type="hidden" id="tax_amount" name="tax_amount" value ="10" required>
                        <input type="hidden" id="total_amount" name="total_amount" value="510" required>
                        <input type="hidden" id="transaction_uuid" name="transaction_uuid"
                            value="{{ $trans_id }}"required>
                        <input type="hidden" id="product_code" name="product_code" value ="EPAYTEST" required>
                        <input type="hidden" id="product_service_charge" name="product_service_charge" value="0"
                            required>
                        <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0"
                            required>
                        <input type="hidden" id="success_url" name="success_url" value="{{ route('payment.Success') }}"
                            required>
                        <input type="hidden" id="failure_url" name="failure_url" value="{{ route('payment.Failure') }}"
                            required>
                        <input type="hidden" id="signed_field_names" name="signed_field_names"
                            value="total_amount,transaction_uuid,product_code" required>
                        <input type="hidden" id="signature" name="signature" value="{{ $signature }}" required>
                        <a><input type="submit" value="My Jobs"
                                style="border:0px;background-color:white;margin:0px;padding:0px"> </a>
                    </form>
                </li>
            @endif

            {{-- <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{ route('account.myJobs') }}">My Jobs</a>
            </li> --}}

            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{ route('account.jobsApplied') }}">Jobs Applied</a>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{ route('account.savedJobs') }}">Saved Jobs</a>
            </li>

            {{-- logout --}}
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{ route('account.logout') }}">Logout</a>
            </li>
        </ul>
    </div>
</div>
