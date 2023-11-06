    <div class="container-fluid mt-5">
        <button class="btn btn-success" onClick="window.location.reload();">Back</button>
    </div>

<h3 class="text-center">
    Reserved Number List
</h3>
@if (auth()->user()->agent_code == 'ARF')
<div class="" id="broom">
    <table class="table">
        <thead>
            <tr>
                <th>Number</th>
                <th>Type</th>
                {{-- <th>Channel</th> --}}
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td style="font-size:18px;">
                    {{$item->number}}
                </td>
                {{-- <td>
                    Agent Name
                </td> --}}
                <td style="font-size:18px;">
                    {{$item->type}}
                </td>
                <td style="font-size:18px;">
                    {{\Carbon\Carbon::createFromTimeStamp(strtotime($item->datetime))->diffForHumans()}}
                </td>
                <td>
                <a href="#" onclick="RevNum('{{$item->id}}','{{route('ajaxRequest.RevNum')}}','{{$item->cid}}')" class="btn btn-info">Revive</a>
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
            <th>Number</th>
            <th>Type</th>
            <th>Agent Name</th>
            <th>Time</th>
            <th>Action</th>
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
            <td style="font-size:18px;">
                {{$item->agent_name}}
            </td>

            <td style="font-size:18px;">
                {{\Carbon\Carbon::createFromTimeStamp(strtotime($item->datetime))->diffForHumans()}}
            </td>
            <td>

            <a href="#" onclick="RevNum('{{$item->id}}','{{route('ajaxRequest.RevNum')}}','{{$item->cid}}')" class="btn btn-info">Revive</a>
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
