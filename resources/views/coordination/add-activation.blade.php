@extends('layouts.backend')

@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-1">
                    Dashboard
                </h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                    Welcome Admin, everything looks great.
                </h2>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="javascript:void(0)">App</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Dashboard
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Overview -->
    <!-- Labels on top -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ __('Customer Details') }}</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-12 space-y-5">

                    <!-- Form Labels on top - Default Style -->
    <form onsubmit="return false" method="post" id="ActiveForm" enctype="multipart/form-data">

        <div class="form-group row">
                            <div class="col-md-6 col-sm-6 col-xs-12 mb-2">
                                <label class="center">
                                    Upload Additional Documents
                                </label>
                            </div>
                        </div>
                        <div class="form-group audio_action mt-2" id="klon_audio1">
                            <div class="col-sm-4 col-xs-12 col-md-4">
                                {{-- <label for="audio1">Additional Document </label> --}}
                                <input type="file" name="documents[]" id="docs" class="">
                            </div>


                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6 form-group rajput mt-4">
                            <a class="btn btn-primary" id="add_audio" style="color: white">
                                Add New Document
                            </a>
                        </div>

                         <div class="form-group row hidden d-none">

                            <div class=" item col-md-4 col-sm-6 col-xs-12 form-group hidden d-none">
                                <select name="additional_document_activation" class="form-control " id="hideme_document"
                                    style="display:block">
                                    <option value="No Additional Document Required">No Additional Document Required
                                    </option>
                                    <option value="Salary Certificate" id="" selected>Salary Certificate</option>
                                    <option value="Tenancy Contract" id="">Tenancy Contract</option>
                                    <option value="Utility Bill" id="">Utility Bill (Current)</option>
                                    <option value="Credit Card" id="">Credit Card</option>
                                    <option value="Pay Slip" id="">Pay Slip</option>
                                    <option value="Title Deeds" id="">Title Deeds</option>
                                    <option value="Car Registration" id="">Car Registration</option>
                                    <option value="Labour Contract" id="">Labour Contracts</option>
                                    <option value="Etisalat Postpaid/Elife Account" id="">Etisalat Postpaid/Elife
                                        Account</option>
                                    <option value="Bank Statement 3 Months" id="">Bank Statement 3 Months</option>
                                    <option value="Customer has Existing billing (account 6 months old)" id="">Customer
                                        has Existing billing (account 6 months old)
                                    </option>
                                    <!-- <option value="24">24 Months</optio n> -->
                                </select>
                            </div>
                        </div>
                    {{-- <form  method="POST" onsubmit="return false;" id="ActiveForm"> --}}
                        <div class="container row mt-5">
                            <input type="hidden" name="leadid" value="{{$data->id}}" id="leadid">
                            <div class="mb-4 col-lg-4">
                                <label class="form-label"
                                    for="example-ltf-email">{{ __('Customer Name') }}</label>
                                <input class="form-control " id="cname" placeholder="Customer Name" name="cname"
                                    type="text" required value="{{ $data->customer_name }}">
                                <input type="hidden" name="type" class="channel_type" id="type" value="{{env('channel_type')}}">
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label class="form-label"
                                    for="example-ltf-password">{{ __('Customer Number') }}</label>
                                <input class="form-control " placeholder="Customer Number i.e 0551234567" name="cnumber"
                                    maxlength="10" required type="tel"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    onkeypress="return isNumberKey(event) " onkeyup="TestNumber()" id="customer_number"
                                    value="{{ $data->customer_number }}" data-validate-length-range="6"
                                    data-validate-words="2" id="customer_number" />
                                <input type="hidden" name="number_test"
                                    value="{{ route('number.tester') }}" id="number_tester">
                                <p style="color:red;display:none;" id="dpExist">
                                    Number Already Exist
                                </p>
                            </div>
                             <div class="col-md-6 col-sm-6 col-xs-12 form-group hidden d-none">
                                <label for="customername">Nationality:</label>
                                <select name="nation" id="c_select" class="form-control " required>
                                    {{-- <option value=""></option> --}}
                                    @foreach($countries as $country)
                                    <option value="{{ $country->name }}"
                                        {{ $data->nationality == $country->name ? 'selected' : '' }}>
                                        {{ $country->name }}</option>
                                    {{-- <option value="{{ $country->name }}">{{ $country->name }}</option> --}}
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="age">Customer Age</label>
                                <input class="form-control " id="age" placeholder="Customer Age not less than 21"
                                    name="age" required type="number" required onkeypress="return isNumberKey(event)"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    maxlength="2" value="{{ $data->age }}">
                            </div>

                            <div class="mb-4 col-lg-4">
                                <label for="simtype">Product Type</label>

                                <select name="simtype" id="simtype" class="sim_type form-control" required>
                                    <option value="">-- Product Type --</option>
                                    <option value="New" @if ($data->sim_type=="New" ) {{ 'selected' }}
                                        @endif>New</option>
                                    <option value="MNP" @if ($data->sim_type=="MNP" ) {{ 'selected' }}
                                        @endif>MNP</option>
                                </select>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="gender">Gender</label>

                                <select name="gender" id="gender" class="gender form-control" required>
                                    <option value="">-- Select Gender --</option>
                                    <option value="Male" @if ($data->gender=="Male" ) {{ 'selected' }}
                                        @endif>Male</option>
                                    <option value="Female" @if ($data->gender=="Female" )
                                        {{ 'selected' }} @endif>Female</option>
                                    <option value="Other" @if ($data->gender=="Other" )
                                        {{ 'selected' }} @endif>Other</option>
                                </select>
                            </div>
                             <div class=" item col-md-6 col-sm-6 col-xs-12 form-group hidden d-none">
                                <!-- <input class="form-control " id="inputSuccess3" placeholder="Customer Number" data-inputmask="'mask' : '(999) 999-9999'" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="both name(s) e.g Jon Doe" required="required" type="num"> -->
                                {{-- <select name="additional_document" class="form-control " id="credit_salary" style="display:none;">
                                        <!-- <option value="No Documents Required">No Documents Required</option> -->
                                        <option value="Credit Card" id="">Credit Card</option>
                                        <option value="Salary Certificate" id="">Salary Certificate</option>
                                    </select> --}}
                                <!-- salary_certificate_above_15000 Darham OR CREDIT CARD -->
                                <label for="customername">Customer Add Docs:</label>
                                <select name="additional_document" class="form-control " id="hideme_document"
                                    style="display:block">

                                    <option value="Salary Certificate" id="" class="hideonelife"
                                        {{$data->additional_document == 'Salary Certificate ' ? 'selected' : ''}}>
                                        Salary Certificate</option>
                                    <option value="Tenancy Contract" id=""
                                        {{$data->additional_document == 'Renancy Contract' ? 'selected' : ''}}>
                                        Tenancy Contract</option>
                                    <option value="Utility Bill" id="" class="hideonelife"
                                        {{$data->additional_document == 'Utility Bill' ? 'selected' : ''}}>Utility
                                        Bill (Current)</option>
                                    <option value="Credit Card" id="" class="hideonelife"
                                        {{$data->additional_document == 'Credit Slip' ? 'selected' : ''}}>Credit
                                        Card</option>
                                    <option value="Pay Slip From Exchange" id="" class="hideonelife"
                                        {{$data->additional_document == 'Pay Slip From Exchange' ? 'selected' : ''}}>
                                        Pay Slip From Exchange</option>
                                    <option value="Title Deeds" id="" class="hideonelife"
                                        {{$data->additional_document == 'Title Deeds' ? 'selected' : ''}}>Title
                                        Deeds</option>
                                    <option value="Car Registration" id="" class="hideonelife"
                                        {{$data->additional_document == 'Car Mulkiya' ? 'selected' : ''}}>Car
                                        Mulkiya</option>
                                    <option value="Labour Contract" id="" class="hideonelife"
                                        {{$data->additional_document == 'Labour Contract' ? 'selected' : ''}}>
                                        Labour Contracts</option>
                                    <option value="Etisalat Postpaid/Elife Account" id="" class="hideonelife"
                                        {{$data->additional_document == 'Erisalat Postpaid/Elife Account' ? 'selected' : ''}}>
                                        Etisalat Postpaid/Elife Account</option>
                                    <option value="Bank Statement Last 3 Months" id="" class="hideonelife"
                                        {{$data->additional_document == 'Bank Statement Last 3 Months' ? 'selected' : ''}}>
                                        Bank Statement Last 3 Months</option>
                                    <option value="Customer has Existing billing (account 6 months old)"
                                        {{$data->additional_document == 'Customer has Existing billing (account 6 months old)' ? 'selected' : ''}}
                                        id="" class="hideonelife">Customer has Existing billing (account 6 months old)
                                    </option>
                                    <option value="DU Bill Last 3 Months"
                                        {{$data->additional_document == 'DU Bill Last 3 Months' ? 'selected' : ''}}
                                        id="" class="hideonelife">DU Bill Last 3 Months
                                    </option>
                                </select>
                                <!-- <input class="form-control " id="salman_ahmed" placeholder="Selected Number" data-validate-length-range="6" data-validate-words="2" name="selnumber" type="file"> -->

                            </div>
                            <div class="col-md-5 col-sm-6 col-xs-12 form-group hidden d-none">
                                <label for="customername">Customer Language:</label>
                                <select name="language" class="form-control " required="">
                                    <option value="English" @if ($data->language == "English") {{ 'selected' }}
                                        @endif>English</option>
                                    <option value="Arabic" @if ($data->language == "Arabic") {{ 'selected' }}
                                        @endif>Arabic</option>
                                    <option value="Hindi/Urdu" @if ($data->language == "Hindi/Urdu")
                                        {{ 'selected' }} @endif>Hindi / Urdu</option>
                                </select>
                            </div>

                                                        <div class=" item col-md-6 col-sm-6 col-xs-12 form-group d-none hidden">
                                <input type="text" name="activation_sim_serial_no" id="activation_sim_serial_no"
                                    placeholder="Sim Serial #" class="form-control" maxlength="19"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    value="0">
                            </div>
                               <div class=" item col-md-6 col-sm-6 col-xs-12 form-group hidden d-none">
                                <label for="sale_agent">Sale Agent</label>
                                <input type="text" name="activation_sold" id="activation_sold_by" placeholder="Sold BY"
                                    class="form-control" value="{{$data->saler_name}}">
                                <input type="hidden" name="activation_sold_by" id="activation_sold_by"
                                    placeholder="Sold BY" class="form-control" value="{{$data->saler_id}}">
                                <input type="hidden" name="saler_id" id="saler_id" placeholder="Sold BY"
                                    class="form-control" value="{{$data->saler_id}}">
                            </div>
                            @if($data->sim_type == 'Elife' || $data->sim_type == 'HomeWifi')
                            @include('coordination.ajax.active-elife')
                            @elseif($data->sim_type == 'MNP')
                            @include('coordination.ajax.active-mnp')
                            @elseif($data->sim_type == 'New')
                            @include('coordination.ajax.active-new')
                            @endif
                            {{-- <div class="mb-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div> --}}
                        </div>
<div class="form-group mt-5">
                            <div class="container-fluid">
                                <input type="hidden" name="call_back_ajax" class="call_back_ajax" id="call_back_ajax"
                                    value="yes">

                                @if($audios->count() > 0)
                                <input type="submit" value="Submit" class="btn btn-success submit_button_new"
                                    onclick="SavingActivationLead('{{route('activate-lead')}}','ActiveForm','{{route('home')}}')">
                                @endif

                                <input type="submit" value="Submit Not Verified" class="btn btn-success submit_button_new"
                                    onclick="SavingActivationLead('{{route('activate-nonverified')}}','ActiveForm','{{route('home')}}')">
                                <button class="btn btn-success" type="button" name="reverify" id="reverify"
                                    data-bs-toggle="modal" data-bs-target="#myModalVer">Re Verify</button>
                                <button class="btn btn-success" type="button" name="follow" id="follow_up"
                                    data-bs-toggle="modal" data-bs-target="#myModal">Follow</button>

                                <button class="btn-submit btn btn-danger" type="button" data-bs-toggle="modal"
                                    data-bs-target="#RejectModalNew">Reject</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#RejectModalNew1">Re Assing Lead</button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#LaterLeadModal">Later Lead</button>

                            </div>

                        </div>
                        {{-- Lorem ipsum dolor sit amet consectetur, adipisicing elit. At amet maiores a ipsum quidem iusto odit doloribus rerum accusantium itaque magnam velit, deserunt, unde sapiente cum, enim id alias expedita! --}}
                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        <h3 class="text-center" id="loading_num3" style="display:none">
                            {{-- Please wait while system loading leads... --}}
                            <img src="{{asset('assets/images/loader.gif')}}" alt="Loading"
                                class="img-fluid text-center offset-md-6" style="width:35px;">
                        </h3>

                        <div class="form-group">
                            <div id="myModal" class="modal fade" role="dialog" style="margin-top:10%;">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                onclick="close_modal()">&times;</button>
                                            <h4 class="modal-title">Follow Back</h4>
                                        </div>
                                        <div class="modal-body">
                                            <!-- <p>Some text in the modal.</p> -->
                                            <div class="form-group" style="display:block;" id="call_back_at_new">
                                                {{-- <div class="col-md-12 col-md-5">
                                                    <label for="">
                                                        <h5>Call Back At</h5>
                                                    </label>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 form-group ">
                                                    <input type="datetime-local" name="call_back_at_new"
                                                        class="form-control " id="myDatepicker"
                                                        placeholder="Add Later time"
                                                        aria-describedby="inputSuccess2Status2">
                                                </div> --}}
                                                <div class="col-md-12 col-md-5">
                                                    <label for="remarks_new">Remarks</label>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                                    <textarea name="remarks_for_cordination"
                                                        id="remarks_for_cordination" cols="30" rows="10"
                                                        class="form-control">{{old('remarks_for_cordination')}}</textarea>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" value="Follow Up New" class="btn btn-success"
                                                name="follow_up_new" id="follow_up_new" style="display:;"
                                                onclick="SavingActivationLead('{{route('activation.store')}}','ActiveForm','{{route('home')}}')">

                                            <!-- <button type="button" class="btn btn-default" operation-dismiss="modal">Close</button> -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="myModalVer" class="modal fade" role="dialog" style="margin-top:10%;">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            {{-- <button type="button" class="close"
                                                operation-dismiss="modal">&times;</button> --}}
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                onclick="close_modal()">&times;</button>
                                            <h4 class="modal-title">Re Verification Back</h4>
                                        </div>
                                        <div class="modal-body">
                                            <!-- <p>Some text in the modal.</p> -->
                                            <div class="form-group" style="display:block;" id="call_back_at_new">

                                                <div class="col-md-12 col-md-5">
                                                    <label for="remarks_new">Remarks</label>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                                    <textarea name="reverify_remarks" id="reverify_remarks" cols="30"
                                                        rows="10"
                                                        class="form-control">{{old('reverify_remarks')}}</textarea>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" value="Re Verify" class="btn btn-success"
                                                name="follow_up_new" id="follow_up_new" style="display:;"
                                                onclick="SavingActivationLead('{{route('activation.store')}}','ActiveForm','{{route('home')}}')">

                                            <!-- <button type="button" class="btn btn-default" operation-dismiss="modal">Close</button> -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>



                        {!! Form::close() !!}
                        <div id="RejectModalNew" class="modal fade" role="dialog" data-backdrop="static"
                            data-keyboard="false" style="margin-top:10%;">
                            <div class="modal-dialog">
                                {{ Form::open([ 'method'  => 'post','id' => 'RejectMyLead', 'route' => [ 'lead.rejected', $data->lead_id ], 'files' => true ]) }}
                                <input type="hidden" name="lead_id" value="{{$data->lead_id}}">
                                <input type="hidden" name="ver_id" id="ver_id" value="{{$data->ver_id}}"
                                    class="dont_hide">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            onclick="close_modal()">&times;</button>
                                        <h4 class="modal-title">Modal Header</h4>
                                    </div>
                                    <div class="modal-body">
                                        <!-- <p>Some text in the modal.</p> -->
                                        <div class="form-group" style="display:block;" id="Reject_New">
                                            <select name="reject_comment_new" id="reject_comment" class="form-control">
                                                <option value="">Select Reject Reason</option>
                                                <option value="Already Active">Already Active</option>
                                                <option value="No Need">No Need</option>
                                                <option value="Age less than 21">Age less than 21</option>
                                                <option value="Not Interested">Not Interested</option>
                                                <option value="Emriate ID Expired">Emriate ID Expired</option>
                                                <option value="Cap Limit">Cap Limit</option>
                                                <option value="Less Salary">Less Salary</option>
                                                <option value="Bill Pending">Bill Pending</option>
                                                <option value="dont have valid docs">dont have valid docs</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="button" value="Reject" class="btn btn-success reject"
                                            name="reject_new" id="reject_ew" style="display:;" onclick="RejectLeadVer('{{route('lead.rejected')}}', 'RejectMyLead','{{route('home')}}')">
                                        <!-- <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button> -->
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <div id="RejectModalNew1" class="modal fade" role="dialog" data-backdrop="static"
                            data-keyboard="false" style="margin-top:10%;">
                            {{-- {!!
                                        Form::model($operation,['method'=>'post','action'=>['AjaxController@LeadReAssign']])
                                        !!} --}}
                            <form method="POST" id="newpostbaby" enctype="multipart/form-data"
                                class="add-new-post">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                onclick="close_modal()">&times;</button>
                                            <h4 class="modal-title">Re Assign Lead</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group" style="display:block;" id="Reject_New">
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
                                                                    {{ $user->id == $data->assign_to ? 'selected' : '' }}>
                                                                    {{ $user->name }}</option>
                                                                @endforeach


                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                {!! Form::hidden('lead_id', $data->id) !!}
                                                {!! Form::hidden('ver_id', $data->ver_id) !!}

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" value="Assign" class="btn btn-success"
                                                name="reject_new" id="reject_ew" style="display:;"
                                                onclick="AssignJunaid('{{route('lead.re-assign')}}','1','newpostbaby')">
                                        </div>
                                    </div>

                                </div>
                                {{ Form::close() }}
                        </div>
                        {{--  --}}
                         <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin-top:6%" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Number details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  id="UpdateNumber" >
            <div class="modal-body" id="modalXample">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="UpdateNumberFinal('{{route('update.number')}}','UpdateNumber','exampleModal')">Save changes</button>
            </div>
        </form>
            </div>
        </div>
    </div>
    {{--  --}}
    <div id="LaterLeadModal" class="modal fade" role="dialog"
                                                data-backdrop="static" data-keyboard="false" style="margin-top:10%;">
                                                {{-- {!!
                                                Form::model($operation,['method'=>'post','action'=>['AjaxController@LeadReAssign']])
                                                !!} --}}
                                                    <form method="POST" id="LaterLeadForm" enctype="multipart/form-data" class="add-new-post">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                                onclick="close_modal()">&times;</button>
                                                            <h4 class="modal-title">Later Lead</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group" style="display:block;"
                                                                id="Reject_New">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="fom-group">
                                                                            <h6 class="text-left">
                                                                            </h6>
                                                                            <label for="add_location">Later Date:</label>

                                                                            <input type="date" name="later_date" id="later_date" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {!! Form::hidden('lead_id', $data->lead_id) !!}
                                                                {!! Form::hidden('ver_id', $data->ver_id) !!}

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="button" value="Assign"
                                                                class="btn btn-success" name="reject_new"
                                                                id="reject_ew" style="display:;" onclick="LaterLead('{{route('LaterLead')}}','1','LaterLeadForm')">
                                                        </div>
                                                    </div>

                                                </div>
                                                {{ Form::close() }}
                    </div>
                    </form>
                    <!-- END Form Labels on top - Default Style -->

                    <!-- Form Labels on top - Alternative Style -->

                    <!-- END Form Labels on top - Alternative Style -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Labels on top -->
    <!-- END Overview -->
@include('chat.chat-main')


</div>
<!-- END Page Content -->
@endsection
