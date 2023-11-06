@foreach ($data as $item)
<div class="row msg_container base_receive">
                        {{-- <div class="col-md-2 col-xs-2 avatar">
                            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" class=" img-responsive " style="width:20px;">
                        </div> --}}
                        <div class="col-md-12 col-xs-12">
                            <div class="messages msg_receive">
                                <p>{{$item->remarks}}</p>
                                <time datetime="2009-11-13T20:00">{{$item->user_agent}} • {{\Carbon\Carbon::createFromTimeStamp(strtotime($item->date_time))->diffForHumans()}}</time>
                            </div>
                        </div>
                    </div>

@endforeach
