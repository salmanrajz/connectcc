@extends('layouts.backend')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css')}}">

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">
            {{env('APP_NAME')}}
          </h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">
            {{auth()->user()->name}}
          </h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="javascript:void(0)">Leads</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
              Details
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Your Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          Agent Leads Details
        </h3>
      </div>
      <div class="row container">
          <div class="form-group mt-3">
        <a class="btn btn-success" href="{{route('add.new.lead')}}">
                                        Add New Lead
                                </a>
          </div>
                                <div class="form-group mt-3 text-center">
                                <button class="btn btn-success" onclick="MyLead('{{route('mylead.ajax')}}','1.02','{{asset('js/plugins/ckeditor/plugins/mathjax/images/loader.gif')}}')">Active</button>
                                <button class="btn btn-danger" onclick="MyLead('{{route('mylead.ajax')}}','reject','{{asset('js/plugins/ckeditor/plugins/mathjax/images/loader.gif')}}')">Reject</button>
                                <button class="btn btn-info" onclick="MyLead('{{route('mylead.ajax')}}','1.03 ','{{asset('js/plugins/ckeditor/plugins/mathjax/images/loader.gif')}}')">Non Verified</button>
                                <button class="btn btn-primary"onclick="MyLead('{{route('mylead.ajax')}}','follow','{{asset('js/plugins/ckeditor/plugins/mathjax/images/loader.gif')}}')">Follow Up</button>
                                <button class="btn btn-success" onclick="MyLead('{{route('mylead.ajax')}}','AssingToAct','{{asset('js/plugins/ckeditor/plugins/mathjax/images/loader.gif')}}')">Assign to Activation</button>
                                <button class="btn btn-info" onclick="MyLead('{{route('mylead.ajax')}}','1.01','{{asset('js/plugins/ckeditor/plugins/mathjax/images/loader.gif')}}')">Pending</button>
                                <button class="btn btn-primary" onclick="MyLead('{{route('mylead.ajax')}}','1.06','{{asset('js/plugins/ckeditor/plugins/mathjax/images/loader.gif')}}')">Later</button>
                                <button class="btn btn-primary" onclick="MyLead('{{route('mylead.ajax')}}','1.22','{{asset('js/plugins/ckeditor/plugins/mathjax/images/loader.gif')}}')">Review</button>
                                <button class="btn btn-success" onclick="MyLead('{{route('mylead.ajax')}}','all','{{asset('js/plugins/ckeditor/plugins/mathjax/images/loader.gif')}}')">All Lead</button>
      </div>
                {{-- </div> --}}
      <div class="block-content" id="LoadSalerData">
         <table class="table table-bordered table-striped" id="MyTable">
                <thead>
                  <tr>
                    <th >ID</th>
                    <th>Lead #</th>
                    <th>Name</th>
                    <th>Customer Num</th>
                    <th>Language</th>
                    <th>Selected Num</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($operation as $k=> $item)

                    <tr>
                        <td>
                            {{++$k}}
                        </td>
                        <td>
                            {{$item->lead_no}}
                        </td>
                        <td>
                            {{$item->customer_name}}
                        </td>
                        <td>
                            {{$item->customer_name}}
                        </td>
                        <td>
                            {{$item->language}}
                        </td>
                        <td>
                            {{$item->selected_number}}
                        </td>
                        <td>
                            {{$item->status}}
                        </td>
                        <td>
                            <i class="fa fa-pencil red" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top"></i>
                            <i class="fa fa-eye green"></i>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
         </table>
      </div>
    </div>
    <!-- END Your Block -->
  </div>
  <!-- END Page Content -->
      <script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables-buttons/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables-buttons-jszip/jszip.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('js/plugins/datatables-buttons/buttons.print.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables-buttons/buttons.html5.min.js')}}"></script>

    <!-- Page JS Code -->
    <script src="{{asset('js/pages/be_tables_datatables.min.js')}}"></script>
    <script>
        $(document).ready( function () {    $('#MyTable').DataTable();} );

    </script>
@endsection
