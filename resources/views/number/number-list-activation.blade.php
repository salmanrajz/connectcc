<table class="table table-striped table-bordered zero-configuration">
    <thead>
        <tr>
            <th>S#</th>
            <th>Lead No</th>
            <th>Date and Time</th>
            <th>Customer Name</th>
            <th>Distance</th>
            <th>Plan Name</th>
            <th>Sim Type</th>
            <th>C.M. No</th>
            <th>Selected No</th>
            <th>Status</th>
            <th>Attend</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($operation as $key => $item)
        <tr>
            <td>{{$key}}</td>
            <td>{{$item->lead_no}}</td>
            <td style="color:black; font-weight:1000">{{$item->updated_at}}</td>
            <td>{{$item->customer_name}}</td>
            <td style="font-weight: 1000">
                @inject('provider', 'App\Http\Controllers\AjaxController')
                <strong class="text-bold">
                    {{-- {{$user_lat}} --}}
                    {{-- {{$user_lng}} --}}
                    {{-- {{$item->lat}} --}}
                    {{-- {{$item->lng}} --}}
                    {{-- {{ $provider::MaroDikro($user_lng,$user_lat,$item->lat,$item->lng) }} KM --}}
                </strong>
            </td>
            <td>
                @if ($item->sim_type == 'Elife')
                @php $plan = \App\elife_plan::whereId($item->select_plan)->first() @endphp
                {{$plan->plan_name}}
                @else
                @php $plan = \App\plan::whereId($item->select_plan)->first() @endphp
                {{$plan->plan_name}}
                @endif
            </td>
            <td>{{$item->sim_type}}</td>
            <td>{{$item->customer_number}}</td>
            <td>{{$item->selected_number}}</td>
            <td>{{$item->status_name}}</td>
            <td>
                <a href="{{route('activation.edit',$item->id)}}">
                <i class="fa fa-edit"></i>
                </a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#RejectModalNew{{$key}}">Follow Up Lead</button>
                                                {{-- MODAL REJECT --}}
                                                <div id="RejectModalNew{{$key}}" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" style="margin-top:10%;">
                                                {!! Form::model($item,['method'=>'get','action'=>['AjaxController@ActivationFollow',$item->id]]) !!}
                                                {{-- {{ Form::open([ 'method'  => 'get', 'route' => [ 'device.rejected', $item->id ] ]) }} --}}
                                                    <div class="modal-dialog">
                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" onclick="close_modal()">&times;</button>
                                                                    <h4 class="modal-title">Follow Up Remarks</h4>
                                                                </div>
                                                                {{$item->lead_no}}
                                                                <div class="modal-body">
                                                                    <!-- <p>Some text in the modal.</p> -->
                                                                    <div class="form-group" style="display:block;" id="Reject_New">
                                                                        <label for="followup_remarks">Follow</label>
                                                                    <textarea name="followup_remarks" id="followup_remarks" cols="30" rows="10" class="form-control" required></textarea>
                                                                    {!! Form::hidden('lead_id', $item->lead_no) !!}
                                                                    {!! Form::hidden('ver_id', $item->id) !!}

                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="submit" value="Reject" class="btn btn-success reject" name="reject_new" id="reject_ew" style="display:;" >
                                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                                </div>
                                                                </div>

                                                    </div>
                                                    {{ Form::close() }}

                                                </div>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>

    <script src="{{asset('assets/plugins/tables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/tables/js/datatable/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/tables/js/datatable-init/datatable-basic.min.js')}}"></script>
