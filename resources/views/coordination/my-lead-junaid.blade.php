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
            <th>Customer Name</th>
            <th>Sale Agent Name</th>
            <th>Emirate Name</th>
            <th>Call Center</th>
            {{-- <th>Plan Name</th> --}}
            <th>Sim Type</th>
            <th>Eti #</th>
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
            {{-- <td style="color:black; font-weight:1000">{{$item->updated_at}}</td> --}}
            <td>{{$item->customer_name}}</td>
            <td>{{$item->agent_name}}</td>
             <td>
                {{$item->emirate_location}}
            </td>


            <td>
                {{$item->agent_code}}
            </td>

            {{-- <td>
                @if ($item->sim_type == 'Elife')
                @php $plan = \App\elife_plan::whereId($item->select_plan)->first() @endphp
                {{$plan->plan_name}}
                @else
                @php $plan = \App\plan::whereId($item->select_plan)->first() @endphp
                @if($plan)
                {{$plan->plan_name}}
                @else
                Plan Not Found
                @endif
                @endif
            </td> --}}
            <td>{{$item->sim_type}}</td>
            <td>{{$item->eti_lead_id}}</td>
            <td>{{$item->selected_number}}</td>
            <td>{{$item->status_name}}</td>
            <td>
                <a href="{{route('activation.edit',$item->id)}}">
                <i class="fa fa-edit"></i>
                </a>
                {{-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#RejectModalNew{{$key}}">Follow Up Lead</button> --}}
                                                {{-- MODAL REJECT --}}

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
