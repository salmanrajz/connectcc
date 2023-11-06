@inject('HomeCount', 'App\Http\Controllers\CoordinaterController')
@inject('provider', 'App\Http\Controllers\HomeController')
<div class="row">
    @foreach ($call_center as $item)
    <div class="col-lg-2">
        <div class="card" id="process_div">
            <div class="card-body text-center" data-toggle="tootltip" tooltip="Check Your Total Pending Lead"
                onclick="javascript:location.href='{{url('admin/all-lead-' . $item->call_center_name)}}'">
                <h4 class="white" style="color:#fff;">Pending - {{$item->call_center_name}}</h4>
                <h6 class="display-5 mt-4 white" style="color:#fff;">
                    {{$HomeCount::call_center_pending($item->call_center_name)}}
                </h6>
            </div>
        </div>

    </div>
    @endforeach
</div>
