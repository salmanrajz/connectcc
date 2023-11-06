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
    {{-- // {{$ln = $operation->selected_number}} --}}
    @if ($operation->selected_number != "")
    @foreach(explode(',', $operation->selected_number) as $key => $info)
    {{-- // <option>{{$info}}</option> --}}
    {{-- // $plan = $question->select_plan; --}}
    {{-- // {{$operation->select_plan[$key]}} --}}
    @php
    $sel_plan = explode(",",$operation->select_plan);
    $selected_number = explode(",",$operation->selected_number);
    $pay_status = explode(",",$operation->pay_status);
    $pay_charges = explode(",",$operation->pay_charges);
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
                Number Category: {{$provider::typefetch($selected_number[$key])}}
            </h6>
            <div id="suggesstion-box2"
                style="background:white;margin: 0;padding: 0;position: absolute;z-index:99999;width:466px;">
            </div>
        </div>
        {{-- <span class=""> --}}

        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
            <select name="plan_new[]" id="c__select" class="form-control " style="" required
                onchange="plan_month($(this).val())" >
                {{-- <option value="{{$operation->select_plan}}">
                {{$operation->select_plan}}
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
        <div class="container col-md-12 hidden d-none">
            <div class="table-responsive hidden-sm hidden d-none hidden-xs">

                <table class="table table-striped table-bordered table-responsive">
                    <thead>
                        <tr class="headings">
                            <label for="" id="lsm">
                                <!-- <input type="text" name="plans[]" id="lm" value=""  class="noborder"/> -->
                            </label>

                            <!-- <p class="ponka" id="ponka">lorem</p> -->
                            <th class="column-title ponka">Local Minutes </th>
                            <th class="column-title">Flexible Minutes </th>
                            <th class="column-title">operation </th>
                            <th class="column-title">Preffered Numbers Allowed </th>
                            <th class="column-title">Free minutes to preffered Numbers </th>
                            <th class="column-title">Contract Commitment </th>
                            <!-- <th class="column-title">Amount </th> -->
                            <th class="column-title no-link last"><span class="nobr">Monthly Plan
                                    Payment</span></th>

                        </tr>
                    </thead>
                    <style>
                        .noborder {
                            border: none;
                            width: 50%;
                            font-weight: bold;
                        }

                    </style>
                    <tbody>
                        @if($myplan)
                        <tr class="even pointer">
                            <td class="lm ponka">
                                <input type="text" name="plans[]" id="lm"
                                    value="{{$myplan->local_minutes}}" class="noborder lm" />
                            </td>
                            <td class="fm ">
                                <input type="text" name="plans[]" id="fm"
                                    value="{{$myplan->flexible_minutes}}" class="noborder fm" />
                            </td>
                            <td class="operation ">
                                <input type="text" name="plans[]" id="operation"
                                    value="{{$myplan->operation}}" class="noborder samina" />
                            </td>
                            <td class="pnum ">
                                <input type="text" name="plans[]" id="pnum"
                                    value="{{$myplan->number_allowed}}" class="noborder pnum" />
                            </td>
                            <td class="fmnum ">
                                <input type="text" name="plans[]" id="fmnum"
                                    value="{{$myplan->free_minutes}}" class="noborder fmnum" />
                            </td>
                            <td>
                                <div id="contract_commitment_1" class="contract_commitment_1">
                                    {{$myplan->duration}}
                                </div>
                                <!-- <input type="text"  id="monthly_plan_payment" value=""  class="noborder mp"/> -->
                            </td>
                            <td class="a-right a-right m_p ">
                                <input type="text" name="plan_s[]" id="mp1" onchange="myFunction2()"
                                    value="{{$myplan->revenue}}" class="noborder mp" />
                            </td>
                            <!-- <td class=" last"><a href="#">View</a> -->
                            </td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
        <!-- <button class="btn btn-success klon_verify" onclick="" type="button">Verify</button> -->
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
            onclick="UpdateNumber('{{$operation->id}}','{{route('update.lead.number')}}')">


      Change Numbers
    </button> --}}
    </div>
