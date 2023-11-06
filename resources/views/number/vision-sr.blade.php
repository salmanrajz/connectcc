@extends('layouts.num-app')

@section('content')
<div class="form-container">
    <form onsubmit="return false" method="post" enctype="multipart/form-data" id="FetchApiForm">
    @csrf
  Select image to upload:
  <input type="file" name="front_img" id="front_img">
  {{-- <input type="file" name="back_img" id="back_img"> --}}
  <input type="button" value="Upload Image" name="submit" onclick="SrApi('{{route('ocr-sr.submit')}}','FetchApiForm')">
  {{-- <input type="submit" value="Upload Image" name="submit" > --}}
 <div class="form-group">
      <label for="dob">Sr #</label>
    <input type="text" name="dob" id="sr_no">
 </div>
 <div class="form-group">
      <label for="dob">Service Order Number</label>
    <input type="text" name="dob" id="order_number">
 </div>
 <div class="form-group">
      <label for="dob">Application Date</label>
    <input type="text" name="dob" id="application_date">
 </div>

</form>
</div>

@endsection
