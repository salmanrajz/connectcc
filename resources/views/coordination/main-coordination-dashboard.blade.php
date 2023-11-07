@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">
            Dashboard
          </h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">
            Welcome Admin, everything looks great.
          </h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="javascript:void(0)">App</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
              Dashboard
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->
                @inject('provider', 'App\Http\Controllers\HomeController')

  <!-- Page Content -->
   <div class="content">

          <!-- Overview -->
                          <h2 class="text-left display-10 mt-4">
                    Daily Summary
                </h2>
                <div id="DailySummaryLead">

                </div>
                          <h2 class="text-left display-10 mt-4">
                    Monthly Summary
                </h2>
                <div id="MonthlySummaryLead">

                </div>
          </div>
          <!-- END Overview -->

        </div>
  <!-- END Page Content -->
  @section('js')
  {{-- {{auth()->user()->role}}
   --}}
@if(auth()->user()->role == 'MainCoordinator')
<script>
    //
window.ShowCordDashboard = function(url, Month, loadingUrl) {
  $.ajax({
    type: 'POST',
    url: url,
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {
      Month: Month,
    },
    beforeSend: function () {
      // alert(Month);
      if (Month == 'Daily') {
        $("#DailySummaryLead").html('<img src="' + loadingUrl + '" class="img-fluid text-center offset-md-6" style="width:35px;"></img>');
      } else if (Month == 'Monthly') {
        $("#MonthlySummaryLead").html('<img src="' + loadingUrl + '" class="img-fluid text-center offset-md-6" style="width:35px;"></img>');
      } else if (Month == 'CallCenter') {
        $("#CallCenterSummaryLead").html('<img src="' + loadingUrl + '" class="img-fluid text-center offset-md-6" style="width:35px;"></img>');
      } else if (Month == 'ActivationAgent') {
        $("#ActivationAgentSummaryLead").html('<img src="' + loadingUrl + '" class="img-fluid text-center offset-md-6" style="width:35px;"></img>');
      }
      // $("#DailySummaryLead").show();
      // $("#loading_num3").html('<p> Loading </p>');
    },
    success: function (data) {
      // alert(data);
      // $("#loading_num3").hide();
      if (Month == 'Daily') {
        $("#DailySummaryLead").html(data);
      } else if (Month == 'Monthly') {
        $("#MonthlySummaryLead").html(data);
      } else if (Month == 'CallCenter') {
        $("#CallCenterSummaryLead").html(data);
      } else if (Month == 'ActivationAgent') {
        $("#ActivationAgentSummaryLead").html(data);
      }
    }
  });
}
//

    // alert("ok");
ShowCordDashboard('{{route('admin.LoadMainCordData')}}','Daily','{{asset('assets/images/loader.gif')}}');
ShowCordDashboard('{{route('admin.LoadMainCordData')}}','Monthly','{{asset('assets/images/loader.gif')}}');
ShowCordDashboard('{{route('admin.LoadMainCordData')}}','CallCenter','{{asset('assets/images/loader.gif')}}');
ShowCordDashboard('{{route('admin.LoadMainCordData')}}','ActivationAgent','{{asset('assets/images/loader.gif')}}');
setInterval(() => {
ShowCordDashboard('{{route('admin.LoadMainCordData')}}','Daily','{{asset('assets/images/loader.gif')}}');
ShowCordDashboard('{{route('admin.LoadMainCordData')}}','Monthly','{{asset('assets/images/loader.gif')}}');
ShowCordDashboard('{{route('admin.LoadMainCordData')}}','CallCenter','{{asset('assets/images/loader.gif')}}');
ShowCordDashboard('{{route('admin.LoadMainCordData')}}','ActivationAgent','{{asset('assets/images/loader.gif')}}');
}, 10000);

</script>
@endrole
@endsection
{{-- @endrole --}}
@endsection
