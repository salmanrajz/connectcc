@extends('layouts.num-app')

@section('content')
<div class="form-container">
    <form onsubmit="return false" method="post" enctype="multipart/form-data" id="FetchApiForm">
    @csrf
  Select image to upload:
  <input type="file" name="front_img" id="front_img">
  {{-- <input type="file" name="back_img" id="back_img"> --}}
  <input type="button" value="Upload Image" name="submit" onclick="SavingDataDeal('{{route('ocr.submit')}}','FetchApiForm')">
 <div class="form-group">
      <label for="dob">Date of Birth</label>
    <input type="text" name="dob" id="dob">
 </div>
 <div class="form-group">
      <label for="dob">Date of Expiry</label>
    <input type="text" name="dob" id="expiry">
 </div>
</form>
</div>

@endsection
