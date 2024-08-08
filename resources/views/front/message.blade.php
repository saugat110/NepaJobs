@if (session()->has('registrationsuccess'))
    <div class="alert alert-success">
        <p class="mb-0  pb-0"> {{ session('registrationsuccess') }} </p>
    </div>
@endif

@if (session()->has('loginerror'))
    <div class="alert alert-danger">
        <p class="mb-0  pb-0"> {{ session('loginerror') }} </p>
    </div>
@endif

@if (session()->has('updatedProfile'))
    <div class="alert alert-success" role="alert">
        {{ session('updatedProfile') }}
    </div>
@endif

@if (session()->has('pp_updated'))
    <div class="alert alert-success" role="alert">
        {{ session('pp_updated') }}
    </div>
@endif

@if (session()->has('jobsadded'))
    <div class="alert alert-success" role="alert">
        {{ session('jobsadded') }}
    </div>
@endif

@if (session()->has('jobUpdated'))
    <div class="alert alert-success" role="alert">
        {{ session('jobUpdated') }}
    </div>
@endif


