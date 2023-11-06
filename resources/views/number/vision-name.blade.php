@extends('layouts.num-app')

@section('content')
<div class="form-container">
    <form onsubmit="return false" method="post" enctype="multipart/form-data" id="FetchApiForm">
    @csrf
  Select image to upload:
  <input type="file" name="front_img" id="front_img">
  {{-- <input type="file" name="back_img" id="back_img"> --}}
  <input type="button" value="Upload Image" name="submit" onclick="NameApi('{{route('ocr-name.submit')}}','FetchApiForm')">
  {{-- <input type="submit" value="Upload Image" name="submit" > --}}
 <div class="form-group">
      <label for="dob">Name:</label>
    <input type="text" name="dob" id="name">
 </div>
 <div class="form-group">
      <label for="dob">Emirate ID:</label>
    <input type="text" name="dob" id="emirate_id">
 </div>

</form>
</div>

@endsection
