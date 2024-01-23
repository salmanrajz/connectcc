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
                @inject('provider', 'App\Http\Controllers\HomeController')

  <!-- Page Content -->
  <div class="content">
    <!-- Your Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          Agent Details
        </h3>
      </div>
      {{-- Change wi`dth! --}}
<div class="table-responsive">
      <table class="table table-striped table-bordered zero-configuration" id="MyTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Call Center</th>
                                        <th>Cnic Number</th>
                                        {{-- <th>Profile</th>
                                        <th>CNIC Front</th>
                                        <th>CNIC Back</th> --}}
                                        <th>Phone</th>
                                        <th>Password</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($plan as $item)

                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->email}}</td>
                                        <td>{{$item->agent_code}}</td>
                                        <td>{{$item->cnic_number}}</td>
                                        {{-- <td>
                                            <img src="{{env('CDN_URL')}}/user-cnic/{{$item->profile}}" alt="cnic_front">
                                        </td>
                                        <td>
                                        <img src="{{env('CDN_URL')}}/user-cnic/{{$item->cnic_front}}" alt="cnic_front">
                                        </td>
                                        <td>
                                        <img src="{{env('CDN_URL')}}/user-cnic/{{$item->cnic_back}}" alt="cnic_back">
                                        </td> --}}
                                        <td>{{$item->phone}}</td>
                                        <td>{{$item->password}}</td>
                                        <td>
                                            @if($item->status == '1')
                                            Approved
                                            @elseif($item->status == 2)
                                            Reject
                                            @elseif($item->status == '0')
                                            Approval Pending
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn-success btn" onclick="ApprovedAccount('{{$item->id}}','{{route('approved.user')}}','1')">
                                                Approved
                                            </button>

                                            <button class="btn-danger btn" onclick="ApprovedAccount('{{$item->id}}','{{route('approved.user')}}','2')">
                                                Reject
                                            </button>
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
