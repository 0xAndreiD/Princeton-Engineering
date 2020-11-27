@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1) ? 'clientadmin.layout' : 'user.layout'))

@section('content')

<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Project List
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Check your projects here
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <!-- Dynamic Table Full -->
    <div class="block block-rounded block-bordered">
        <div class="block-content block-content-full">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 30px;">ID</th>
                        <th style = "width: 15%">Project Name</th>
                        <th style="width: 15%;">Project Number</th>
                        <th style="width: 30%;">File Name</th>
                        <th style="width: 10%;">Created Time</th>
                        <th style="width: 10%;">Submitted Time</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                    <tr>
                        <td class="text-center">{{$project['id']}}</td>
                        <td >{{ $project['clientProjectName'] }}</td>
                        <td >{{ $project['clientProjectNumber'] }}</td>
                        <td > <a href="{{ route('requestFile') . '?jobId=' . $project['id'] }}"> {{ $project['requestFile'] }} </a> </td>
                        <td >{{ $project['createdTime'] }}</td>
                        <td >{{ $project['submittedTime'] }}</td>
                        <td ><span class="badge badge-info"> {{ $project['available'] }} </span> </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    <div>
</div>

@endsection