<label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Appointment Slot - {{$date}}</label>
<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
    <select name="appointment_time" id="time_slot" class="form-control">
        @foreach ($timeslot as $item)
         @php
         $data2 = \App\candidate_data::where('schedule_date',$date)->where('schedule_time',$item->name)->first();
        //  if($data2)
         @endphp
         @if($data2)
         <option value="{{$item->name}}" readonly>{{$item->name}} - Already Booked</option>
         @else
         <option value="{{$item->name}}">{{$item->name}}</option>
        @endif
        @endforeach
    </select>
</div>
