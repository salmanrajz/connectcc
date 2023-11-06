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
                        <a class="link-fx" href="javascript:void(0)">DNCR</a>
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
            <h3 class="block-title">{{ __('DNCR FILE LIST') }}</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-12 space-y-5">
                    <h4 class="card-title">DNCR File</h4>
                                <form method="post" enctype="multipart/form-data" action="{{route('AddDncrPost')}}">
    {{ csrf_field() }}
                                <input type="file" name="myfile" id="myfile" class="form-control">
                        <input type="submit" name="upload" class="btn btn-primary mt-3" value="Upload">

                                </form>
                    <!-- Form Labels on top - Default Style -->
                    <div id="mylead">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>S#</th>
                                                <th>Request ID</th>
                                                <th>Old File</th>
                                                <th>New File</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $key=>$item)
                                                <tr>
                                                    <td>
                                                        {{++$key}}
                                                    </td>
                                                    <td>
                                                        {{$item->requested_id}}
                                                    </td>
                                                    <td>
                                                        <a href="{{env('CDN_URL')}}/documents/{{$item->requested_file}}">{{$item->requested_file}}</a>
                                                    </td>
                                                    <td>
                                                        @if($item->generated_file == 'NULLS')
                                                        FILE NOT GENERATED YET
                                                        @else
                                                        <a href="{{env('CDN_URL')}}/documents/{{$item->generated_file}}">{{$item->generated_file}}</a>

                                                        {{-- {{$item->generated_file}} --}}
                                                        @endif
                                                        {{-- {{$item->generated}} --}}
                                                    </td>
                                                    <td>
                                                        @if($item->generated_file == 'NULLS')
                                                        Processing
                                                        @else
                                                        <a href="{{env('CDN_URL')}}/documents/{{$item->generated_file}}">Download File</a>

                                                        {{-- Download File Now --}}
                                                        @endif
                                                        {{-- {{$item->requested_file}} --}}
                                                    </td>
                                                </tr>
                                            @endforeach


                                        </tbody>

                                    </table>
                                </div>
                                </div>
                    <!-- END Form Labels on top - Default Style -->

                    <!-- Form Labels on top - Alternative Style -->

                    <!-- END Form Labels on top - Alternative Style -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Labels on top -->
    <!-- END Overview -->


</div>
<!-- END Page Content -->
@endsection
