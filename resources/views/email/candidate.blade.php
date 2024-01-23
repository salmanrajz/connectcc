<p>
    Dear {{$data['name']}}
</p>
<p>
    You have been selected for an interview for Telesales Agent.
</p>
<p>
    Below are your Interview Details:
</p>
<p>
    Your Interview Code is: {{$data['interview_code']}}
</p>
<p>
    Date: {{ Carbon\Carbon::parse($data['date'])->format('d F Y')}}
</p>
<p>
    Time: {{$data['time']}}
</p>
<p>
    Manager Name: {{$data['manager']}}
</p>
<p>
    Number: {{$data['manager_number']}}
</p>
<p>
    Full Location: {{$data['location']}}
</p>
<p>
    Incase of any change, please contact the Interviewer directly. Looking forward to welcoming you.
</p>
<p>
    Best Regards,
</p>
<p>
    HR
</p>
<p>
    Note: (This email is system generated)
</p>
