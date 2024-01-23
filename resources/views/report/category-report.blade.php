@extends('layouts.dashboard-app')

@section('main-content')
<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Hello, <span>Welcome {{auth()->user()->name}}</span></h4>

            </div>
            <div class="col p-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Activation Leads</a>
                    </li>
                    <li class="breadcrumb-item active">Activation Report</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Activation Download</h4>
                        {{-- {{}} --}}
                        <form class="form-horizontal form-label-left input_mask" method="post" id="ActiveForm"
                            {{-- action="{{route('admin.user.store')}}" --}}
                            enctype="multipart/form-data" autocomplete="off">
                            <div class="form-group">
                                <label for="start_date">Start Date: </label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="start_date">End Date: </label>
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="start_date">Number Category: </label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <select name="number_category[]" multiple id="number_category"
                                        class="emirates form-control select2" aria-placeholder="Select Category">
                                         {{-- <option value="">Select Category</option> --}}
                                            @foreach ($q as $item)
                                            <option value="{{$item->type}}">{{$item->type}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="start_date">Channel Partner: </label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <select name="channel_partner[]"  id="channel_partner"
                                        class="form-control">
                                         <option value="All">All</option>
                                         <option value="TTF">TTF</option>
                                         <option value="ExpressDial">Express Dial</option>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <select name="category" id="category"
                                    class="select2 form-control js-example-basic-single" multiple>
                                    <option value="All">All</option>
                                    @foreach ($q as $item)
                                    <option value="{{$item->type}}">{{$item->type}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                            <div class="form-group">
                                <button class="btn btn-info"
                                    onclick="DownloadReport('{{route('download.category.report')}}','ActiveForm','loadtable')">Download</button>
                            </div>
                        </div>

                        {{-- <a class="btn btn-success" href="{{route('plan.create')}}">Add New Plan</a> --}}
                        {{-- <div class="table-responsive">

                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>S#</th>
                                        <th>Lead No</th>
                                        <th>Customer Name</th>
                                        <th>Plan Name</th>
                                        <th>Sim Type</th>
                                        <th>Lead Type</th>
                                        <th>C.M. No</th>
                                        <th>Lead Generate Time</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>View Lead</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- #/ container -->
</div>
@endsection
{{-- @@section('name') --}}

{{-- @endsection --}}
