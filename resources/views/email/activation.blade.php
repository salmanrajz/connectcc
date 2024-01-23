<p>
    Lead No : {{$detail['lead_no']}}
</p>
<p>
    Customer Name: {{$detail['customer_name']}}
</p>
<p>
    Customer Number: {{$detail['customer_number']}}
</p>
<p>
    Selected Number: {{$detail['selected_number']}}
</p>
<p>
    Sim Type: {{$detail['sim_type']}}
</p>
<p>
    Status: Lead has been Activated Succesfully
</p>
<a href="{{route('view.lead',$detail['lead_id'])}}">View Lead Details</a>

{{-- <a href="{{route('view.lead'.$detail['lead_id'])}}">View Lead Details</a> --}}
