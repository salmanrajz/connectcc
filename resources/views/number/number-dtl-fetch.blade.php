@if (auth()->user()->agent_code == 'ARF')
<div class="" id="broom">
    <table class="table ">
        <thead>
            <tr>
                <th>Numbers</th>
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
            @role('Manager|NumberSuperAdmin|Cordination')

                <td>
                    <a href="#" onclick="BookNum('{{$item->id}}','{{route('ajaxRequest.BookNum')}}')" class="btn btn-success">Reserve Now</a>
                </td>
                @endrole
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@else
<div class="table-responsive" >
    {{-- <table class="table "> --}}

    <table class="table table-striped table-bordered zero-configuration" id="MyTable">
        <thead>
            <tr>
                <th>Number</th>
                <th>Type</th>
                <th>Status</th>
                {{-- <th>RS</th> --}}
                {{-- <th>Number usage count</th> --}}
                {{-- <th>Channel</th> --}}
            {{-- @role('Manager|NumberSuperAdmin|Cordination') --}}

                <th>Action</th>
                {{-- @endrole --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                {{-- @php
                    $date = \Carbon\Carbon::parse($item->created_at);
                    $now = \Carbon\Carbon::now();
                     $diff = $date->diffInDays($now);
                @endphp --}}
                <td style="font-size:18px;color:red">
                    {{$item->number}}
                </td>
                <td style="font-size:18px;">
                    {{$item->type}}
                </td>
                <td style="font-size:18px;color:red">
                    {{$item->identity}}
                </td>
                {{-- <td>
                    {{$item->total}}
                </td> --}}
                {{-- <td style="font-size:18px;color:red">
                    @php
                    $cho = \App\choosen_number::select('id')->where('number_id',$item->id)->count();
                    if($cho > 0){
                        echo $cho;
                    }else{
                        echo 0;
                    }
                    @endphp
                </td> --}}
                {{-- <td style="font-size:18px;"> --}}
                    {{-- {{$item->channel_type}} --}}
                {{-- </td> --}}
                {{-- {{auth()->user()->role}} --}}
                @role('Manager|NumberSuperAdmin|Cordination|Emirate Coordinator|FloorManager|TeamLeader|Admin')
                {{-- @role('Coordinator') --}}
                <td>
                <a href="#" onclick="VerifyNum2('{{$item->id}}','{{route('ajaxRequest.VerifyNum2')}}')" class="btn btn-success">Remove</a>
                </td>
                @endrole
                {{-- @role('sale|NumberAdmin|Admin') --}}
                @if(auth()->user()->role == 'sale' || auth()->user()->role == 'NumberAdmin' || auth()->user()->role == 'Admin')
                <td>
                    <a href="#" onclick="BookNum('{{$item->id}}','{{route('ajaxRequest.BookNum')}}','{{$item->channel_type}}','{{$item->number}}','{{'home'}}')" class="btn btn-success">Reserved Number</a>
                </td>
                @endif
                {{-- @endrole --}}
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endif
{{-- <script src="{{asset('js/main.js')}}?id={{time()}}"></script> --}}
<script>
        $(document).ready( function () {    $('#MyTable').DataTable();} );

</script>

