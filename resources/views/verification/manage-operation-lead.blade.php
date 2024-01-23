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
  {{-- @php
  $emirates = \App\emirate::all(;
   $countries = \App\country_phone_code::all();
   @endphp --}}
  <!-- END Hero -->
                @inject('provider', 'App\Http\Controllers\HomeController')

  <!-- Page Content -->
   <div class="content">

          <!-- Overview -->
                    <form method="post" id="pre-verification-form" onsubmit="return false" enctype="multipart/form-data">
            <input type="hidden" name="cust_id" value="">
            <input class="form-control " id="leadid" value="{{$operation->id}}" placeholder="Lead Number" type="hidden" disabled>
            <input type="hidden" name="lead_id" id="lead_id" value="{{$operation->id}}">
            <input type="hidden" name="lead_no" id="lead_no" value="{{$operation->lead_no}}">
            <div class="row hidden d-none">
                <div class="form-group">
                    <div class="col-md-12">
                        Priority Question:
                    </div>
                    <div class="col-md-12">
                        <label for="ageask">age</label>
                        <input type="checkbox" name="ageask" id="ageask">
                        <label for="Documentsask">Documents</label>
                        <input type="checkbox" name="Documentsask" id="Documentsask">
                        <label for="Salaryask">Salary</label>
                        <input type="checkbox" name="Salaryask" id="Salaryask">
                        <label for="lockingperiodask">Locking period</label>
                        <input type="checkbox" name="lockingperiodask" id="lockingperiodask">
                        <label for="NameAsk">Name</label>
                        <input type="checkbox" name="NameAsk" id="NameAsk">
                        <label for="5daysask">5 days free cancelation</label>
                        <input type="checkbox" name="5daysask" id="5daysask">
                    </div>
                </div>
              </div>
            <div class="table-responsive">

              <!-- kettly -->
              <div class="divTable blueTable">
                <div class="divTableHeading">
                  <div class="divTableRow">
                    <div class="divTableHead">Data Field</div>
                    <div class="divTableHead">Customer Data Field</div>
                    <div class="divTableHead">To be Edit</div>
                    <div class="divTableHead">Verified</div>
                  </div>
                </div>


                <?php


                ?>
                <div class="divTableBody">
                  <!-- 1 -->
                  <div class="divTableRow">
                    <div class="divTableCell" style="width:20%;">
                      <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                      <p> <strong>Customer Name:</strong> </p>
                    </div>
                    <div class="divTableCell" style="width:20%;">
                      <input class="form-control " id="inputSuccess3" placeholder="Customer Name" name="old_cname" placeholder="Customer Number" type="text" disabled value="{{$operation->customer_name}}">
                    </div>
                    <div class="divTableCell" style="width:20%;">
                      <!-- <input type="checkbox"   id="state"> -->
                      <select name="Select Option" id="state" class="form-control" style="width:100px;">
                        <option value="other1">No </option>
                        <option value="other" id="other2">Yes</option>
                      </select>

                    </div>
                    <div class="divTableCell" style="width:20%;">
                    <input class="form-control " id="province" type="text" value="{{$operation->customer_name}}">
                      <input class="form-control " id="province1" name="cname" type="hidden" value="{{$operation->customer_name}}">
                      <input class="form-control " id="" name="cust_id" type="hidden" value="{{$operation->lead_no}}">
                      <script>
                        var myInput = document.getElementById('province');
                        myInput.disabled = true;
                      </script>
                    </div>
                  </div>
                  <!-- 1 -->
                  <!-- 2 -->
                  <div class="divTableRow">
                    <div class="divTableCell" style="width:20%;">
                      <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                      <p> <strong>Customer Number:</strong> </p>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " disabled id="inputSuccess3" placeholder="Customer Name" name="old_cname" placeholder="Customer Number" type="tel" value="{{$operation->customer_number}}" maxlength="12">
                    </div>
                    <div class="divTableCell">
                      <select name="Select Option" id="state2" class="form-control" style="width:100px;">
                        <option value="other1">No </option>
                        <option value="other" id="other2">Yes</option>
                        <!-- <option value="other" id="other1">Yes</option> -->
                      </select>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " id="province2" type="text" value="{{$operation->customer_number}}">
                      <input class="form-control " id="province22" name="cnumber" type="hidden" value="{{$operation->customer_number}}">
                      <script>
                        var myInput = document.getElementById('province2');
                        myInput.disabled = true;
                      </script>
                    </div>
                  </div>
                  <!-- 2 -->
                  <!-- 3 -->
                  <div class="divTableRow">
                    <div class="divTableCell" style="width:20%;">
                      <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                      <p> <strong>Age:</strong> </p>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " disabled id="inputSuccess3" placeholder="Age" name="old_age" placeholder="Customer Number" type="text" value="{{$operation->age}}">
                    </div>
                    <div class=" divTableCell">
                      <select name="Select Option" id="state3" class="form-control" style="width:100px;">
                        <option value="other1">No </option>
                        <option value="other" id="other2">Yes</option>
                        <!-- <option value="other" id="other1">Yes</option> -->
                      </select>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " id="province3" type="text" value="{{$operation->age}}">
                      <input class="form-control " id="province33" name="age" type="hidden" value="{{$operation->age}}">
                      <script>
                        var myInput = document.getElementById('province3');
                        myInput.disabled = true;
                      </script>
                    </div>
                  </div>
                  <div class="divTableRow">
                    <div class="divTableCell" style="width:20%;">
                      <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                      <p> <strong>Gender:</strong> </p>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " disabled id="inputSuccess3" placeholder="Age" name="" placeholder="Customer Number" type="text" value="{{$operation->gender}}">
                    </div>
                    <div class=" divTableCell">
                      <select name="Select Option" id="state_gender" class="form-control" style="width:100px;">
                        <option value="other1">No </option>
                        <option value="other" id="other2">Yes</option>
                        <!-- <option value="other" id="other1">Yes</option> -->
                      </select>
                    </div>
                    <div class="divTableCell">
                    <select name="" id="p_gender3" class="gender form-control" required>
                    <option value="">-- Select Gender --</option>
                        <option value="Male" @if ($operation->gender== "Male") {{ 'selected' }} @endif>Male</option>
                        <option value="Female" @if ($operation->gender == "Female") {{ 'selected' }} @endif>Female</option>
                        <option value="Other" @if ($operation->gender == "Other") {{ 'selected' }} @endif>Other</option>
                    </select>
                      {{-- <input class="form-control " id="province3" type="text" value="{{$operation->age}}"> --}}
                      <input class="form-control " id="p_gender" name="gender" type="hidden" value="{{$operation->gender}}">
                      <script>
                        var myInput = document.getElementById('p_gender3');
                        myInput.disabled = true;
                      </script>
                    </div>
                  </div>
                  <div class="divTableRow">
                    <div class="divTableCell" style="width:20%;">
                      <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                      <p> <strong>Emirates:</strong> </p>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " disabled id="inputSuccess3" placeholder="Age" name="old_age" placeholder="Customer Number" type="text" value="{{$operation->emirates}}">
                    </div>
                    <div class=" divTableCell">
                      <select name="Select Option" id="state_emirates" class="form-control" style="width:100px;">
                        <option value="other1">No </option>
                        <option value="other" id="other2">Yes</option>
                        <!-- <option value="other" id="other1">Yes</option> -->
                      </select>
                    </div>
                    <div class="divTableCell">
                      <select name="emirates" id="province_emirates" class="emirates form-control" required>
                        <option value="{{$operation->emirates}}">
                            {{$operation->emirates}}
                          </option>
                        @foreach($emirates as $emirate)
                        <option value="{{ $emirate->name }}">{{ $emirate->name }}</option>
                    @endforeach
                      </select>
                      <input class="form-control " id="province__emirates" name="emirates" type="hidden" value="{{$operation->emirates}}">
                      <script>
                        var myInput = document.getElementById('province_emirates');
                        myInput.disabled = true;
                      </script>
                    </div>
                  </div>
                  <div class="divTableRow">
                    <div class="divTableCell" style="width:20%;">
                      <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                      <p> <strong>Area:</strong> </p>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " disabled id="inputSuccess3" placeholder="Age" name="old_age" placeholder="Customer Number" type="text" value="{{$operation->area}}">
                    </div>
                    <div class=" divTableCell">
                      <select name="Select Option" id="state_area" class="form-control" style="width:100px;">
                        <option value="other1">No </option>
                        <option value="other" id="other2">Yes</option>
                        <!-- <option value="other" id="other1">Yes</option> -->
                      </select>
                    </div>
                    <div class="divTableCell">
                        <input class="form-control " id="state__area"  type="text" value="{{$operation->area}}">

                      <input class="form-control " id="state_area_hidden" name="area" type="hidden" value="{{$operation->area}}">
                      <script>
                        var myInput = document.getElementById('state__area');
                        myInput.disabled = true;
                      </script>
                    </div>
                  </div>
                  <div class="divTableRow">
                    <div class="divTableCell" style="width:20%;">
                      <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                      <p> <strong>Language:</strong> </p>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " disabled id="inputSuccess3" placeholder="Age" name="old_age" placeholder="Customer Number" type="text" value="{{ $operation->language }}">
                    </div>
                    <div class=" divTableCell">
                      <select name="Select Option" id="state_language" class="form-control" style="width:100px;">
                        <option value="other1">No </option>
                        <option value="other" id="other2">Yes</option>
                        <!-- <option value="other" id="other1">Yes</option> -->
                      </select>
                    </div>
                    <div class="divTableCell">
                      <select name="language" class="form-control " id="province_language">
                        <option value="{{ $operation->language }}">{{ $operation->language }}</option>
                        <option value="">Select Language</option>
                        <option value="English">English</option>
                        <option value="Arabic">Arabic</option>
                        <option value="Hindi/Urdu">Hindi / Urdu</option>
                      </select>
                      <input class="form-control " id="province__language" name="language" type="hidden" value="{{ $operation->language }}">
                      <script>
                        var myInput = document.getElementById('province_language');
                        myInput.disabled = true;
                      </script>
                    </div>
                  </div>
                  <!-- 3 -->
                  <!-- 4 -->
                  <div class="divTableRow">
                    <div class="divTableCell" style="width:20%;">
                      <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                      <p> <strong>Nationality:</strong> </p>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " disabled id="inputSuccess3" placeholder="Nationality" name="old_nation" placeholder="Nationality" type="text" value="{{ $operation->nationality }}" >
                            </div>
                            <div class=" divTableCell">
                      <select name="Select Option" id="state4" class="form-control" style="width:100px;">
                        <option value="other1">No </option>
                        <option value="other" id="other2">Yes</option>
                        <!-- <option value="other" id="other1">Yes</option> -->
                      </select>
                    </div>
                    <div class="divTableCell">
                      <select name="nation" id="province4" class="form-control has-feedback-left">

                        <option value="{{ $operation->nationality }}">{{ $operation->nationality }}</option>
                        @foreach($countries as $country)
                        <option value="{{ $country->name }}">{{ $country->name }}</option>
                    @endforeach
                        <!-- <input class="form-control " id="province4"  type="text" value=""  > -->
                      <input class="form-control " id="province44" name="nation" type="hidden" value="{{$operation->nationality}}">
                        <script>
                          var myInput = document.getElementById('province4');
                          myInput.disabled = true;
                        </script>
                    </div>
                  </div>
                  <!-- 4 -->
                  <!-- 5 -->
                  <div class="divTableRow">
                    <div class="divTableCell" style="width:20%;">
                      <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                      <p> <strong>Original Emirate Id:</strong> </p>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " id="inputSuccess3" disabled name="old_nation" value="{{ $operation->emirate_id }}" type="text" >
                            </div>
                            <div class=" divTableCell">
                      <select name="Select Option" id="state5" class="form-control" style="width:100px;">
                        <option value="other1">No </option>
                        <option value="other" id="other2">Yes</option>
                        <!-- <option value="other" id="other1">Yes</option> -->
                      </select>
                    </div>
                    <div class="divTableCell">
                      <select name="emirate_id" class="form-control has-feedback-left" required id="province5">
                        {{-- <option value=""></option> --}}
                        <option value="">-- Original Emirate Id --</option>
                        <option value="Yes, Customer has original Emirates Id" id="" @if ($operation->emirate_id == "Yes, Customer has original Emirates Id") {{ 'selected' }} @endif>Yes, Customer has original Emirates Id</option>
                        <option value="No" id="" @if ($operation->emirate_id == "No") {{ 'selected' }} @endif>No</option>
                        <!-- <option value="24">24 Months</option> -->
                      </select>
                      <!-- <input class="form-control " id="province5"  type="text" value=""> -->
                      <input class="form-control " id="province55" name="emirate_id" type="hidden" value="{{ $operation->emirate_id }}">
                      <script>
                        var myInput = document.getElementById('province5');
                        myInput.disabled = true;
                      </script>
                    </div>
                  </div>
                  <!-- 5 -->
                  <!-- original Emirate number -->
                  <div class="divTableRow hidden d-none">
                    <div class="divTableCell" style="width:20%;">
                      <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                      <p> <strong>Emirate ID #:</strong> </p>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " id="inputSuccess3" disabled name="old_nation" value="{{ $operation->emirate_num }}" type="text">
                    </div>
                    <div class=" divTableCell">
                      <select name="Select Option" id="state_emirate_num" class="form-control" style="width:100px;">
                        <option value="other1">No </option>
                        <option value="other" id="other2">Yes</option>
                        <!-- <option value="other" id="other1">Yes</option> -->
                      </select>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " id="province_original_id1" data-inputmask="'mask' : '999-9999-9999999-9'" data-validate-length-range="6" data-validate-words="2" name="emirate_number" placeholder="Emirate ID" type="num" value="{{ $operation->emirate_num }}">
                      <input class="form-control " id="province_original_id11" name="emirate_num" type="hidden" value="{{ $operation->emirate_num }}">
                      <script>
                        var myInput = document.getElementById('province_original_id1');
                        myInput.disabled = true;
                      </script>
                    </div>
                  </div>
                  <!-- original Emirate number end -->
                  <!-- 6 -->
                  <div class="divTableRow">
                    <div class="divTableCell" style="width:20%;">
                      <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                      <p> <strong>Additional Documents:</strong> </p>
                    </div>
                    <div class="divTableCell">
                      <input class="form-control " id="inputSuccess3" disabled value="{{ $operation->additional_document }}" name="" type="text">
                    </div>
                    <div class="divTableCell">
                      <select name="Select Option" id="state6" class="form-control" style="width:100px;">
                        <option value="other1">No </option>
                        <option value="other" id="other2">Yes</option>
                        <!-- <option value="other" id="other1">Yes</option> -->
                      </select>
                    </div>
                    <div class="divTableCell">
                      <select name="" id="province6" class="form-control">
                    <option value="No Additional Document Required" id="" class="hideonelife" {{$operation->additional_document == 'No Additional Document Required' ? 'selected' : ''}}>No Additional Document Required</option>
                    <option value="Golden Visa" id="" class="hideonelife" {{$operation->additional_document == 'Golden Visa' ? 'selected' : ''}}>Golden Visa</option>
                        <option value="Salary Certificate" id="" class="hideonelife" {{$operation->additional_document == 'Salary Certificate' ? 'selected' : ''}}>Salary Certificate</option>
                    <option value="Tenancy Contract" id="" {{$operation->additional_document == 'Tenancy Contract' ? 'selected' : ''}}>Tenancy Contract</option>
                    <option value="Utility Bill" id="" class="hideonelife" {{$operation->additional_document == 'Utility Bill' ? 'selected' : ''}}>Utility Bill (Current)</option>
                    {{-- <option value="Credit Card" id="" class="hideonelife" {{$operation->additional_document == 'Credit Slip' ? 'selected' : ''}}>Credit Card</option> --}}
                    <option value="Pay Slip From Exchange" id="" class="hideonelife" {{$operation->additional_document == 'Pay Slip From Exchange' ? 'selected' : ''}}>Pay Slip From Exchange</option>
                    <option value="Title Deeds" id="" class="hideonelife" {{$operation->additional_document == 'Title Deeds' ? 'selected' : ''}}>Title Deeds</option>
                    <option value="Car Registration" id="" class="hideonelife" {{$operation->additional_document == 'Car Registration' ? 'selected' : ''}}>Car Registration</option>
                    <option value="Labour Contract" id="" class="hideonelife" {{$operation->additional_document == 'Labour Contract' ? 'selected' : ''}}>Labour Contracts</option>
                    <option value="Etisalat Postpaid/Elife Account" id="" class="hideonelife" {{$operation->additional_document == 'Etisalat Postpaid/Elife Account' ? 'selected' : ''}}>Etisalat Postpaid/Elife Account</option>
                    <option value="Bank Statement Last 3 Months" id="" class="hideonelife" {{$operation->additional_document == 'Bank Statement Last 3 Months' ? 'selected' : ''}}>Bank Statement Last 3 Months</option>
                    <option value="Customer has Existing billing (account 6 months old)" {{$operation->additional_document == 'Customer has Existing billing (account 6 months old)' ? 'selected' : ''}} id="" class="hideonelife">Customer has Existing billing (account 6 months old)
                    </option>
                    <option value="DU Bill Last 3 Months" {{$operation->additional_document == 'DU Bill Last 3 Months' ? 'selected' : ''}} id="" class="hideonelife">DU Bill Last 3 Months
                    </option>
                      </select>
                      <!-- <input class="form-control " id="province6"  type="file" value=""  > -->
                      <input class="form-control " id="province66" name="additional_documents" type="hidden" value="{{$operation->additional_document}}">
                      <script>
                        var myInput = document.getElementById('province6');
                        myInput.disabled = true;
                      </script>
                    </div>
                  </div>
                </div>
                <!-- kettly -->
                <div class="divTableRow">
                  <div class="divTableCell" style="width:20%;">
                    <!-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Device Name</label> -->
                    <p> <strong>Sim Type:</strong> </p>
                  </div>
                  <div class="divTableCell">
                  <input class="form-control " id="inputSuccess3" disabled placeholder="Sim Type" name="sim_type" type="text" value="{{$operation->sim_type}}">
                  </div>
                  <div class="divTableCell">
                    <select name="Select Option" id="state7" class="form-control" style="width:100px;">
                      <option value="other1">No </option>
                      <option value="other" id="other2">Yes</option>
                      <!-- <option value="other" id="other1">Yes</option> -->
                    </select>
                  </div>
                  <div class="divTableCell">

                    <input type="hidden" name="call_back_ajax">
                    <select name="simtype" id="province7" class="sim_type form-control" required>
                      <option value="">-- Product Type --</option>
                      <option value="New" @if ($operation->sim_type == "New") {{ 'selected' }} @endif>New</option>
                      <option value="MNP" @if ($operation->sim_type == "MNP") {{ 'selected' }} @endif>MNP</option>
                      <option value="Elife" @if ($operation->sim_type == "Elife") {{ 'selected' }} @endif>Elife</option>
                      <option value="HomeWifi" @if ($operation->sim_type == "HomeWifi") {{ 'selected' }} @endif>Home Wifi</option>
                    </select>
                    <input type="hidden" name="total" value=">">
                    <input type="hidden" name="monthly_payment" value="">
                    <!-- <input class="form-control " id="province7"  type="text" value="  > -->
                    <input class="form-control " id="province77" name="sim_type" type="hidden" value="{{$operation->sim_type}}">
                    <script>
                      var myInput = document.getElementById('province7');
                      myInput.disabled = true;
                    </script>
                  </div>
                </div>
                <!-- call_back_ajax -->

              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 form-group ">
                <input type="hidden" name="saler_id" id="saler_id" value="{{$operation->saler_id}}">
                <input type="hidden" name="call_back_ajax" class="call_back_ajax">
              {{-- <input type="hidden" name="gender" value="{{$operation->gender}}" class="gender"> --}}

              <div class="container hidden d-none" style="background:#EEEEEE;border:1px solid #1C6EA4">
                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                  <h4 class="">Pre Check Status</h4>
                  <h3 class="details red">
                    {{$operation->pre_check_status}}
                  </h3>
                </div>
                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                  <h4 class="">Pre Check Remarks</h4>
                  <h3 class="details red">
                    {{$operation->pre_check_remarks}}
                  </h3>
                </div>
              </div>
               @if($operation->sim_type == 'Elife' || $operation->sim_type == 'HomeWifi')
                <div class="container row" style="background:#EEEEEE;border:1px solid #1C6EA4">
                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                  <h4 class="">Location</h4>
                </div>
                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                  <h4 class="">
                    <input type="text" name="map" id="map" value="{{$operation->location}}" class="form-control" disabled>
                  </h4>
                  <h3 class="details red">
                  </h3>
                </div>
              </div>
              @endif



              </div>

               @if($operation->sim_type == 'Elife' || $operation->sim_type == 'HomeWifi')
               @include('dashboard.include.verify-elife')
               @elseif($operation->sim_type == 'MNP')
               @include('dashboard.include.verify-mnp')
               @elseif($operation->sim_type == 'New')
               @include('verification.include.verify-new')
               @endif

              <div class="container">
                <div class="row">
                  <div class="col-md-12">
                      <div class="form-group audio_action row" >
                        <div class="col-sm-4 col-xs-12 col-md-4">
                          <label for="audio1" data-toggle="tooltip" title="Audio 1 is Mandatory">Audio 1</label>
                          <input type="file" name="audio[]" id="audio1" class=""  accept="audio/*">
                          </div>
                          {{-- <div class="col-sm-4 col-xs-12 col-md-4">
                            <label for="audio1">Audio 2 <strong style="color:red" data-toggle="tooltip" title="Audio 2nd Optional">Optional</strong></label>
                            <input type="file" name="audio[]" id="audio1" class=""  accept="audio/*">

                            </div>
                            <div class="col-sm-4 col-xs-12 col-md-4">
                                <label for="audio1">Audio <strong style="color:red" data-toggle="tooltip" title="Audio 3rd Optional">optional</strong></label>
                                <input type="file" name="audio[]" id="audio1" class=""  accept="audio/*">

                        </div> --}}


                      </div>
                      <div class="form-group audio_action row" >
                        <div class="col-sm-4 col-xs-12 col-md-4">
                          <label for="audio1" data-toggle="tooltip" title="Audio 1 is Mandatory">WhatsApp Attachement</label>
                          {{-- <input type="file" name="audio[]" id="audio1" class=""  accept="audio/*"> --}}
                          <input type="file" name="whatsapp" id="whatsapp" accept="image/*">
                          </div>
                          {{-- <div class="col-sm-4 col-xs-12 col-md-4">
                            <label for="audio1">Audio 2 <strong style="color:red" data-toggle="tooltip" title="Audio 2nd Optional">Optional</strong></label>
                            <input type="file" name="audio[]" id="audio1" class=""  accept="audio/*">

                            </div>
                            <div class="col-sm-4 col-xs-12 col-md-4">
                                <label for="audio1">Audio <strong style="color:red" data-toggle="tooltip" title="Audio 3rd Optional">optional</strong></label>
                                <input type="file" name="audio[]" id="audio1" class=""  accept="audio/*">

                        </div> --}}


                      </div>
                      {{-- <label for="remarks">Remarks: </label>
                      <input type="text" name="remarks_process_new" id="remarks_process_new" value="{{$operation->remarks}}" class="form-control"> --}}

                  </div>
                </div>
              </div>
              <br>
              <br>
              <br>
              <!-- end of table -->
              <!-- pika booo -->
               @if($operation->sim_type == 'Elife' || $operation->sim_type == 'HomeWifi')
                <input type="button" value="Non Verified" class="btn btn-danger" name="follow_up" id="follow_up" data-bs-toggle="modal" data-bs-target="#myModalF">
                <div class="btn btn-group">
                    <input type="button" value="Save Changes" class="btn btn-success" name="upload" onclick="VerifyLead('{{route('SaveChanges')}}','pre-verification-form','{{route('home')}}')">
                </div>
                <div class="btn btn-group">
                    <input type="button" value="Verified" class="btn btn-success" name="upload" onclick="VerifyLead('{{route('verification.store')}}','pre-verification-form','{{route('home')}}')">
                </div>

               @else
              <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                  <!-- <button type="button" class="btn btn-primary">Cancel</button> -->
                  <!-- <button class="btn btn-primary" type="reset">Reset</button> -->
                  @if($operation->status == '1.11')
                  <input type="button" value="Verify and Active" class="btn btn-success" name="upload" onclick="VerifyLead('{{route('verification.active.store')}}','pre-verification-form','{{route('home')}}')">
                  @else
                  <button type="button" class="btn btn-info" onclick="VerifyLead('{{route('verified.at.location')}}','pre-verification-form','{{route('home')}}')">Verify At Location</button>
                  <button type="button" class="btn btn-info" onclick="VerifyLead('{{route('verified.at.whatsapp')}}','pre-verification-form','{{route('home')}}')">Verified Via WhatsApp</button>
                  {{-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Verified Need Today</button> --}}
                  {{-- <button type="button" class="btn btn-info" onclick="VerifyLead('{{route('verified.today')}}','pre-verification-form','{{route('home')}}')">Verified Need Today</button> --}}
                  {{-- <button type="button" class="btn btn-danger" onclick="VerifyLead('{{route('not.answer')}}','pre-verification-form','{{route('home')}}')">Not Answer</button> --}}
                <input type="button" value="Verified" class="btn btn-success" name="upload" onclick="VerifyLead('{{route('verification.store')}}','pre-verification-form','{{route('home')}}')">
                <input type="button" value="Non Verified" class="btn btn-danger" name="follow_up" id="follow_up" data-bs-toggle="modal" data-bs-target="#myModalF">
                <div class="btn btn-group">
                    <input type="button" value="Save Changes" class="btn btn-success" name="upload" onclick="VerifyLead('{{route('SaveChanges')}}','pre-verification-form','{{route('home')}}')">
                </div>
                    {{-- <input type="button" value="Pending" class="btn btn-danger" name="back" id="back" onclick="javascript:location.href='{{route('home')}}'"> --}}
                  @endif

                  {{-- <button type="button" class="btn btn-success hidden" data-toggle="modal" data-target="#myModal">Later</button> --}}
                  {{-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#RejectModalNew">Reject</button> --}}
                  <!-- <input type="submit" value="Reject" class="btn btn-success" name="reject"> -->
                  <!-- <button type="submit" class="btn btn-success" name="upload">Submit</button> -->
                </div>
              </div>
              @endif
            </div>
            <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
            <!-- my modal reject -->
            <div id="RejectModalNew" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" style="margin-top:10%;">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="close_modal()">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                  </div>
                  <div class="modal-body">
                    <!-- <p>Some text in the modal.</p> -->
                    <div class="form-group" style="display:block;" id="Reject_New">
                      <select name="reject_comment_new" id="reject_comment" class="form-control">
                        <option value="">Select Reject Reason</option>
                        <option value="Already Active">Already Active</option>
                        <option value="No Need">No Need</option>
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
                    {{-- <input type="button" value="Reject" class="btn btn-success reject" name="reject_new" id="reject_ew" style="display:;" onclick="test_reject()"> --}}
                    <input type="button" value="Reject" class="btn btn-success reject" name="reject_new" id="reject_ew" style="display:;" onclick="RejectLeadVer('{{route('verification.store')}}','pre-verification-form','{{route('home')}}')">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                  </div>
                </div>

              </div>
            </div>
            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" style="margin-top:10%;" >
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="close_modal()">&times;</button>
                    <h4 class="modal-title">Choose Schedule Timing</h4>
                  </div>
                  <div class="modal-body">
                    <!-- <p>Some text in the modal.</p> -->
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                          <h6>Choose Start Date </h6>
                        <!-- Linked Picker Parent -->
                        <div class="form-group">
                          <div class="input-group date" id="datetimepicker6">
                            <input type="text" class="form-control" name="start_date" id="start_date" readonly value="{{date('d/m/Y')}}">
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                          <h6>Choose Start Time </h6>
                        <!-- Linked Picker Parent -->
                        <div class="form-group">
                          <div class="input-group date" id="datetimepicker6">
                            <input type="time" class="form-control" name="start_time" id="start_date"  >
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                          </div>
                        </div>
                        <span class="red" style="color:red;text-align:center">System Automatically Add 2 Hrs from choosen time</span>
                      </div>

                    </div>
                  </div>
                  <div class="modal-footer">
                  <button type="button" class="btn btn-info" onclick="VerifyLead('{{route('verified.today')}}','pre-verification-form','{{route('home')}}')">Verified Need Today</button>
                    {{-- <input type="submit" value="Follow Up" class="btn btn-success" name="later"> --}}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
            <!-- FOLLOW UP -->
            <div id="myModalF" class="modal fade" role="dialog" style="margin-top:10%;">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                    <!-- <p>Some text in the modal.</p> -->
                    <div class="row">
                      <div class="col-md-12 col-sm-5 col-xs-12 form-group">
                        <h3>
                            Non Verified Remarks
                        </h3>
                        <div class="form-group">
                            <textarea name="remarks_for_cordination" id="remarks_for_cordination" cols="10" rows="2" class="form-control">{{old('remarks_for_cordination')}}</textarea>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="modal-footer">
                    <input type="submit" value="Submit" class="btn btn-success" name="later" onclick="RejectLeadVer('{{route('verification.store')}}','pre-verification-form','{{route('home')}}')">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
            <!-- modal later -->
<style>
    .select2-close-mask{
    z-index: 2099;
}
.select2-dropdown{
    z-index: 3051;
}
</style>
            <!-- modal reject end -->
          </form>
          </div>
          <!-- END Overview -->
          <div class="modal fade bd-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="exampleModal">

 {{-- <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin-top:6%" data-backdrop="static" data-keyboard="false" style="overflow: hidden"> --}}
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Number details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  id="UpdateNumber" >
            <div class="modal-body" id="modalXample">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" onclick="UpdateNumberFinal('{{route('update.number')}}','UpdateNumber','exampleModal')">Save changes</button> --}}
            </div>
        </form>
            </div>
        </div>
    </div>
     <style>
  div.blueTable {
    border: 1px solid #1C6EA4;
    background-color: #EEEEEE;
    width: 100%;
    text-align: left;
    border-collapse: collapse;
  }

  .divTable.blueTable .divTableCell,
  .divTable.blueTable .divTableHead {
    border: 1px solid #AAAAAA;
    padding: 3px 2px;
  }

  .divTable.blueTable .divTableBody .divTableCell {
    font-size: 13px;
  }

  .divTable.blueTable .divTableHeading {
    background: #34495E;
    border-bottom: 1px solid #444444;
  }

  .divTable.blueTable .divTableHeading .divTableHead {
    font-size: 15px;
    font-weight: bold;
    color: #FFFFFF;
    border-left: 1px solid #D0E4F5;
  }

  .divTable.blueTable .divTableHeading .divTableHead:first-child {
    border-left: none;
  }

  .blueTable .tableFootStyle {
    font-size: 14px;
  }

  .blueTable .tableFootStyle .links {
    text-align: right;
  }

  .blueTable .tableFootStyle .links a {
    display: inline-block;
    background: #1C6EA4;
    color: #FFFFFF;
    padding: 2px 8px;
    border-radius: 5px;
  }

  .blueTable.outerTableFooter {
    border-top: none;
  }

  .blueTable.outerTableFooter .tableFootStyle {
    padding: 3px 5px;
  }

  /* DivTable.com */
  .divTable {
    display: table;
  }

  .divTableRow {
    display: table-row;
  }

  .divTableHeading {
    display: table-header-group;
  }

  .divTableCell,
  .divTableHead {
    display: table-cell;
  }

  .divTableHeading {
    display: table-header-group;
  }

  .divTableFoot {
    display: table-footer-group;
  }

  .divTableBody {
    display: table-row-group;
  }
</style>
@include('chat.chat-main')

        </div>
  <!-- END Page Content -->
@endsection
