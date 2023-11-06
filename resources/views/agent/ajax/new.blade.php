<div class="package_name new_package"
    style="display:{{ old('simtype') == 'New' ? 'block' : 'none' }}">
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
    <!-- form-group end -->
    <div class="form-group jackson_action row" id="klon1">
        <div class="col-md-4">
            <label for="mytypeval">Select Number Type</label>
            <select name="mytypeval[]" id="mytypeval" class="form-control">
                <option value="">Select Number Type</option>
                <option value="my">My Reserved Number</option>
                @foreach($q as $qq)
                    <option value="{{ $qq->type }}">{{ $qq->type }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12 form-group salman_ahmed">
            <label for="mytypeval">Select Number</label>

            <select name="selnumber[]" id="selcnumber" class="form-control NumberDropDown">
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
        </div>


        <!-- Customer number end -->
        {{-- <span class=""> --}}

        <div class="col-md-3 col-sm-12 col-xs-12 form-group ">
            <label for="mytypeval">Select Plan</label>
            <select name="plan_new[]" id="c__select" class="form-control c__select">
                <option value="">Select Plan</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->plan_name }}</option>
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



    <div class="col-md-6 col-sm-6 col-xs-6 form-group rajput">
        <a class="btn btn-primary" id="sumebutton" style="color:#fff;display:none;">
            Add New Number
        </a>
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
                onclick="SavingActivationLead('{{ route('leadstorenew') }}','ActiveForm','{{ route('home') }}')">
            {{-- <input value="Follow Up" class="btn btn-success follow_up_new" data-toggle="modal" data-target="#myModal" id="follow_up_new" type="button"> --}}

            {{-- <input type="submit" value="Submit" class="btn btn-success  submit_mnp submit_button_on_no" name="upload" id="submit_button">
                          <input value="Follow Up" class="btn btn-success follow_up_mnp follow_up_new" data-toggle="modal" data-target="#myModalMNP" id="follow_up_new" >
                          <input type="reset" value="Reset" class="btn btn-primary reset" name="reset" id="reset"> --}}


        </div>
    </div>

</div>

</div>
