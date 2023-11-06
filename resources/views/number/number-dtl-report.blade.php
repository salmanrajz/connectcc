@if (auth()->user()->agent_code == 'ARF')
<div class="" id="broom">
    <table class="table ">
        <thead>
            <tr>
                <th>Number</th>
                <th>Type</th>
                {{-- <th>Action</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td style="font-size:18px;">
                    {{$item->number}}
                </td>
                <td style="font-size:18px;">
                    {{$item->type}}
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@else
<div class="table-responsive" id="broom">
    <table class="table table-striped table-bordered zero-configuration">
        <thead>
            <tr>
                <th>Name</th>
                <th>Number</th>
                <th>Type</th>
                <th>Agent Group</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td style="font-size:18px;">
                    {{$item->name}}
                </td>
                <td style="font-size:18px;">
                    {{$item->number}}
                </td>
                <td style="font-size:18px;">
                    {{$item->type}}
                </td>
                <td style="font-size:18px;">
                    {{$item->agent_group}}
                </td>
                <td>

                    @if($item->status == 'Hold')
                    <button onclick="reject('{{$item->id}}','{{route('ajaxRequest.Reject')}}','{{$item->cid}}')" class="btn btn-success">Reject</button>
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endif

    <script src="{{asset('assets/plugins/tables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/tables/js/datatable/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/tables/js/datatable-init/datatable-basic.min.js')}}"></script>
