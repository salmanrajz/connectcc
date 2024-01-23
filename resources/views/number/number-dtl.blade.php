{{-- @extends('layouts.num-app')
 --}}
@extends('layouts.backend')

@section('content')
<body onload="NumberDtl('All','{{route('ajaxRequest.NumberByType')}}')">

<div class="form-container">
    <div class="container-fluid">
        <h3>Select Number Type</h3>
        @foreach ($q as $qq)
    <input type="radio" name="numtype" id="standard" value="{{$qq->type}}" onclick="NumberDtl('{{$qq->type}}','{{route('ajaxRequest.NumberByType')}}')">
        <label for="radio">{{$qq->type}}</label>
        @endforeach
        <input type="radio" name="numtype" id="reload" onclick="NumberDtl('All','{{route('ajaxRequest.NumberByType')}}')">
            <label for="radio">Show All</label>
        <input type="radio" name="numtype" id="standard" value="{{auth()->user()->agent_code}}" onclick="NumberDtl('{{auth()->user()->agent_code}}','{{route('ajaxRequest.NumberByCallCenter')}}')">
            <label for="radio">Special Number</label>
    </div>

    <div class="container-fluid float-left left">
        <button class="btn btn-info float-left left" onclick="ShowReserved('{{auth()->user()->id}}','{{route('ajaxRequest.ReservedNum')}}')">Show Reserved Number</button>

    {{-- </div> --}}
    {{-- <div class="container-fluid float-right right"> --}}
        {{-- <li><a href="{{route('logout')}}"><i class="icon-power"></i> <span>Logout</span></a> --}}
        {{-- <a class="btn btn-success float-right right" href="{{route('logout')}}">Logout ({{auth()->user()->name}})</a> --}}
    </div>

</div>
<h3 class="text-center" id="loading_num">
    Please wait while system loading numbers...
    <img src="{{asset('js/plugins/slick-carousel/ajax-loader.gif')}}" alt="Loading" class="img-fluid text-center offset-md-6">
</h3>
<div id="broom" class="container">

</div>
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
    <script src="{{asset('js/plugins/sweetalert2/sweetalert2.js')}}"></script>
    <link rel="stylesheet" href="{{asset('js/plugins/sweetalert2/sweetalert2.css')}}">
    <!-- Page JS Code -->
    <script src="{{asset('js/pages/be_tables_datatables.min.js')}}"></script>
    <script>
        $(document).ready( function () {    $('#MyTable').DataTable();} );

    </script>
@endsection
