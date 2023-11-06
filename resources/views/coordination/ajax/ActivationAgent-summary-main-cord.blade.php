@inject('HomeCount', 'App\Http\Controllers\CoordinaterController')
@inject('provider', 'App\Http\Controllers\HomeController')
<div class="row mt-3 ">
                    @foreach ($activation_users as $au)
                    <div class="col-lg-2">
                        <div class="card" id="pending_div">
                            <div class="card-body text-center" data-toggle="tootltip" tooltip="Check Your Total Pending Lead" onclick="javascript:location.href='{{route('agent_assigned',$au->id)}}'">
                                <h4 class="white" style="color:#fff;">{{$au->name}}</h4>
                                <h6 class="display-5 mt-4 white" style="color:#fff;" >
                                    {{$HomeCount::assign_count($au->id)}}
                                </h6>

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
