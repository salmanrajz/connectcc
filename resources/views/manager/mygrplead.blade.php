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
     <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">My Call Center All Leads</h4>
                        {{-- {{}} --}}

                        {{-- <a class="btn btn-success" href="{{route('plan.create')}}">Add New Plan</a> --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration"
                                style="font-weight:300">
                                {{-- <table s id="pdf"> --}}
                                <thead>
                                    <tr>
                                        <th>S#</th>
                                        <th>Lead No</th>
                                        <th>Date</th>
                                        <th>Agent Name</th>
                                        <th>Customer Name</th>
                                        <th>Selected Number</th>
                                        <th>Sim Type</th>
                                        <th>Plan Name</th>
                                        {{-- <th>Channel</th> --}}
                                        <th>Customer Number</th>
                                        {{-- <th>Lead Generate Time</th> --}}
                                        <th>Status</th>
                                        {{-- <th>Remarks</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($operation as $key => $item)

                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$item->lead_no}}</td>
                                        <td>{{$item->date_time}}</td>
                                        <td>{{$item->agent_name}}</td>
                                        <td>{{$item->customer_name}}</td>
                                        <td>{{$item->selected_number}}</td>
                                        {{-- <td>
                                            {{$provider::FullPlan($item->select_plan,$item->sim_type)}}
                                        </td> --}}
                                        <td>{{$item->sim_type}}</td>
                                        {{-- <td>{{$item->customer_number}}</td> --}}
                                        <td>
                                            @if ($item->sim_type == 'Elife')
                                            @php $plan = \App\elife_plan::whereId($item->select_plan)->first() @endphp
                                            {{$plan->plan_name}}
                                            @else
                                            @php $plan = \App\Models\plan::whereId($item->select_plan)->first() @endphp
                                            @if($plan)
                                            {{$plan->plan_name}}
                                            @endif
                                            @endif
                                        </td>
                                        {{-- <td>{{$item->channel_type}}</td> --}}
                                        <td>{{$item->customer_number}}</td>
                                         <td>
                                                    @if ($item->sim_type == 'Elife')
                                                        @if ($item->status == '1.02')
                                                            SR Generated
                                                        @endif
                                                    @else
                                                        {{$item->status_name}}
                                                    @endif
                                                </td>
                                        {{-- <td>{{$item->remarks}}</td> --}}
                                        <td>

                                            <a href="{{route('view.lead',$item->id)}}" data-toggle="tooltip"
                                                title="View Lead Details">
                                                {{-- View remarks --}}
                                                <i class="fa fa-eye display-6" style="color:green;"></i>
                                            </a>

                                        @if($item->status == '1.19')
                                        <a href="{{route('lead.re-follow-process',$item->id)}}" data-toggle="tooltip"
                                                title="Attend As Agent">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @elseif($item->status == '1.20' || $item->status == '1.07' || $item->status == '1.09')
                                        <a href="{{route('cord.add-location-lead',$item->id)}}" data-toggle="tooltip"
                                                title="Attend As Coordinator">
                                            <i class="fa fa-check-circle display-6" style="color:green;"></i>
                                        </a>
                                        @endif
                                        </td>

                                    </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
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
