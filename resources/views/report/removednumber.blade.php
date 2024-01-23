{{-- @extends('layouts.dashboard-app') --}}
{{-- https://github.com/grosv/laravel-passwordless-login?ref=madewithlaravel.com --}}
{{-- @section('main-content') --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="content-body">
            <div class="container">
                <div class="row page-titles">
                    <div class="col p-0">
                        <h4>Hello, <span>Welcome {{auth()->user()->name}}</span></h4>

                    </div>
                    <div class="col p-0">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">All REMOVED NUMBER</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">REMOVED NUMBERS</h4>
                            {{-- <a class="btn btn-success" href="{{route('plan.create')}}">Add New Plan</a> --}}
                                {{-- <div class="table-responsive"> --}}
                                    <form id="BulkActiveForm" method="post">
                                     <input type="button"  class="btn btn-success" onclick="BulkRemove('{{route('BulkRevive')}}','BulkActiveForm')" value="Bulk Revive">

                                    <table class="table table-striped table-bordered zero-configuration" id="pdf">
                                        <thead>
                                        <tr>
                                            <th>CheckBox</th>
                                            {{-- <th>Name</th> --}}
                                            <th>Number</th>
                                            <th>Type</th>
                                            <th>Channel</th>
                                            <th>Status</th>
                                            <th>PassCode</th>
                                            <th>Action</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($removed as $item)
                                        <tr>

                                            {{-- <td style="font-size:18px;">
                                                {{$item->name}}
                                            </td> --}}
                                            <td>
                                                <input type="checkbox" name="checkbox[]" id="checkbox" value="{{$item->id}}">
                                            </td>
                                            <td style="font-size:18px;">
                                                {{$item->number}}
                                            </td>
                                            <td style="font-size:18px;">
                                                {{$item->type}}
                                            </td>
                                            <td style="font-size:18px;">
                                                {{$item->channel_type}}
                                            </td>
                                            <td style="font-size:18px;">
                                                {{$item->status}}
                                            </td>
                                            <td style="font-size:18px;">
                                                {{$item->passcode}}
                                            </td>
                                            <td>
                                                {{-- <button onclick="RevNum('{{$item->id}}','{{route('Remove.RevNum')}}','{{$item->id}}')" class="btn btn-info">Revive</button> --}}
                                            </td>


                                        </tr>
                                        @endforeach
                                    </tbody>

                                    </table>

                            </form>
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- #/ container -->
        </div>
        {{-- @endsection --}}
        {{-- @@section('name') --}}

        {{-- @endsection --}}
{{-- <script src="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css
"></script>

<script src="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css
"></script> --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
{{-- <script src=""></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="{{asset('js/main.js')}}"></script>

<script>
$(document).ready(function () {
    $('#pdf').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>
