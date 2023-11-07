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
     <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>S#</th>
                                        <th>Lead No</th>
                                        {{-- <th>Date and Time</th> --}}
                                        <th>Appointment Time Start</th>
                                        <th>Appointment Time End</th>
                                        <th>Customer Name</th>
                                        <th>Plan Name</th>
                                        <th>Sim Type</th>
                                        <th>Selected No</th>
                                        <th>Status</th>
                                        <th>Attend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($operation as $key => $item)

                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$item->lead_id}}</td>
                                        {{-- <td style="color:black; font-weight:1000">{{$item->created_at}}</td> --}}
                                        <td style="color:black; font-weight:1000">
                                            {{date('h:i A', strtotime($item->appointment_from))}}</td>
                                        <td style="color:black; font-weight:1000">
                                            {{date('h:i A', strtotime($item->appointment_to))}}</td>
                                        <td>{{$item->customer_name}}</td>

                                        <td>
                                            @if ($item->sim_type == 'Elife')
                                            @php $plan = \App\Models\elife_plan::whereId($item->select_plan)->first() @endphp
                                            {{$plan->plan_name}}
                                            @else
                                            @php $plan = \App\Models\plan::whereId($item->select_plan)->first() @endphp
                                            @if ($plan)
                                            {{$plan->plan_name}}
                                            @endif

                                            {{-- {{$plan->plan_name}} --}}
                                            @endif
                                        </td>
                                        <td>{{$item->sim_type}}</td>
                                        <td>{{$item->selected_number}}</td>
                                        <td>{{$item->status_name}}</td>
                                        <td>
                                            <a href="{{route('view.lead',$item->lead_no)}}" data-toggle="tooltip"
                                                title="View">
                                                {{-- View remarks --}}
                                                <i class="fa fa-eye display-10" style="color:green;"></i>
                                            </a>

                                            {{-- </td>
                                        <td> --}}
                                        @if(auth()->user()->role == 'MainCoordinator' || auth()->user()->role == 'Emirate Coordinator')
                                            <a href="{{route('verification.add-location-lead',$item->lead_no)}}"
                                                data-toggle="tooltip" title="Edit">
                                                {{-- View remarks --}}
                                                Assign to Self
                                            </a>
                                            <a href="{{route('manage-cordination',$item->lead_no)}}"
                                                data-toggle="tooltip" title="Edit">
                                                {{-- View remarks --}}
                                                <i class="fa fa-pencil display-10" style="color:green;"></i>
                                            </a>
                                            <style>
                                                .modal-backdrop {
                                                    z-index: -1;
                                                    }
                                            </style>
                                            {{-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Update Time</button> --}}
                                             <div id="myModal" class="modal fade" role="dialog"
                                             data-backdrop="static" data-keyboard="false"
                                             style="margin-top:10%;" >
                                                <div class="modal-dialog">
                                                <form id="pre-verification-form">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" onclick="close_modal()">&times;</button>
                                                        <h4 class="modal-title">Choose Schedule Timing</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- <p>Some text in the modal.</p> -->
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                                            <h6>Choose Start Date </h6>
                                                            <!-- Linked Picker Parent -->
                                                            <input type="hidden" name="lead_id" value="{{$item->lead_no}}" id="lead_id">
                                                            <div class="form-group">
                                                            <div class="input-group date" id="datetimepicker6">
                                                                <input type="text" class="form-control" name="start_date" id="start_date" readonly value="{{date('d/m/Y')}}">
                                                                <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                                </span>
                                                            </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                                            <h6>Choose Start Time </h6>
                                                            <!-- Linked Picker Parent -->
                                                            <div class="form-group">
                                                            <div class="input-group date" id="datetimepicker6">
                                                                <input type="time" class="form-control" name="start_time" id="start_date"  >
                                                                <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                                </span>
                                                            </div>
                                                            </div>
                                                            <span class="red" style="color:red;text-align:center">System Automatically Add 2 Hrs from choosen time</span>
                                                        </div>
                                                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        <h3 class="text-center" id="loading_num3" style="display:none">
                            {{-- Please wait while system loading leads... --}}
                            <img src="{{asset('assets/images/loader.gif')}}" alt="Loading"
                                class="img-fluid text-center offset-md-6" style="width:35px;">
                        </h3>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-info" onclick="VerifyLead('{{route('update.time')}}','pre-verification-form','{{route('time.out')}}')">Update Time</button>
                                                        {{-- <input type="submit" value="Follow Up" class="btn btn-success" name="later"> --}}
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                    </div>
                                                </form>

                                                </div>
                                                </div>
                                                {{-- {{auth()->user()->role}} --}}
                                            @elseif(auth()->user()->role == 'Admin' || auth()->user()->id == '643' || auth()->user()->role == 'sale' || auth()->user()->role == 'NumberAdmin')
                                            {{-- @elseif(auth()->user()->role == 'Admin' || auth()->user()->id == '643') --}}
                                            <a href="{{route('verification.add-location-lead',$item->lead_no)}}"
                                                data-toggle="tooltip" title="Edit">
                                                {{-- View remarks --}}
                                                <i class="fa fa-pencil display-10" style="color:green;"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>

                            </table>
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
