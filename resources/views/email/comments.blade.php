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
    Agent Name: {{$detail['saler_name']}}
</p>
<p>
    Remarks: {{$detail['remarks']}}
</p>

<a href="{{route('view.lead',$detail['lead_id'])}}">View Lead Details</a>
