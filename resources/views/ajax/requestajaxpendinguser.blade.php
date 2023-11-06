<div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id="pdf">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Call Center</th>
                                        <th>Cnic Number</th>
                                        <th>Profile</th>
                                        <th>CNIC Front</th>
                                        <th>CNIC Back</th>
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
                                        <td>
                                            <img src="{{env('CDN_URL')}}/user-cnic/{{$item->profile}}" alt="cnic_front">
                                        </td>
                                        <td>
                                        <img src="{{env('CDN_URL')}}/user-cnic/{{$item->cnic_front}}" alt="cnic_front">
                                        </td>
                                        <td>
                                        <img src="{{env('CDN_URL')}}/user-cnic/{{$item->cnic_back}}" alt="cnic_back">
                                        </td>
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
