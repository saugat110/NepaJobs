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

@if (session()->has('passwordChanged'))
    <div class="alert alert-success" role="alert">
        {{ session('passwordChanged') }}
    </div>
@endif

@if (session()->has('passwordChangeError'))
    <div class="alert alert-danger" role="alert">
        {{ session('passwordChangeError') }}
    </div>
@endif

@if (session()->has('notAdmin'))
    <div class="alert alert-danger" role="alert">
        {{ session('notAdmin') }}
    </div>
@endif

@if (session()->has('jobrejected'))
    <div class="alert alert-danger" role="alert">
        {{ session('jobrejected') }}
    </div>
@endif

@if (session()->has('userStateManage'))
    <div class="alert alert-success" role="alert">
        {{ session('userStateManage') }}
    </div>
@endif

@if (session()->has('userStateError'))
    <div class="alert alert-danger" role="alert">
        {{ session('userStateError') }}
    </div>
@endif

@if (session()->has('deletedUser'))
    <div class="alert alert-success" role="alert">
        {{ session('deletedUser') }}
    </div>
@endif

@if (session()->has('jobFeatured'))
    <div class="alert alert-success" role="alert">
        {{ session('jobFeatured') }}
    </div>
@endif

@if (session()->has('jobUnFeatured'))
    <div class="alert alert-success" role="alert">
        {{ session('jobUnFeatured') }}
    </div>
@endif

@if (session()->has('resetEmail'))
    <div class="alert alert-success" role="alert">
        {{ session('resetEmail') }}
    </div>
@endif

@if (session()->has('tokenMismatch'))
    <div class="alert alert-danger" role="alert">
        {{ session('tokenMismatch') }}
    </div>
@endif

@if (session()->has('resetSuccess'))
    <div class="alert alert-success" role="alert">
        {{ session('resetSuccess') }}
    </div>
@endif

@if (session()->has('categoryAdded'))
    <div class="alert alert-success" role="alert">
        {{ session('categoryAdded') }}
    </div>
@endif

@if (session()->has('categoryStatus'))
    <div class="alert alert-success" role="alert">
        {{ session('categoryStatus') }}
    </div>
@endif

@if (session()->has('categoryDeleted'))
    <div class="alert alert-success" role="alert">
        {{ session('categoryDeleted') }}
    </div>
@endif

@if (session()->has('jobtypeAdded'))
    <div class="alert alert-success" role="alert">
        {{ session('jobtypeAdded') }}
    </div>
@endif

@if (session()->has('jobtypeStatus'))
    <div class="alert alert-success" role="alert">
        {{ session('jobtypeStatus') }}
    </div>
@endif

@if (session()->has('jobtypeDeleted'))
    <div class="alert alert-success" role="alert">
        {{ session('jobtypeDeleted') }}
    </div>
@endif

@if (session()->has('jobStatus'))
    <div class="alert alert-success" role="alert">
        {{ session('jobStatus') }}
    </div>
@endif

@if (session()->has('jobDeleted'))
    <div class="alert alert-success" role="alert">
        {{ session('jobDeleted') }}
    </div>
@endif

@if (session()->has('paymentSuccess'))
    <div class="alert alert-success" role="alert">
        {{ session('paymentSuccess') }}
    </div>
@endif

@if (session()->has('notpaid'))
    <div class="alert alert-danger" role="alert">
        {{ session('notpaid') }}
    </div>
@endif










