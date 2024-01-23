<div class="table-responsive">
    <table class="table table-striped table-bordered zero-configuration">
        <thead>
            <tr>
                <th>S#</th>
                <th>Lead No</th>
                <th>Customer Name</th>
                <th>Plan Name</th>
                <th>Sim Type</th>
                <th>Shared With</th>
                <th>C.M. No</th>
                <th>Selected No</th>
                <th>Later Date</th>
                <th>Lead Generate Time</th>
                {{-- <th>Status</th> --}}
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($operation as $key => $item)

            <tr>
                <td>{{++$key}}</td>
                <td>{{$item->lead_no}}</td>
                <td>{{$item->customer_name}}</td>
                <td>
                    @if ($item->sim_type == 'Elife')
                    @php $plan = \App\Models\elife_plan::whereId($item->select_plan)->first() @endphp
                    {{$plan->plan_name}}
                    @else
                    @php $plan = \App\Models\plan::whereId($item->select_plan)->first() @endphp
                    @if($plan)
                    {{$plan->plan_name}}
                    @endif
                    @endif
                </td>
                <td>{{$item->sim_type}}</td>
                <td>
                    @php
                    $user = \App\Models\User::where('id',$item->share_with)->first();
                    if($user){
                    echo $user->name . '-' . $user->email;
                    }else{
                    echo "Un Shared Lead";
                    }
                    @endphp
                </td>
                <td>{{$item->customer_number}}</td>
                <td>{{$item->selected_number}}</td>
                <td>
                    @if($item->status == '1.06')
                    {{$item->later_date}}
                    @endif
                </td>
                <td>{{$item->lead_generate_time}}</td>
                <td>
                    @if ($item->sim_type == 'Elife')
                    @if ($item->status == '1.02')
                    SR Generated
                    @endif
                    @else
                    {{$item->status_name}}
                    @endif
                </td>
                {{-- <td>{{$item->remarks}}</td> --}}
                <td>
                    @if($item->status == '1.02' || $item->status == '1.11')
                    <a href="{{route('view.lead.active',$item->id)}}">
                        {{-- <i class="fa fa-edit"></i> --}}
                        <i class="fa fa-edit display-6" style="color:green;"></i>

                    </a>
                    @elseif($item->status == '1.03')
                    <a href="{{route('edit.lead',$item->id)}}">
                        <i class="fa fa-edit"></i>
                    </a>
                    @elseif($item->status == '1.13')
                    <a href="{{route('re-verification.lead_generate',$item->id)}}">
                        <i class="fa fa-edit"></i>
                    </a>
                    @else
                    {{-- <td> --}}
                    <a href="{{route('view.lead',$item->id)}}" data-toggle="tooltip" title="View Lead Details">
                        {{-- View remarks --}}
                        <i class="fa fa-eye display-6" style="color:green;"></i>
                    </a>
                    <a href="{{route('verification.add-location-lead',$item->id)}}" data-toggle="tooltip"
                        title="View Lead Details">
                        {{-- View remarks --}}
                        <i class="fa fa-edit display-6" style="color:red;"></i>
                    </a>
                    {{-- </td> --}}
                    @endif
                </td>
            </tr>
            @endforeach

        </tbody>

    </table>
</div>
<script src="{{asset('assets/plugins/tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/tables/js/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/tables/js/datatable-init/datatable-basic.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-mask/jasny-bootstrap.min.js')}}"></script>
