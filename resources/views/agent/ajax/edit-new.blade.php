<div class="package_name new_package"
>
    {{-- <input type="hidden" name="AjaxUrl" value="{{ route('ajaxRequest.post') }}"
    id="AjaxUrl"> --}}
    <input type="hidden" name="AjaxUrl2" value="{{ route('ajaxRequest.PlanType') }}"
    id="AjaxUrl2">
    {{-- <input type="hidden" name="AjaxUrl3" value="{{ route('ajaxRequest.checkNumData') }}"
    id="AjaxUrl3"> --}}
    <input type="hidden" name="CheckPackageName" value="{{ route('ajaxRequest.CheckPackageName') }}"
    id="CheckPackageName">
    {{-- <input type="hidden" name="CheckChannelPartner" value="{{ route('CheckChannelPartner') }}"
    id="CheckChannelPartner"> --}}

    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <label class="center">
                Postpaid Package
            </label>
        </div>
    </div>
    @if ($data->selected_number != "")
    @foreach(explode(',', $data->selected_number) as $key => $info)
    {{-- <option>{{$info}}</option> --}}
    {{-- $plan = $question->select_plan; --}}
    {{-- {{$data->select_plan[$key]}} --}}
    @php
        $sel_plan = explode(",",$data->select_plan);
        $selected_number = explode(",",$data->selected_number);
        // $pay_status = explode(",",$data->pay_status);
        // $pay_charges = explode(",",$data->pay_charges);
    @endphp
@inject('provider', 'App\Http\Controllers\AjaxController')

    <!-- form-group end -->
    <div class="form-group jackson_action row" id="klon1">

        <div class="col-md-4 col-sm-12 col-xs-12 form-group salman_ahmed">
            <label for="mytypeval">Select Number</label>

            <input class="form-control " id="salman_ahmed" placeholder="Selected Number" name="selnumber[]" type="text" required maxlength="12" value="{{$selected_number[$key]}}" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" onkeypress="return isNumberKey(event)" readonly>

                             Number Status: {{$provider::status_fetch($selected_number[$key])}}

        </div>


        <!-- Customer number end -->
        {{-- <span class=""> --}}

        <div class="col-md-6 col-sm-12 col-xs-12 form-group ">
            <label for="mytypeval">Select Plan</label>
            <select name="plan_new[]" id="c__select" class="form-control c__select">
                <option value="">Select Plan</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ $sel_plan[$key] == $plan->id ? 'selected' : '' }}>{{ $plan->plan_name }}</option>
                @endforeach
                <!-- <option value="250">250</option> -->
            </select>
        </div>
        {{-- </span> --}}
        <div class="item col-md-2 col-sm-12 col-xs-12 form-group hidden d-none">
            <select name="activation_charges_new[]" id="activation_charges" class="activation_charges form-control">
                <option value="">Select Amount</option>
                <option value="Free" selected>Free</option>
            </select>
        </div>
        <div class="col-md-2 col-sm-12 col-xs-12 form-group  hidden d-none">
            <input type="text" name="activation_rate_new[]" id="activation_rate" class="activation_rate form-control"
                placeholder="I.E 130 AED" value="Free" onkeypress="return isNumberKey(event)">
        </div>

    </div>
    @endforeach
    @endif


    <div class="col-md-6 col-sm-6 col-xs-6 form-group rajput">
        <a class="btn btn-primary" id="sumebutton" style="color:#fff;display:none;">
            Add New Number
        </a>
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
                                    <div class="col-md-12">
                                        <div class="input-group mb-3">
                                        @php $leadlocation = \App\Models\lead_location::where('lead_id',$data->id)->first() @endphp
                                        @if($leadlocation)
                                        <a href="{{$leadlocation->location_url}}" class="btn btn-success" target="_blank">View
                                        Location URL</a>
                                            <input type="text" class="form-control" placeholder="Customer Location Url" name="add_location" id="add_location"  onkeyup="check_location_url()" value="https://maps.google.com?q={{$leadlocation->lat}},{{$leadlocation->lng}}">
                                            <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="check_location_url()" id="checker">Fetch Location</button>
                                        @endif
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
                                            <label for="add_location">Start Time </label>
                                            <input type="time" class="form-control" name="start_time" id="start_date"  value="{{$data->appointment_from}}">
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

    <div class="col-md-12 ">
        <h4>Remarks *</h4>
        <div class="form-group">
            <textarea name="remarks_process_new" id="remarks_process" cols="30" rows="10"
                class="form-control remarks_process_new">Please Verify</textarea>
        </div>
    </div>
    <div class="form-group">
        <div id="myModal" class="modal fade" role="dialog" style="margin-top:10%;" data-backdrop="static"
            data-keyboard="false">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                            onclick="close_modal()">&times;</button>
                        <h4 class="modal-title">Follow Up</h4>
                    </div>
                    <div class="modal-body">
                        <!-- <p>Some text in the modal.</p> -->
                        <div class="form-group row" style="display:block;" id="call_back_at_new">
                            <div class="col-md-1 col-md-12">
                                <label for="">
                                    <h5>Call Back At</h5>
                                </label>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group ">
                                <input type="hidden" name="call_back_ajax" value='' class="call_back_ajax">
                                <input type="datetime-local" name="call_back_at_new" class="form-control"
                                    id="call_back_new" placeholder="Add Later time"
                                    aria-describedby="inputSuccess2Status2">
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <!-- <textarea name="remarks_new" id="remarks_new" cols="30" rows="10" class="form-control"></textarea> -->

                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" value="Follow Up" class="btn btn-success" name="follow" id="follow_up_new"
                            style="display:;">

                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3 mt-2">
            <input type="button" value="Submit" class="btn btn-success submit_button_new submit_button_on_no"
                name="upload" id="submit_button"
                onclick="SavingActivationLead('{{ route('leadupdatenew') }}','ActiveForm','{{ route('home') }}')">


        </div>
    </div>

</div>

</div>
