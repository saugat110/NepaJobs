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

@if (session()->has('jobdeleted'))
    <div class="alert alert-success" role="alert">
        {{ session('jobdeleted') }}
    </div>
@endif

@if (session()->has('jobnotdeleted'))
    <div class="alert alert-danger" role="alert">
        {{ session('jobnotdeleted') }}
    </div>
@endif

@if (session()->has('applyjoberror'))
    <div class="alert alert-danger" role="alert">
        {{ session('applyjoberror') }}
    </div>
@endif

@if (session()->has('applyjoberror2'))
    <div class="alert alert-danger" role="alert">
        {{ session('applyjoberror2') }}
    </div>
@endif

@if (session()->has('applyjobsuccess'))
    <div class="alert alert-success" role="alert">
        {{ session('applyjobsuccess') }}
    </div>
@endif

@if (session()->has('jobunapplysuccess'))
    <div class="alert alert-success" role="alert">
        {{ session('jobunapplysuccess') }}
    </div>
@endif


@if (session()->has('savejobsuccess'))
    <div class="alert alert-success" role="alert">
        {{ session('savejobsuccess') }}
    </div>
@endif

@if (session()->has('savejoberror'))
    <div class="alert alert-danger" role="alert">
        {{ session('savejoberror') }}
    </div>
@endif

@if (session()->has('jobunsavesuccess'))
    <div class="alert alert-success" role="alert">
        {{ session('jobunsavesuccess') }}
    </div>
@endif


@if (session()->has('jobaccepted'))
    <div class="alert alert-success" role="alert">
        {{ session('jobaccepted') }}
    </div>
@endif

@if (session()->has('rejecteddeleted'))
    <div class="alert alert-success" role="alert">
        {{ session('rejecteddeleted') }}
    </div>
@endif







