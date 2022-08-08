@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : (Auth::user()->userrole == 4 ? 'reviewer.layout' : (Auth::user()->userrole == 5 ? 'printer.layout' : 'user.layout'))))

@section('css_after')
<link rel="stylesheet" href="{{ asset('css/account.css') }}">
@endsection

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
    <div class="row" <?php echo Auth::user()->userrole == 1 || Auth::user()->userrole == 2 ? '' : 'style="justify-content: space-around;"'; ?>>
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
            <div class="custom-control custom-checkbox custom-control-danger mb-4">
                <input type="checkbox" class="custom-control-input" id="allow-cc" name="allow-cc" <?php echo Auth::user()->allow_cc ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="allow-cc">Allow Email CC'ed</label>
            </div>
            @endif

            @if(Auth::user()->userrole == 1)
            <h2 class="content-heading pt-0">Administrative Settings</h2>
            <div class="custom-control custom-checkbox custom-control-danger mb-4">
                <input type="checkbox" class="custom-control-input" id="allow-cc" name="allow-cc" <?php echo Auth::user()->allow_cc ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="allow-cc">Allow Email CC'ed</label>
            </div>
            @endif

            <div class="form-group text-center">
                <button class="btn btn-primary" onclick="saveAccount()"> Save Account Settings </button>
            </div>
        </div>
        {{-- @if (Auth::user()->userrole == 1 || Auth::user()->userrole == 2)
        <div class="col-md-3">
            <h2 class="content-heading pt-0 text-center">Billing Information</h2>
            <div class="form-group mb-4">
                <label for="bname"><i class="fa fa-user"></i> Full Name</label>
                <input type="text" style="border: 1px solid pink;" class="form-control" id="bname" name="billing_name" placeholder="Enter Name..." value="">
            </div>
            <div class="form-group mb-4">
                <label for="bmail"><i class="fa fa-envelope"></i> Email</label>
                <input type="text" style="border: 1px solid pink;" class="form-control" id="bmail" name="billing_email" placeholder="Enter Email..." value="">
            </div>
            <div class="form-group mb-4">
                <label for="baddress"><i class="fa fa-address-card-o"></i> Address</label>
                <input type="text" style="border: 1px solid pink;" class="form-control" id="baddress" name="billing_address" placeholder="Enter Address..." value="">
            </div>
            <div class="form-group mb-4">
                <label for="bcity"><i class="fa fa-institution"></i> City</label>
                <input type="text" style="border: 1px solid pink;" class="form-control" id="bcity" name="billing_city" placeholder="Enter City..." value="">
            </div>
            <div class="row mb-4">
                <div class="ml-3">
                  <label for="state">State</label>
                  <input type="text" id="bstate" class="form-control" style="border: 1px solid pink; width: 130px;" placeholder="ex: MA">
                </div>
                <div class="ml-2">
                  <label for="zip">Zip</label>
                  <input type="text" id="bzip" class="form-control" style="border: 1px solid pink; width: 130px;">
                </div>
            </div>
            <div class="custom-control custom-checkbox custom-control-primary custom-control-lg">
                <input type="checkbox" class="custom-control-input" id="billing_same_chk" onclick="billingCopy()">
                <label class="custom-control-label" for="billing_same_chk" style="cursor: pointer;">Billing info same as company info</label>
            </div>
        </div>
        <div class="col-md-3">
            <h2 class="content-heading pt-0 text-center">Shipping Information</h2>
            <div class="form-group mb-4">
                <label for="sname"><i class="fa fa-user"></i> Full Name</label>
                <input type="text" style="border: 1px solid pink;" class="form-control" id="sname" name="billing_name" placeholder="Enter Name..." value="">
            </div>
            <div class="form-group mb-4">
                <label for="smail"><i class="fa fa-envelope"></i> Email</label>
                <input type="text" style="border: 1px solid pink;" class="form-control" id="smail" name="billing_email" placeholder="Enter Email..." value="">
            </div>
            <div class="form-group mb-4">
                <label for="saddress"><i class="fa fa-address-card-o"></i> Address</label>
                <input type="text" style="border: 1px solid pink;" class="form-control" id="saddress" name="billing_address" placeholder="Enter Address..." value="">
            </div>
            <div class="form-group mb-4">
                <label for="scity"><i class="fa fa-institution"></i> City</label>
                <input type="text" style="border: 1px solid pink;" class="form-control" id="scity" name="billing_city" placeholder="Enter City..." value="">
            </div>
            <div class="row mb-4">
                <div class="ml-3">
                  <label for="sstate">State</label>
                  <input type="text" id="sstate" name="sstate" class="form-control" style="border: 1px solid pink; width: 130px;" placeholder="ex: MA">
                </div>
                <div class="ml-2">
                  <label for="szip">Zip</label>
                  <input type="text" id="szip" name="szip" class="form-control" style="border: 1px solid pink; width: 130px;">
                </div>
            </div>
            <div class="custom-control custom-checkbox custom-control-primary custom-control-lg mb-4">
                <input type="checkbox" class="custom-control-input" id="shipping_same_chk" onclick="shippingCopy()">
                <label class="custom-control-label" for="shipping_same_chk" style="cursor: pointer;">Shipping info same as billing</label>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-primary" onclick="saveBilling()"> Save Billing Data </button>
            </div>
        </div>
        <div class="col-md-3">
            <h2 class="content-heading pt-0 text-center">Payment Information</h2>
            <div class="container preload">
                <div class="creditcard">
                    <div class="front">
                        <div id="ccsingle"></div>
                        <svg version="1.1" id="cardfront" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" viewBox="0 0 750 471" style="enable-background:new 0 0 750 471;" xml:space="preserve">
                            <g id="Front">
                                <g id="CardBackground">
                                    <g id="Page-1_1_">
                                        <g id="amex_1_">
                                            <path id="Rectangle-1_1_" class="lightcolor grey" d="M40,0h670c22.1,0,40,17.9,40,40v391c0,22.1-17.9,40-40,40H40c-22.1,0-40-17.9-40-40V40
                                    C0,17.9,17.9,0,40,0z" />
                                        </g>
                                    </g>
                                    <path class="darkcolor greydark" d="M750,431V193.2c-217.6-57.5-556.4-13.5-750,24.9V431c0,22.1,17.9,40,40,40h670C732.1,471,750,453.1,750,431z" />
                                </g>
                                <text transform="matrix(1 0 0 1 60.106 295.0121)" id="svgnumber" class="st2 st3 st4">0123 4567 8910 1112</text>
                                <text transform="matrix(1 0 0 1 54.1064 428.1723)" id="svgname" class="st2 st5 st6">JOHN DOE</text>
                                <text transform="matrix(1 0 0 1 54.1074 389.8793)" class="st7 st5 st8">cardholder name</text>
                                <text transform="matrix(1 0 0 1 479.7754 388.8793)" class="st7 st5 st8">expiration</text>
                                <text transform="matrix(1 0 0 1 65.1054 241.5)" class="st7 st5 st8">card number</text>
                                <g>
                                    <text transform="matrix(1 0 0 1 574.4219 433.8095)" id="svgexpire" class="st2 st5 st9">01/23</text>
                                    <text transform="matrix(1 0 0 1 479.3848 417.0097)" class="st2 st10 st11">VALID</text>
                                    <text transform="matrix(1 0 0 1 479.3848 435.6762)" class="st2 st10 st11">THRU</text>
                                    <polygon class="st2" points="554.5,421 540.4,414.2 540.4,427.9 		" />
                                </g>
                                <g id="cchip">
                                    <g>
                                        <path class="st2" d="M168.1,143.6H82.9c-10.2,0-18.5-8.3-18.5-18.5V74.9c0-10.2,8.3-18.5,18.5-18.5h85.3
                                c10.2,0,18.5,8.3,18.5,18.5v50.2C186.6,135.3,178.3,143.6,168.1,143.6z" />
                                    </g>
                                    <g>
                                        <g>
                                            <rect x="82" y="70" class="st12" width="1.5" height="60" />
                                        </g>
                                        <g>
                                            <rect x="167.4" y="70" class="st12" width="1.5" height="60" />
                                        </g>
                                        <g>
                                            <path class="st12" d="M125.5,130.8c-10.2,0-18.5-8.3-18.5-18.5c0-4.6,1.7-8.9,4.7-12.3c-3-3.4-4.7-7.7-4.7-12.3
                                    c0-10.2,8.3-18.5,18.5-18.5s18.5,8.3,18.5,18.5c0,4.6-1.7,8.9-4.7,12.3c3,3.4,4.7,7.7,4.7,12.3
                                    C143.9,122.5,135.7,130.8,125.5,130.8z M125.5,70.8c-9.3,0-16.9,7.6-16.9,16.9c0,4.4,1.7,8.6,4.8,11.8l0.5,0.5l-0.5,0.5
                                    c-3.1,3.2-4.8,7.4-4.8,11.8c0,9.3,7.6,16.9,16.9,16.9s16.9-7.6,16.9-16.9c0-4.4-1.7-8.6-4.8-11.8l-0.5-0.5l0.5-0.5
                                    c3.1-3.2,4.8-7.4,4.8-11.8C142.4,78.4,134.8,70.8,125.5,70.8z" />
                                        </g>
                                        <g>
                                            <rect x="82.8" y="82.1" class="st12" width="25.8" height="1.5" />
                                        </g>
                                        <g>
                                            <rect x="82.8" y="117.9" class="st12" width="26.1" height="1.5" />
                                        </g>
                                        <g>
                                            <rect x="142.4" y="82.1" class="st12" width="25.8" height="1.5" />
                                        </g>
                                        <g>
                                            <rect x="142" y="117.9" class="st12" width="26.2" height="1.5" />
                                        </g>
                                    </g>
                                </g>
                            </g>
                            <g id="Back">
                            </g>
                        </svg>
                    </div>
                    <div class="back">
                        <svg version="1.1" id="cardback" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" viewBox="0 0 750 471" style="enable-background:new 0 0 750 471;" xml:space="preserve">
                            <g id="Front">
                                <line class="st0" x1="35.3" y1="10.4" x2="36.7" y2="11" />
                            </g>
                            <g id="Back">
                                <g id="Page-1_2_">
                                    <g id="amex_2_">
                                        <path id="Rectangle-1_2_" class="darkcolor greydark" d="M40,0h670c22.1,0,40,17.9,40,40v391c0,22.1-17.9,40-40,40H40c-22.1,0-40-17.9-40-40V40
                                C0,17.9,17.9,0,40,0z" />
                                    </g>
                                </g>
                                <rect y="61.6" class="st2" width="750" height="78" />
                                <g>
                                    <path class="st3" d="M701.1,249.1H48.9c-3.3,0-6-2.7-6-6v-52.5c0-3.3,2.7-6,6-6h652.1c3.3,0,6,2.7,6,6v52.5
                            C707.1,246.4,704.4,249.1,701.1,249.1z" />
                                    <rect x="42.9" y="198.6" class="st4" width="664.1" height="10.5" />
                                    <rect x="42.9" y="224.5" class="st4" width="664.1" height="10.5" />
                                    <path class="st5" d="M701.1,184.6H618h-8h-10v64.5h10h8h83.1c3.3,0,6-2.7,6-6v-52.5C707.1,187.3,704.4,184.6,701.1,184.6z" />
                                </g>
                                <text transform="matrix(1 0 0 1 621.999 227.2734)" id="svgsecurity" class="st6 st7">985</text>
                                <g class="st8">
                                    <text transform="matrix(1 0 0 1 518.083 280.0879)" class="st9 st6 st10">security code</text>
                                </g>
                                <rect x="58.1" y="378.6" class="st11" width="375.5" height="13.5" />
                                <rect x="58.1" y="405.6" class="st11" width="421.7" height="13.5" />
                                <text transform="matrix(1 0 0 1 59.5073 228.6099)" id="svgnameback" class="st12 st13">John Doe</text>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="form-container">
                <div class="field-container">
                    <label for="cardname">Name</label>
                    <input id="cardname" maxlength="20" type="text" style="border: 1px solid pink;" class="form-control">
                </div>
                <div class="field-container">
                    <label for="cardnumber">Card Number</label><span id="generatecard">generate random</span>
                    <input id="cardnumber" type="text" pattern="[0-9]*" inputmode="numeric" style="border: 1px solid pink;" class="form-control">
                    <svg id="ccicon" class="ccicon" width="750" height="471" viewBox="0 0 750 471" version="1.1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
        
                    </svg>
                </div>
                <div class="field-container">
                    <label for="expirationdate">Expiration (mm/yy)</label>
                    <input id="expirationdate" type="text" pattern="[0-9]*" inputmode="numeric" style="border: 1px solid pink;" class="form-control">
                </div>
                <div class="field-container">
                    <label for="securitycode">Security Code</label>
                    <input id="securitycode" type="text" pattern="[0-9]*" inputmode="numeric" style="border: 1px solid pink;" class="form-control">
                </div>
            </div>
        </div>
        @endif --}}
    </div>
</div>

<script src="{{ asset('js/imask.min.js') }}"></script>
@include('user.account.script')
@endsection