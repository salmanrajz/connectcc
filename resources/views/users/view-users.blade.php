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
      <table class="table table-bordered table-striped" id="MyTable">
         <thead>
                  <tr>
                    <th >ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Call Center</th>
                    <th>Status</th>
                    <td>Account Created</td>
                    <td>Last Sale</td>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($data as $k=> $item)

                    <tr>
                        <td>
                            {{++$k}}
                        </td>
                        <td>
                            {{$item->name}}
                        </td>
                        <td>
                            {{$item->email}}
                        </td>
                        <td>
                            {{$item->sl}}
                        </td>
                        <td>
                            {{$item->agent_code}}
                        </td>
                        <td>
                                                    @if($item->deleted_at != '')
                                                    Deactivated
                                                    @else
                                                    Active
                                                    @endif
                                                </td>

                                                <td>
                                                    {{$item->created_at}}
                                                </td>

                                                <td>
                                                    {{-- @if($item->deleted_at == '') --}}
                                                    @if($item->role == 'sale' || $item->role == 'NumberAdmin')
                                                        {{\Carbon\Carbon::parse($provider::LastSaleCounter($item->id))->diffForHumans()}}
                                                    @endif
                                                    {{-- @endif --}}
                                                </td>
                                                <td>
                                                {{-- <a href="{{route('user.edit',$item->id)}}">
                                                <i class="fa fa-edit"></i>
                                                </a> --}}
                                                @if($item->deleted_at != '')
                                                    <a href="{{route('user.destroy',$item->id)}}" onclick="return confirm('Are you sure you want to Enable this user?');">
                                                        <i class="fa fa-key"></i>
                                                    </a>
                                                    {{-- Active --}}
                                                    @else
                                                    {{-- De Active --}}
                                                    <a href="{{route('user.destroy',$item->id)}}" onclick="return confirm('Are you sure you want to Disabled this user?');">
                                                        <i class="fa fa-recycle"></i>
                                                    </a>
                                                    @endif
                                                <a href="{{route('user.edit',$item->id)}}">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="{{route('master.login',$item->id)}}" onclick="return confirm('Are you sure you want to login this user?');">
                                                    <i class="fa fa-lock"></i>
                                                </a>
                                                <a href="{{route('AgentReport',$item->id)}}" >
                                                    <i class="fa fa-bar-chart"></i>
                                                </a>
                                                <a href="{{route('AgentMonthlySale',$item->id)}}" >
                                                    <i class="fa fa-bar-chart"></i>
                                                </a>
                                                <a href="{{route('UserLog',$item->id)}}" >
                                                    <i class="fa fa-line-chart"></i>
                                                </a>
                                                <a href="#" onclick="AssignChannelPartner('{{route('user.assign.data')}}','{{$item->id}}','{{asset('assets/images/loader.gif')}}')">
                                                    <i class="fa fa-users"></i>
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
    <script>
        $(document).ready( function () {    $('#MyTable').DataTable();} );

    </script>
@endsection
