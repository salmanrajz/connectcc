<!DOCTYPE html>
<html>

<head>
    <title>Import Excel File in CRM SOFTWARE</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body onload="loc.getLocation()">
    <br />
    {{--
  <div class="container">
   <h3 align="center">Import Excel File in CRM SOFTWARE</h3>
    <br />
   @if(count($errors) > 0)
    <div class="alert alert-danger">
     Upload Validation Error<br><br>
     <ul>
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
    </div>
    @endif --}}

    @if($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif
    {{-- @php
    $url = 'http://whatsapp.parhakooo.com/chats?id=DXB';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, true); // we want headers
    curl_setopt($ch, CURLOPT_NOBODY, true); // we don't need body
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT,10);
    $output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    // echo $httpcode;
    // echo 'HTTP code: ' .
    if($httpcode == 200){
    // echo "WhatsApp DNC Filter Online";
    $httpcode;
    }else{
    // echo "WhatsApp DNC Filter Offline, Please contact IT Team";
    }

    @endphp --}}
   <form method="post" enctype="multipart/form-data" action="{{route('submit_dnc_number')}}">

    {{-- <form method="post" enctype="multipart/form-data" action="{{route('dnc.add.number.new')}}"> --}}
        {{ csrf_field() }}
        @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>

            @foreach($errors->all() as $error)
            {{ $error }}<br />
            @endforeach
        </div>
        @endif
        <div class="form-group">
            <table class="table">
                <tr>
                    <td width="40%" align="right"><label>Type Number FOR DNC
                            <p>
                                Limit 1 Number Only</p>
                        </label></td>
                    <td width="30">
                        <input type="tel" name="list" id="list" class="form-control" class="form-control">
                        {{-- <textarea name="list" id="list" cols="30" rows="10" class="form-control"></textarea> --}}
                        <textarea name="message" id="message" cols="30" rows="10" class="form-control"
                            style="display:none;"></textarea>
                        <div id="locdetails" style="display:none;">
                            <label>Latitude:</label>
                            <input type="text" id="mylat" name="myLat" />
                            <label>Longitude:</label>
                            <input type="text" id="mylon" name="myLon" />
                            <input type="hidden" name="status" value="DNC">
                            {{-- <button onclick="loc.updateCoords()">Update Coordinates</button> --}}
                        </div>
                        {{-- <input type="text" name="list" id="list" class="form-control"> --}}
                        {{-- <input type="file" name="select_file" /> --}}
                        {{-- <input type="text" name="salman" id="salman" class="form-control" value="salmanahmed"> --}}
                        {{-- <input type="file" name="select_file2" /> --}}
                        {{-- <input type="file" name="select_file3" /> --}}
                        {{-- <input type="file" name="select_file4" /> --}}
                        {{-- <input type="file" name="select_file5" /> --}}
                    </td>
                    <td width="30%" align="left">
                        {{-- @if($httpcode == 200) --}}
                        <input type="submit" name="upload" class="btn btn-primary" value="Submit DNC Number" id="FilterBTN">
                        {{-- @else --}}
                        {{-- <input type="submit" name="upload" class="btn btn-primary" value="Filter" id="FilterBTN" style="display: :none"> --}}
                        {{-- <input type="button" name="upload" class="btn btn-danger" value="DNC Filter Offline" --}}
                            {{-- id="FilterBTNDanger" style="display: none"> --}}
                        {{-- @endif --}}
                    </td>
                </tr>
                <tr>
                    <td width="40%" align="right"></td>
                    {{-- <td width="30"><span class="text-muted">.xls, .xslx</span></td> --}}
                    <td width="30%" align="left"></td>
                </tr>
            </table>
            <p class="text-center">
                Note: System may take hour to submit number in Systemf for NDC
            </p>
        </div>
    </form>
    <button class="btn btn-success" onclick="window.location.href='{{route('home')}}'">Back to Dashboard</button>



    <br />
    {{-- <div class="panel panel-default">
    <div class="panel-heading">
     <h3 class="panel-title">Customer Data</h3>
    </div>
    <div class="panel-body">
     <div class="table-responsive">
      <table class="table table-bordered table-striped">
       <tr>
        <th>Vendor Name</th>
        <th>Model</th>
        <th>description</th>
        <th>shipping_type</th>
        <th>SKU</th>
        <th>stock</th>
       </tr>
       @foreach($data as $row)
       <tr>
        <td>{{ $row->vendor }}</td>
    <td>{{ $row->model }}</td>
    <td>{{ $row->description }}</td>
    <td>{{ $row->shipping_type }}</td>
    <td>{{ $row->SKU }}</td>
    <td>{{ $row->stock }}</td>
    </tr>
    @endforeach
    </table>
    </div>
    </div>
    </div> --}}
    </div>
