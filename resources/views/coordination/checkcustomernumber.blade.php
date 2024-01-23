@extends('layouts.backend')

@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-1">
                    Dashboard
                </h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                    Welcome Admin, everything looks great.
                </h2>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="javascript:void(0)">App</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Dashboard
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Overview -->
    <!-- Labels on top -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ __('Customer Details') }}</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Search Lead Via Customer Number</h4>
                                <select name="check" id="check_customer_number" class="form-control"></select>
                                <input type="hidden" name="number_search_lead" id="number_search_url" value="{{route('customernumber.search.lead')}}">
                                {{-- <a class="btn btn-success" href="{{route('call-center.create')}}">Add New Call Center Group</a> --}}
                                <div id="mylead">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Number</th>
                                                <th>Status</th>
                                                <th>Call Center</th>
                                                <th>Action</th>
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
                </div>
        </div>
    </div>
    <!-- END Labels on top -->
    <!-- END Overview -->


</div>
<!-- END Page Content -->
@endsection
