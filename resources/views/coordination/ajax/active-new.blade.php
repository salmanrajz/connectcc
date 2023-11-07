@inject('provider', 'App\Http\Controllers\AjaxController')

<div class="package_name new_package">
    <div class="form-group">
        <div class="col-md-12 col-sm-6 col-xs-12">
            <label class="center">
                Postpaid Packages
            </label>
        </div>
    </div>

    {{-- @php $plans = \App\plan::select('plans.*')->where('status','1')->where('plan_category',)->get(); @endphp --}}
    {{-- // {{$ln = $data->selected_number}} --}}
    @if ($data->selected_number != "")
    @foreach(explode(',', $data->selected_number) as $key => $info)
    {{-- // <option>{{$info}}</option> --}}
    {{-- // $plan = $question->select_plan; --}}
    {{-- // {{$data->select_plan[$key]}} --}}
    @php
    $sel_plan = explode(",",$data->select_plan);
    $selected_number = explode(",",$data->selected_number);
    $pay_status = explode(",",$data->pay_status);
    $pay_charges = explode(",",$data->pay_charges);
    // echo $sel_plan[$key];
    $myplan = \App\Models\plan::where('id',$sel_plan[$key])->first();
    $plans = \App\Models\plan::select('plans.*')->where('plan_category',$myplan->plan_category)->get();
    @endphp
    {{-- {{$values[$key]}} --}}

    <!-- form-group end -->
    <div class="form-group  row" id="">
        <div class="col-md-4 col-sm-12 col-xs-12 form-group salman_ahmed" required>
            <input class="form-control " id="salman_ahmed" placeholder="Selected Number" name="selnumber[]"
                type="text" required maxlength="12" value="{{$selected_number[$key]}}"
                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                onkeypress="return isNumberKey(event)" readonly>
            <input type="hidden" name="quick_search_val" id="quick_search_val">
            <h6 class="passcode" style="font-weight:bolder">
                Passcode: {{$provider::passcode_fetch($selected_number[$key])}}
            </h6>
            <div id="suggesstion-box2"
                style="background:white;margin: 0;padding: 0;position: absolute;z-index:99999;width:466px;">
            </div>
        </div>
        {{-- <span class=""> --}}

        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
            <select name="plan_new[]" id="c__select" class="form-control " style="" required
                onchange="plan_month($(this).val())" >
                {{-- <option value="{{$data->select_plan}}">
                {{$data->select_plan}}
                </option> --}}
                <option value="">Select Plan</option>
                @foreach($plans as $plan)
                <option value="{{ $plan->id }}" {{ $sel_plan[$key] == $plan->id ? 'selected' : '' }}>
                    {{ $plan->plan_name }}</option>
                @endforeach

                <!-- <option value="250">250</option> -->
            </select>

        </div>
        {{-- </span> --}}
        <div class="item col-md-2 col-sm-12 col-xs-12 form-group  hidden d-none">
            <select name="activation_charges_new[]" id="activation_charges"
                class="activation_charges form-control">
                <option value=""></option>
                <!-- <option value="Paid">Paid</option> -->

                <option value="Free" @if ($pay_status[$key]=="Free" ) {{ 'selected' }} @endif>Free</option>
                <option value="Paid" @if ($pay_status[$key]=="Paid" ) {{ 'selected' }} @endif>Paid</option>
            </select>
        </div>
        <div class="col-md-2 col-sm-12 col-xs-12 form-group  hidden d-none">
            <input type="text" name="activation_rate_new[]" id="activation_rate"
                class="activation_rate form-control" placeholder="I.E 130 AED"
                value="{{$pay_charges[$key]}}">
        </div>
        <div class="col-md-2 hidden d-none">
            <select name="klon_change" id="klon_change" class="form-control klon_verify">
                <option value="no">No</option>
                <option value="Yes">Yes</option>
            </select>

        </div>
       <div class="container">
                        <div class="form-group row">
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group  mt-3">
                                <label for="activation_date">Activation Date</label>
                                <input type="datetime-local" name="activation_date[]" id="activation_date"
                                    placeholder="Date and Time" class="form-control"
                                    value="">
                            </div>
                            <div class=" col-md-6 col-sm-6 col-xs-12 form-group mt-3 ">
                                <label for="">SR No</label>
                                <input type="tel" name="activation_sr_no[]" id="activation_sr_no" placeholder="SR #"
                                    class="form-control" maxlength="9"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    value="{{old('activation_sr_no')}}">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group mt-3 ">
                                <label for="activation_service_order">Service Order No</label>
                                <input type="tel" name="activation_service_order[]" id="activation_service_order"
                                    placeholder="Service Order #" class="form-control" maxlength="10"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    value="{{old('activation_service_order')}}">
                            </div>



                            <!-- <span class="required">*</span> -->
                            <div class="  col-md-6 col-sm-6 col-xs-12 form-group hidden d-none">
                                <label for="activation_selected_no">Selected No</label>
                                <input type="text" name="activation_selected_no[]" id="activation_selected_no"
                                    placeholder="Select #" class="form-control" maxlength="10"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    value="{{$info}}">
                            </div>
                        </div>
                        <div class="form-group row mt-5">
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group ">
                                <label for="activation_service_order">Sr Photo (Please Upload SR Image)</label>
                                <input class="form-control " id="sr_photo" placeholder="Selected Number" data-validate-length-range="6" data-validate-words="2" name="sr_photo[]" type="file">
                            </div>
                        </div>

    @endforeach
    @endif

    <div id="container_sam"></div>


    <!-- form-group end -->
    <div id="container_sam2" class="form-group">

    </div>


    <div class="col-md-6 col-sm-6 col-xs-6 form-group rajput">
        {{-- <a class="btn btn-primary" id="sumebutton" style="display:none;color:#fff">
            Add New Number
        </a> --}}
        {{-- <button type="button" class="btn btn-primary" id="show_sumebutton" style="color:#fff" data-toggle="modal"
            data-target="#exampleModal"
            Edit Number Details
    </button> --}}
    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
    id="show_sumebutton"
    data-bs-target="#exampleModal"
            onclick="UpdateNumber('{{$data->id}}','{{route('update.lead.number')}}')">


      Change Numbers
    </button> --}}
    </div>
