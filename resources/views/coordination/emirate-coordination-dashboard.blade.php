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
                <div id="DailySummaryLeadEmirate">

                </div>
                <div class="col-sm-3 col-xxl-3">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$provider::emirate_my_group_yesterday_assigned('Salman')}}

                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Yesterday Leads') }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-gem fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{route('emirate.my.proceed.yesterday')}}">
                            <span>{{ __('View Leads') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
                {{-- <div class="col-lg-2">
                    <div class="card" id="pending_div">
                        <div class="card-body text-center" data-toggle="tootltip"
                            tooltip="Check Your Total Pending Lead"
                            onclick="javascript:location.href='{{route('my.proceed.yesterday')}}'">
                            <h4 class="white" style="color:#fff;">Yesterday Lead</h4>
                            <h6 class="display-5 mt-4 white" style="color:#fff;">
                                {{$provider::emirate_my_group_yesterday_assigned('Salman')}}
                            </h6>
                        </div>
                    </div>
                </div> --}}

          </div>
          <!-- END Overview -->

        </div>
  <!-- END Page Content -->
  @section('js')
  {{-- {{auth()->user()->role}}
   --}}
@if(auth()->user()->role == 'Emirate Coordinator')
<script>
    window.ShowEmirateDashboard = function(url, Month, loadingUrl) {
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
            if (Month == 'Daily') {
                $("#DailySummaryLead").html('<img src="' + loadingUrl + '" class="img-fluid text-center offset-md-6" style="width:35px;"></img>');
            } else if (Month == 'Monthly') {
                $("#MonthlySummaryLead").html('<img src="' + loadingUrl + '" class="img-fluid text-center offset-md-6" style="width:35px;"></img>');
            } else if (Month == 'CallCenter') {
                $("#CallCenterSummaryLead").html('<img src="' + loadingUrl + '" class="img-fluid text-center offset-md-6" style="width:35px;"></img>');
            } else if (Month == 'ActivationAgent') {
                $("#ActivationAgentSummaryLead").html('<img src="' + loadingUrl + '" class="img-fluid text-center offset-md-6" style="width:35px;"></img>');
            } else if (Month == 'EmirateCord') {
                $("#DailySummaryLeadEmirate").html('<img src="' + loadingUrl + '" class="img-fluid text-center offset-md-6" style="width:35px;"></img>');
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
            } else if (Month == 'EmirateCord') {
                $("#DailySummaryLeadEmirate").html(data);
            }
        }
    });
}
   var pathname = window.location.pathname; // Returns path only (/path/example.html)
    // alert(pathname);
    if (pathname.indexOf('/home') >= 0 || pathname.indexOf('/admin') >= 0) {
    //   alert("boom");
    // alert($("#CarsAjaxUrl5").val());
      // var Url = $("#CarsAjaxUrl5").val() + '?_=' + Math.round(Math.random() * 10000);
      // console.log(Url);
      // $('#ajaxContent').load(Url);
      ShowEmirateDashboard('{{route('admin.LoadMainCordData')}}','EmirateCord','{{asset('assets/images/loader.gif')}}');
      setInterval(() => {
          ShowEmirateDashboard('{{route('admin.LoadMainCordData')}}','EmirateCord','{{asset('assets/images/loader.gif')}}');
      }, 5000);

  }
</script>
@endif
@endsection
{{-- @endrole --}}
@endsection
