@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : (Auth::user()->userrole == 4 ? 'reviewer.layout' : 'user.layout')))

@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    My account
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Change your account settings here.
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Content -->
<div class="content" style="text-align:left">
    <div class="row" style="justify-content: space-around;">
        <div class="col-md-3">
            <h2 class="content-heading pt-0 text-center">Account Settings</h2>
            <div class="form-group mb-4">
                <label for="font-size">Username * </label>
                <input type="text" style="border: 1px solid pink;" class="form-control" id="username" name="username" placeholder="Enter Your Username.." value="{{Auth::user()->username}}">
            </div>
            <div class="form-group mb-4">
                <label for="cell-height">Email * </label>
                <input type="text" style="border: 1px solid pink;" class="form-control" id="email" name="email" placeholder="Enter Your Email.." value="{{Auth::user()->email}}">
            </div>
            <div class="form-group mb-4">
                <label for="cell-height">Password * </label>
                <input type="text" style="border: 1px solid pink;" class="form-control" id="password" name="password" placeholder="Enter Your Password.." value="{{Auth::user()->password}}">
            </div>

            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
            <h2 class="content-heading pt-0">Administrative Settings</h2>
            <div class="custom-control custom-checkbox custom-control-danger mb-4">
                <input type="checkbox" class="custom-control-input" id="automatic-open" name="automatic-open" <?php echo Auth::user()->auto_report_open ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="automatic-open">Automatic Report Files Open</label>
            </div>
            @endif

            <div class="form-group text-center">
                <button class="btn btn-primary" onclick="saveAccount()"> Save </button>
            </div>
        </div>
    </div>
</div>

@include('user.account.script')
@endsection