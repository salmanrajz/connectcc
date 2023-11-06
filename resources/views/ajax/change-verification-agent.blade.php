<form id="ActiveForm">
    <div class="container">
    <div class="form-group">
        <div class="col-md-6 col-sm-12 col-xs-12 form-group ">
            <label for="cname">Lead No</label>
            <input class="form-control " id="cname" placeholder="Customer Name" name="cname" type="text" required value="{{$data->lead_no}}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6 col-sm-12 col-xs-12 form-group ">
            <label for="cname">Current Agent Name</label>
            <select name="agent_name" id="agent_name" class="form-control">
                <option value="">Select Agent Name</option>
                @foreach ($user as $item)
                <option value="{{$item->id}}" {{$data->verify_agent == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                @endforeach
                <option value="Null">Default</option>
            </select>
        </div>
    </div>
    <input type="hidden" name="id" value="{{$data->id}}">
    <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
    <div class="form-group">
                    <input type="button" value="Change Agent" class="btn btn-success" name="follow_up_new"
                        id="follow_up_new" style="display:;"
                        onclick="SavingActivationLead('{{route('change.agent')}}','ActiveForm','{{route('InitialLeads.lead')}}')">
    </div>
</div>

</form>
