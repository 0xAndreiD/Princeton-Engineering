@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : 'user.layout'))

@section('content')

<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Seal Positioning
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    You can create some seal templates
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="content" style="text-align:left">
    <div class="content" style="text-align:left">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Templates</h3>
                <div class="block-options">
                    <a type="button"  href="{{ route('sealtemplate') }}" class="btn-block-option">
                        <i class="fa fa-plus"></i> Add Template
                    </a>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table id="templates" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 30%;">ID</th>
                                <th style="width:30%">company</th>
                                <th style="width:30%;">Template</th>
                                <th style="min-width: 150px;">Action</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th class="searchHead">
                                    <select placeholder="Search Company" class="searchBox" id="companyFilter">
                                        <option value="">All</option>
                                        @foreach($companyList as $company)
                                            <option value="{{ $company['company_name'] }}">{{ $company['company_name'] }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th class="searchHead"> <input type="text" placeholder="Search Template" class="searchBox" id="templateFilter"> </th>
                                <th>
                                    <span class="ml-1" style='writing-mode: vertical-lr;width: 42px;transform: rotateZ(180deg);'>Edit</span>
                                    {{-- <span class="ml-4" style='writing-mode: tb-rl;width: 42px;transform: rotateZ(180deg);'>Permit</span> --}}
                                    <span class="ml-3" style='writing-mode: tb-rl;width: 42px;transform: rotateZ(180deg);'>Delete</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>
<script>
    $(document).ready(function () {
        var table = $('#templates').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "orderCellsTop": true,
            "pageLength" : 50,
            "ajax":{
                    "url": "{{ url('getTemplateData') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
            "columns": [
                { "data": "id" },
                { "data": "companyname" },
                { "data": "template" },
                { "data": "actions", "orderable": false }
            ]	 
        });

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });


        $("#templateFilter").on('keyup change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });

        $("#companyFilter").on('change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });
    });

    
function delTemplate(obj, id) {
    let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
        }
    });
    toast.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this template!',
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-danger m-1',
            cancelButton: 'btn btn-secondary m-1'
        },
        confirmButtonText: 'Yes, delete it!',
        html: false,
        preConfirm: e => {
            return new Promise(resolve => {
                setTimeout(() => {
                    resolve();
                }, 50);
            });
        }
    }).then(result => {
        if (result.value) {
            $.post("delTemplate", {data: id}, function(result){
                if (result){
                    $(obj).parents("tr").remove().draw;
                    toast.fire('Deleted!', 'User has been deleted.', 'success');
                }
            });

        } else if (result.dismiss === 'cancel') {
            toast.fire('Cancelled', 'Template is safe :)', 'info');
        }
    });
}

</script>
<script src="{{ asset('js/jquery.ui.min.js') }}"></script>
<script src="{{ asset('js/fabric.min.js') }}"></script>
@endsection