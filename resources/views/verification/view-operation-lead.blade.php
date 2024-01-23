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

                {{-- </div> --}}
<div class="table-responsive">
      <table class="table table-bordered table-striped" id="MyTable">
                <thead>
                  <tr>
                                                <th>S#</th>
                                                <th>Lead No</th>
                                                <th>Lead Attend By</th>
                                                <th>Language</th>
                                                <th>Customer Name</th>
                                                <th>Selected Number</th>
                                                <th>Plan Name</th>
                                                <th>Sim Type</th>
                                                <th>C.M. No</th>
                                                <th>Lead Generate Time</th>
                                                <th>Status</th>
                                                {{-- <th>Remarks</th> --}}
                                                <th>Attend</th>
                                                <th>Force Attend</th>
                  </tr>
                </thead>
                <tbody>
                                            @foreach ($operation as $key => $item)
                                            <tr>
                                                <td>{{++$key}}</td>
                                                <td>{{$item->lead_no}}</td>
                                                 <td style="color:black;font-weight:1000;">
                                            {{-- {{$item->verify_agent}} --}}
                                            @php $user = \App\Models\user::whereId($item->verify_agent)->first(); @endphp
                                            {{-- {{$plan->plan_name}} --}}
                                            @if($user)
                                            {{$user->name}}
                                            @else
                                            Not Attended Yet
                                            @endif
                                        </td>
                                                <td style="color:black;font-weight:1000;">{{$item->language}}</td>
                                                <td>{{$item->customer_name}}</td>
                                                <td style="color:black;font-weight:1000;">{{$item->selected_number}}</td>
                                                <td>

                                               {{-- Plan Name --}}
                                                </td>

                                                <td>{{$item->sim_type}}</td>
                                                <td>{{$item->customer_number}}</td>
                                                <td>{{$item->lead_generate_time}}</td>
                                                <td>
                                                    Pending
                                                </td>
                                                <td>
                                                <a href="#" onclick="accept_lead('{{$item->id}}','{{route('ajaxRequest.AcceptLead')}}','{{url('Verification/verification-lead/')}}')" data-toggle="tooltip" title="Attend Lead for Verification">
                                                <i class="fa fa-check-circle display-6" style="color:green;"></i>
                                                </a>
                                                {{-- @if(auth()->user()->id != '704') --}}
                                                {{-- @endif --}}
                                            </td>
                                            <td>
                                                    <a href="#" onclick="accept_lead_force('{{$item->id}}','{{route('ajaxRequest.Assignme')}}','{{url('Verification/verification-lead/')}}')" data-toggle="tooltip" title="Assign me lead">
                                                    <i class="fa fa-edit display-6" style="color:blue;"></i>
                                                    </a>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready( function () {    $('#MyTable').DataTable();} );

    </script>
@endsection
