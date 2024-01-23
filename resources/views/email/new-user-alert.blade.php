<h1>
    New User Request has been generated,
</h1>
<p>
    Please have a look.
</p>
<p>
    Name: {{$detail['name']}}
</p>
<p>
    Email: {{$detail['email']}}
</p>
<p>
    Password: {{$detail['password']}}
</p>
<p>
    Call Center: {{$detail['call_center']}}
</p>
<p>
    Profile: <img src="{{env('CDN_URL')}}/user-cnic/{{$detail['profile']}}" alt="Profile Photo">
</p>
<p>
    CNIC Back: <img src="{{env('CDN_URL')}}/user-cnic/{{$detail['cnic_front']}}" alt="CNIC Front">
</p>
<p>
    CNIC Front: <img src="{{env('CDN_URL')}}/user-cnic/{{$detail['cnic_back']}}" alt="CNIC Back">
</p>
