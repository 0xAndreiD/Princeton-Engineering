@extends('clientadmin.layout')

@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Company Profile Panel
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Panel Status
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-content block-content-full">
            <form class="" onsubmit="return false;" method="POST" id="profileForm">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Company Profile</h3>
                    </div>
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="number">Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="number" 
                                    name="digits" placeholder="Enter A Number.."
                                    value="{{$company->company_number}}" >
                                    <input type="hidden" class="form-control" id="id" name="id" value="{{$company->id}}">
                                </div>
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" 
                                        name="name" placeholder="Enter A Name.."
                                        value="{{$company->company_name}}"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="telno">Tel No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="telno" 
                                    name="telno" placeholder="212-999-0000"
                                    value="{{$company->company_telno}}"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="address" 
                                    name="address" placeholder="Your Address.."
                                    value="{{$company->company_address}}"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" 
                                    name="email" placeholder="Your A Valid Email.."
                                    value="{{$company->company_email}}"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="website">Website <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="website" 
                                    name="website" placeholder="http://example.com"
                                    value="{{$company->company_website}}"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-right bg-light">
                        <button id="updateButton" type="submit" class="btn btn-sm btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

        <!-- Normal Block Modal -->
        <div class="modal" id="modal-block-normal" tabindex="-1" role="dialog" aria-labelledby="modal-block-normal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                </div>
            </div>
        </div>
        <!-- END Normal Block Modal -->
        <script src="{{ asset('js/pages/common.js') }}"></script>

        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers:
                    { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });
            });
        </script>

        @include('clientadmin.companyProfile.script')
@endsection