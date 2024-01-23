@extends('layouts.dashboard-app')
@php $users = \App\User::role('activation')->get() @endphp
{{-- $users = \App\User::role('activation')->get(); --}}

@section('main-content')
<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Hello, <span>Welcome {{auth()->user()->name}}</span></h4>

            </div>
            <div class="col p-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a>
                    </li>
                    <li class="breadcrumb-item active">All Coordination Lead</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Coordination Leads</h4>
                        {{-- <a class="btn btn-success" href="{{route('plan.create')}}">Add New Plan</a> --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>S#</th>
                                        <th>Lead No</th>
                                        <th>Customer Name</th>
                                        <th>Plan Name</th>
                                        <th>Sim Type</th>
                                        <th>Selected No</th>
                                        <th>Status</th>
                                        <th>Attend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($operation as $key => $item)

                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$item->lead_id}}</td>
                                        <td>{{$item->customer_name}}</td>
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
                                        <td>{{$item->selected_number}}</td>
                                        <td>{{$item->status_name}}</td>
                                        <td>
                                            <a href="{{route('view.lead',$item->lead_no)}}" data-toggle="tooltip"
                                                title="View">
                                                {{-- View remarks --}}
                                                <i class="fa fa-eye display-6" style="color:green;"></i>
                                            </a>

                                        {{-- </td>
                                        <td> --}}
                                        @if(auth()->user()->role == 'MainCoordinator')
                                        <a href="{{route('manage-cordination',$item->lead_no)}}" data-toggle="tooltip"
                                                title="Edit">
                                                {{-- View remarks --}}
                                                <i class="fa fa-pencil display-6" style="color:green;"></i>
                                            </a>
                                            {{-- <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#RejectModalNew{{$key}}">Re Assing Lead</button> --}}
                                            {{-- MODAL REJECT --}}
                                            {{-- <div id="RejectModalNew{{$key}}" class="modal fade" role="dialog"
                                                data-backdrop="static" data-keyboard="false" style="margin-top:10%;">
                                                {!!
                                                Form::model($item,['method'=>'post','action'=>['AjaxController@LeadReAssign',$item->id]])
                                                !!}
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                onclick="close_modal()">&times;</button>
                                                            <h4 class="modal-title">Re Assign Lead</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group" style="display:block;"
                                                                id="Reject_New">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="fom-group">
                                                                            <h6 class="text-left">
                                                                            </h6>
                                                                            <label for="add_location">Allocate
                                                                                To:</label>

                                                                            <select name="assing_to" id="assing_to"
                                                                                class="form-control">
                                                                                <option value="">Allocate to</option>
                                                                                @foreach($users as $user)
                                                                                <option value="{{ $user->id }}"
                                                                                    {{ $user->id == $item->assign_to ? 'selected' : '' }}>
                                                                                    {{ $user->name }}</option>
                                                                                @endforeach


                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {!! Form::hidden('lead_id', $item->id) !!}

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" value="Reject"
                                                                class="btn btn-success reject" name="reject_new"
                                                                id="reject_ew" style="display:;">
                                                        </div>
                                                    </div>

                                                </div>
                                                {{ Form::close() }}

                                            </div> --}}
                                        @endif
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- #/ container -->
</div>
@endsection
{{-- @@section('name') --}}

{{-- @endsection --}}