</body>
<script type="text/javascript"
    src="https://maps.google.com/maps/api/js?key=AIzaSyAoPgkL2yzcbaUEoYpanDN9j4t7LjjDukY&libraries=places&geometry"
    async></script>
<script>
    function Location() {

        //Private Instance Variables, can only be accessed or modified from within the object
        var myCoords = {
                lat: "",
                lon: ""
            },
            self = this;

        //PRIVATE FUNCTIONS/METHODS
        //This function is private and can only be called from within the scope of the object, it is passed a location object as its only parameter
        //this location object is used to update the object instance's stored latitude and longitude
        var setLocation = function (position) {
            myCoords.lat = position.coords.latitude;
            myCoords.lon = position.coords.longitude;
            //Update the lat and lon form fields with the users location
            self.updateFields(myCoords.lat, myCoords.lon);
        };

        //This function is also private and can only be called from within the scope of the object, it is called if the users location could not be ascertained by the Geolocation API, it is passed an error object as its only parameter
        var locationError = function (error) {
            //Examine the error object to determine its type, display an appropriate error message to the user
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("Allow Location for Proceed the Action");
                    location.reload();
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Sorry, Unknown Error Occured");
                    break;
                case error.TIMEOUT:
                    alert("The request  timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        };

        //PUBLIC FUNCTIONS/METHODS
        //This function makes the request to the Geolocation API to determine the user's location
        this.getLocation = function () {
            //Check for support for the Geolocation API in the user's browser
            if (navigator.geolocation) {
                //Display a confirmation dialog to the user detailing the apps policies relating to the handling of their location information
                if (confirm(
                        "The app is about to request access to your location information, please read our privacy policy relating to how your location information will be handled, processed and stored"
                        )) {
                    //If the user accepts, make a request to the Geolocation API to determine the user's location and call
                    //If the user's location is successfully retrieved, the success callback function is set as the showLocation function of the Location object instance
                    //Else if the user's location is not successfully retrieved, call the locationError callback function
                    navigator.geolocation.getCurrentPosition(setLocation, locationError);
                }
                //If the user does not accept the policies, return false
                else {
                    return false;
                }
                //if Geolocation is not supported, display an error message to the user.
            } else {
                alert(
                    "Geolocation is not supported by your browser! If you wish to use this service, upgrade your browser.");
            }
        };

        //This function takes the coordinates stored (gathered either using the Geolocation API or manually entered by the user)
        this.showOnMap = function (mapholderid) {
            if (mapholderid) {
                if (myCoords.lat && myCoords.lon) {
                    if (confirm(
                            "In order to show your location on a map, your location is sent to google's mapping service, do you accept?"
                            )) {
                        var latlon = myCoords.lat + "," + myCoords.lon;

                        var img_url = "https://maps.googleapis.com/maps/api/staticmap?center=" + latlon +
                            "&zoom=14&size=400x300&sensor=false";
                        document.getElementById(mapholderid).innerHTML = "<img src='" + img_url + "' />";
                    }
                } else {
                    alert("Location not set! You must first get your location");
                }
            } else {
                alert("You must define the ID of the element you wish the map to be added to!");
            }
        };

        //This function updates the latitude and longitude input field values with the stored values
        this.updateFields = function (lat, lon) {
            document.getElementById("mylat").value = lat;
            document.getElementById("mylon").value = lon;
            $("#FilterBTN").show();
            $("#FilterBTNDanger").show();
        };
        //  setInterval(() => {
        //      VerifyLead();
        //  }, 5000);

        //This function updates the stored latitude and longitude values with the user entered values
        this.updateCoords = function () {
            //Test the user entered latitude and longitude using a regular expression to ensure that they are valid
            //if they are, update the stored values, else display an error message
            if (document.getElementById("mylat").value.match(/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/) && document
                .getElementById("mylon").value.match(/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,6}/)) {
                myCoords.lat = document.getElementById("mylat").value;
                myCoords.lon = document.getElementById("mylon").value;
                alert("You have successfully manually updated your coordinates");
            } else {
                alert("You must enter both a valid latitude and longitude!");
            }
        };
    }
    var loc = new Location();

</script>

</html>
