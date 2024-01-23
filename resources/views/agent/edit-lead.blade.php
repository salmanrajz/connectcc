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
                            <div class="mb-4 col-lg-4">
                                <label for="age">Customer Age</label>
                                <input class="form-control " id="age" placeholder="Customer Age not less than 21"
                                    name="age" required type="number" required onkeypress="return isNumberKey(event)"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    maxlength="2" value="{{ $data->age }}">
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="c_select">Country</label>
                                <select name="nation" id="c_select" class="form-control select2" required>

                                    @foreach($countries as $country)
                                        <option value="{{ $country->name }}" @if ($data->nationality==$country->name)
                                            {{ 'selected' }} @endif>{{ $country->name }}</option>
                                    @endforeach
                                </select>
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
                            <div class="mb-4 col-lg-4">
                                <label for="emirate">Select Emirate</label>

                                <select name="emirates" id="emirate" class="emirates form-control" required>
                                    <option value="">Select Emirates</option>
                                    @foreach($emirates as $emirate)
                                        <option value="{{ $emirate->name }}" @if ($data->emirates==$emirate->name)
                                            {{ 'selected' }} @endif>{{ $emirate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="emirate">Select Area</label>

                                <input type="text" name="area" id="area_name" class="form-control" value="{{$data->area}}">
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="emirate_id">Select Emirate ID</label>

                                <select name="emirate_id" class="form-control " required id="emirate_id">
                                    <option value="">-- Original Emirate Id --</option>
                                    <option value="Yes, Customer has original Emirates Id" id=""
                                    @if ($data->emirate_id=="Yes, Customer has original Emirates Id" )
                                        {{ 'selected' }} @endif
                                    >Yes, Customer has
                                        original Emirates Id
                                    </option>
                                    <option value="No" id="" @if ($data->emirate_id=="No" )
                                        {{ 'selected' }} @endif>No</option>
                                    <!-- <option value="24">24 Months</option> -->
                                </select>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label for="credit_salary">Additional Documents</label>
                                <select name="additional_document" class="form-control " id="credit_salary">
                                    <option value="No Additional Document Required" id="" class="hideonelife"
                                        {{ $data->additional_document == 'No Additional Document Required' ? 'selected' : '' }}>
                                        No Additional Document Required</option>
                                    <option value="Golden Visa" id="" class="hideonelife"
                                        {{ $data->additional_document == 'Golden Visa' ? 'selected' : '' }}>
                                        Golden Visa</option>
                                    <option value="Salary Certificate" id="" class="hideonelife"
                                        {{ $data->additional_document == 'Salary Certificate' ? 'selected' : '' }}>
                                        Salary Certificate</option>
                                    <option value="Tenancy Contract" id=""
                                        {{ $data->additional_document == 'Renancy Contract' ? 'selected' : '' }}>
                                        Tenancy Contract</option>
                                    <option value="Utility Bill" id="" class="hideonelife"
                                        {{ $data->additional_document == 'Utility Bill' ? 'selected' : '' }}>
                                        Utility Bill (Current)</option>
                                    {{-- <option value="Credit Card" id="" class="hideonelife" {{$data->additional_document == 'Credit Slip' ? 'selected' : '' }}>Credit
                                    Card</option> --}}
                                    <option value="Pay Slip From Exchange" id="" class="hideonelife"
                                        {{ $data->additional_document == 'Pay Slip From Exchange' ? 'selected' : '' }}>
                                        Pay Slip From Exchange</option>
                                    <option value="Title Deeds" id="" class="hideonelife"
                                        {{ $data->additional_document == 'Title Deeds' ? 'selected' : '' }}>
                                        Title Deeds</option>
                                    <option value="Car Registration" id="" class="hideonelife"
                                        {{ $data->additional_document == 'Car Registration' ? 'selected' : '' }}>
                                        Car Mulkiya</option>
                                    <option value="Labour Contract" id="" class="hideonelife"
                                        {{ $data->additional_document == 'Labour Contract' ? 'selected' : '' }}>
                                        Labour Contracts</option>
                                    <option value="Etisalat Postpaid/Elife Account" id="" class="hideonelife"
                                        {{ $data->additional_document == 'Etisalat Postpaid/Elife Account' ? 'selected' : '' }}>
                                        Etisalat Postpaid/Elife Account</option>
                                    <option value="Bank Statement Last 3 Months" id="" class="hideonelife"
                                        {{ $data->additional_document == 'Bank Statement Last 3 Months' ? 'selected' : '' }}>
                                        Bank Statement Last 3 Months</option>
                                    <option value="Customer has Existing billing (account 6 months old)"
                                        {{ $data->additional_document == 'Customer has Existing billing (account 6 months old)' ? 'selected' : '' }}
                                        id="" class="hideonelife">Customer has Existing billing (account 6 months old)
                                    </option>
                                    <option value="DU Bill Last 3 Months"
                                        {{ $data->additional_document == 'DU Bill Last 3 Months' ? 'selected' : '' }}
                                        id="" class="hideonelife">DU Bill Last 3 Months
                                    </option>
                                </select>
                            </div>
                            <div class="mb-4 col-md-4">
                                <label for="hideme_document">Language</label>
                                <select name="language" class="form-control " required id="language">
                                    <option value="">Select Language</option>
                                    <option value="English"
                                        {{ $data->language == 'English' ? 'selected' : '' }}>
                                        English</option>
                                    <option value="Arabic"
                                        {{ $data->language == 'Arabic' ? 'selected' : '' }}>
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
                                            {{ $data->shared_with == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}</option>
                                    @endforeach

                                </select>
                        </div>
                                @include('agent.ajax.edit-new')



                                {{-- @include('agent.ajax.new') --}}

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
@include('chat.chat-main')


</div>
<!-- END Page Content -->
@endsection
