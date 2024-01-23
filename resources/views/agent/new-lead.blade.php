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
                    <form  method="POST" onsubmit="return false;" id="ActiveForm">
                        <div class="container row">
                            <div class="mb-4 col-lg-4">
                                <label class="form-label"
                                    for="example-ltf-email">{{ __('Customer Name') }}</label>
                                <input class="form-control " id="cname" placeholder="Customer Name" name="cname"
                                    type="text" required value="{{ old('cname') }}">
                                <input type="hidden" name="type" class="channel_type" id="type" value="{{env('channel_type')}}">
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label class="form-label"
                                    for="example-ltf-password">{{ __('Customer Number') }}</label>
                                <input class="form-control " placeholder="Customer Number i.e 0551234567" name="cnumber"
                                    maxlength="10" required type="tel"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    onkeypress="return isNumberKey(event) " onkeyup="TestNumber()" id="customer_number"
                                    value="{{ old('cnumber') }}" data-validate-length-range="6"
                                    data-validate-words="2" id="customer_number" />
                                <input type="hidden" name="number_test"
                                    value="{{ route('number.tester') }}" id="number_tester">
                                <p style="color:red;display:none;" id="dpExist">
                                    Number Already Exist
                                </p>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="age">Customer Age</label>
                                <input class="form-control " id="age" placeholder="Customer Age not less than 21"
                                    name="age" required type="number" required onkeypress="return isNumberKey(event)"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    maxlength="2" value="{{ old('age') }}">
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="c_select">Country</label>
                                <select name="nation" id="c_select" class="form-control select2" required>

                                    @foreach($countries as $country)
                                        <option value="{{ $country->name }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="simtype">Product Type</label>

                                <select name="simtype" id="simtype" class="sim_type form-control" required>
                                    <option value="">-- Product Type --</option>
                                    <option value="New" @if (old('simtype')=="New" ) {{ 'selected' }}
                                        @endif>New</option>
                                    <option value="MNP" @if (old('simtype')=="MNP" ) {{ 'selected' }}
                                        @endif>MNP</option>
                                </select>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="gender">Gender</label>

                                <select name="gender" id="gender" class="gender form-control" required>
                                    <option value="">-- Select Gender --</option>
                                    <option value="Male" @if (old('gender')=="Male" ) {{ 'selected' }}
                                        @endif>Male</option>
                                    <option value="Female" @if (old('gender')=="Female" )
                                        {{ 'selected' }} @endif>Female</option>
                                    <option value="Other" @if (old('gender')=="Other" )
                                        {{ 'selected' }} @endif>Other</option>
                                </select>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="emirate">Select Emirate</label>

                                <select name="emirates" id="emirate" class="emirates form-control" required>
                                    <option value="">Select Emirates</option>
                                    @foreach($emirates as $emirate)
                                        <option value="{{ $emirate->name }}" @if (old('emirates')==$emirate->name)
                                            {{ 'selected' }} @endif>{{ $emirate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="emirate">Select Area</label>

                                <input type="text" name="area" id="area_name" class="form-control">
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="emirate_id">Select Emirate ID</label>

                                <select name="emirate_id" class="form-control " required id="emirate_id">
                                    <option value="">-- Original Emirate Id --</option>
                                    <option value="Yes, Customer has original Emirates Id" id="">Yes, Customer has
                                        original Emirates Id
                                    </option>
                                    <option value="No" id="" @if (old('emirate_id')=="No" )
                                        {{ 'selected' }} @endif>No</option>
                                    <!-- <option value="24">24 Months</option> -->
                                </select>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="credit_salary">Additional Documents</label>
                                <select name="additional_document" class="form-control " id="credit_salary">
                                    <option value="No Additional Document Required" id="" class="hideonelife"
                                        {{ old('additional_document') == 'No Additional Document Required' ? 'selected' : '' }}>
                                        No Additional Document Required</option>
                                    <option value="Golden Visa" id="" class="hideonelife"
                                        {{ old('additional_document') == 'Golden Visa ' ? 'selected' : '' }}>
                                        Golden Visa</option>
                                    <option value="Salary Certificate" id="" class="hideonelife"
                                        {{ old('additional_document') == 'Salary Certificate ' ? 'selected' : '' }}>
                                        Salary Certificate</option>
                                    <option value="Tenancy Contract" id=""
                                        {{ old('additional_document') == 'Renancy Contract' ? 'selected' : '' }}>
                                        Tenancy Contract</option>
                                    <option value="Utility Bill" id="" class="hideonelife"
                                        {{ old('additional_document') == 'Utility Bill' ? 'selected' : '' }}>
                                        Utility Bill (Current)</option>
                                    {{-- <option value="Credit Card" id="" class="hideonelife" {{old('additional_document') == 'Credit Slip' ? 'selected' : '' }}>Credit
                                    Card</option> --}}
                                    <option value="Pay Slip From Exchange" id="" class="hideonelife"
                                        {{ old('additional_document') == 'Pay Slip From Exchange' ? 'selected' : '' }}>
                                        Pay Slip From Exchange</option>
                                    <option value="Title Deeds" id="" class="hideonelife"
                                        {{ old('additional_document') == 'Title Deeds' ? 'selected' : '' }}>
                                        Title Deeds</option>
                                    <option value="Car Registration" id="" class="hideonelife"
                                        {{ old('additional_document') == 'Car Registration' ? 'selected' : '' }}>
                                        Car Mulkiya</option>
                                    <option value="Labour Contract" id="" class="hideonelife"
                                        {{ old('additional_document') == 'Labour Contract' ? 'selected' : '' }}>
                                        Labour Contracts</option>
                                    <option value="Etisalat Postpaid/Elife Account" id="" class="hideonelife"
                                        {{ old('additional_document') == 'Etisalat Postpaid/Elife Account' ? 'selected' : '' }}>
                                        Etisalat Postpaid/Elife Account</option>
                                    <option value="Bank Statement Last 3 Months" id="" class="hideonelife"
                                        {{ old('additional_document') == 'Bank Statement Last 3 Months' ? 'selected' : '' }}>
                                        Bank Statement Last 3 Months</option>
                                    <option value="Customer has Existing billing (account 6 months old)"
                                        {{ old('additional_document') == 'Customer has Existing billing (account 6 months old)' ? 'selected' : '' }}
                                        id="" class="hideonelife">Customer has Existing billing (account 6 months old)
                                    </option>
                                    <option value="DU Bill Last 3 Months"
                                        {{ old('additional_document') == 'DU Bill Last 3 Months' ? 'selected' : '' }}
                                        id="" class="hideonelife">DU Bill Last 3 Months
                                    </option>
                                </select>
                            </div>
                            <div class="mb-4 col-md-4">
                                <label for="hideme_document">Language</label>
                                <select name="language" class="form-control " required id="language">
                                    <option value="">Select Language</option>
                                    <option value="English"
                                        {{ old('language') == 'English' ? 'selected' : '' }}>
                                        English</option>
                                    <option value="Arabic"
                                        {{ old('language') == 'Arabic' ? 'selected' : '' }}>
                                        Arabic</option>
                                    <option value="Hindi/Urdu">Hindi / Urdu</option>
                                </select>
                            </div>
                        </div>
                        <div class="container row">
                             <div class="mb-4 col-md-4">
                                <label for="shared_with">Shared With</label>
                                <select name="shared_with" id="shared_with" class="form-control">
                                    <option value="">Select Option</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('shared_with') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}</option>
                                    @endforeach

                                </select>
                        </div>
                        <div class="form-group">
                  <label for="customer_provider" style="color:red;">Customer Will Provide Location to Agent</label>
                  <input type="checkbox" name="customer_provider" id="customer_provider" checked>
              </div>
              <div class="container-fluid" style="border:1px solid black; padding:20px 30px;">

                                <div class="container-fluid">
                                    <div class="row">
                                    <div id="location_error"></div>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Customer Location Url" name="add_location" id="add_location"  onkeyup="check_location_url()" value="https://maps.google.com?q=0,0">
                                            <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="check_location_url()" id="checker">Fetch Location</button>
                                        </div>
                                    </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fom-group">
                                            <label for="add_location">Add Latitude and Langitude</label>
                                            <input type="text" class="form-control" id="add_lat_lng" name="add_lat_lng" value="0,0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                        <div class="col-md-12">
                            <span class="red" style="color:green" onclick="ConfirmLocationURL()">Confirm Location URL</span>
                        </div>
                    </div>
                                <h6>Choose Schedule Time </h6>
                                {{--  --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fom-group">
                                            <label for="add_location">Start Date</label>
                                            <input type="text" class="form-control" name="start_date" id="start_date" readonly value="{{date('d/m/Y')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fom-group">
                                            <label for="add_location">Start Time</label>
                                            <input type="time" class="form-control" name="start_time" id="start_date"  >
                                        </div>
                                    </div>
                                </div>
                                <span class="red" style="color:red;text-center">System Automatically Add 2 Hrs from choosen time</span>

                                <div class="row hidden d-none">
                                    <div class="col-md-6">
                                        <div class="fom-group">
                                            <label for="add_location">Allocate To:</label>

                                            <select name="assing_to" id="assing_to" class="form-control">
                                                <option value="">Allocate to</option>
                                                    <option value="136" {{ old('assign_to') == '136' ? 'selected' : 'selected' }}>Hassan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                {{-- <div class="row">
                                    <div class="container-fluid">
                                        <button class="btn btn-success" type="button" name="submit" onclick="VerifyLead('{{route('lead-location.store')}}','pre-verification-form','{{route('all.pending','AllCord')}}')">Proceed</button>
                                        <button class="btn btn-success" type="button" name="follow" id="follow_up" data-toggle="modal" data-target="#myModal">Follow</button>
                                        <button class="btn btn-success" type="button" name="follow" id="follow_up" data-toggle="modal" data-target="#myModalVer">Re Verification</button>
                                        <button class="btn btn-info" type="button" data-toggle="modal" data-target="#RejectModalNew">Reject</button>
                                    </div>
                                </div> --}}
                            </div>
                                @include('agent.ajax.new')

                                 <div class="alert alert-danger print-error-msg mt-4eb." style="display:none">
                            <ul></ul>
                        </div>

                            {{-- <div class="mb-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div> --}}
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


</div>
<!-- END Page Content -->
@endsection
