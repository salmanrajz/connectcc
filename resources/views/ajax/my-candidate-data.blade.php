@extends('layouts.dashboard-app')

@section('main-content')
<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                                       <h4>Hello, <span>Welcome {{auth()->user()->name}}</span></h4>

            </div>
            <div class="col p-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">From</a>
                    </li>
                    <li class="breadcrumb-item active">Basic</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>

                            @foreach($errors->all() as $error)
                            {{ $error }}<br />
                            @endforeach
                        </div>
                        @endif
                        <form class="form-horizontal form-label-left input_mask" method="post"
                            {{-- action="{{route('admin.user.store')}}"  --}}
                            id="CandidateData"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <!-- Plan name -->
                            <div class="form-group">

                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Candidate Name</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="inputSuccess3" name="name"
                                        placeholder="Type Candidate Name" type="text" autocomplete="off" value="{{$data->name}}" readonly>
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Email</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="inputSuccess3" name="email"
                                        placeholder="Type Email Here" type="email"  value="{{$data->email}}" readonly>
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Phone</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="inputSuccess3" name="phone"
                                        placeholder="Type Phone # Here" type="phone" value="{{$data->phone}}" readonly>
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Appointment Date</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="slot_date" name="appointment_date"
                                        type="text" value="{{$data->schedule_date}}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Appointment Time</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="slot_time" name="appointment_time"
                                        type="text" value="{{$data->schedule_time}}" readonly>
                                </div>
                            </div>
                            <div class="form-group" id="LoadSlotData">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Appointment Location</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback" >
                                <input class="form-control has-feedback-left" id="slot_time" name="appointment_time"
                                        type="text" value="{{$data->location}}" readonly>
                                </div>
                            </div>
                            <div class="form-group" id="LoadLocationManager">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Appointment Manager</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback" >
                                <input class="form-control has-feedback-left" id="slot_time" name="appointment_time"
                                        type="text" value="{{$data->manager_id}}" readonly>
                                </div>
                            </div>
                            <div class="form-group" id="LoadLocationManager">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Candidate Source</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback" >
                                <input class="form-control has-feedback-left" id="slot_time" name="appointment_time"
                                        type="text" value="{{$data->source}}" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Appointment Message</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <textarea name="message" id="message" cols="30" rows="10" class="form-control" readonly>{{$data->message}}</textarea>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Manager Feedback</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <textarea name="manager_feedback" id="manager_feedback" cols="30" rows="10" class="form-control" readonly>{{$data->manager_feedback}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Call Center</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <select name="call_center" id="call_center" class="form-control">
                                        <option value="">Select Call Center</option>
                                        <option value="CC4">CC4</option>
                                        <option value="CC5">CC5</option>
                                        <option value="CC7">CC7</option>
                                        <option value="CC8">CC8</option>
                                    </select>
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <!-- <button type="button" class="btn btn-primary">Can cel</button> -->
                            {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                            <button type="button" class="btn btn-success" name="upload"
                            onclick="VerifyLead('{{route('create.id')}}','CandidateData','{{route('joined.candidate')}}')"
                            >Generate ID</button>
                        </div>
                    </div>
                    <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        <h3 class="text-center" id="loading_num3" style="display:none">
                            {{-- Please wait while system loading leads... --}}
                            <img src="{{asset('assets/images/loader.gif')}}" alt="Loading"
                                class="img-fluid text-center offset-md-6" style="width:35px;">
                        </h3>



                    </div>
                    <!--  #7-->



                    </form>

                </div>
            </div>
        </div>

    </div>

</div>
<!-- #/ container -->
</div>
@endsection
