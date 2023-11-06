                        <form class="form-horizontal form-label-left input_mask" method="post"
                            action="{{route('numbersavereserved')}}" id="FormID">
                            @csrf
                            <!-- Plan name -->
                            <div class="form-group">

                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Number</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="inputSuccess3" name="number"
                                        placeholder="Type Number" type="text" value="{{ $operation->number }}">
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>

                            </div>

                            <!-- Plan name end -->

                            <!--  #1 -->
                            <div class="form-group">


                                <!--  #1-->
                                <!--  #2 -->

                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Call Center</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <select name="call_center" id="call_center" class="form-control">
                                        <option value="default">Default</option>
                                        @foreach ($call_center as $item)
                                            <option value="{{$item->call_center_name}}" {{$operation->call_center == $item->call_center_name ? 'selected' : ''}} >{{$item->call_center_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--  --}}

                            </div>
                            <input type="hidden" name="id" value="{{$operation->id}}">
                            <!--  #2-->

                            <!--  #7-->
                            <div class="ln_solid"></div>
                            <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                            <div class="form-group">
                                <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                <button class="btn btn-success" type="button" name="submit" onclick="VerifyLead('{{route('numbersavereserved')}}','FormID','{{route('checknumberoriginalreserved.status')}}')">Proceed</button>
                                    <!-- <button type="button" class="btn btn-primary">Can cel</button> -->
                                    {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                                    {{-- <button type="submit" class="btn btn-success" name="upload">Submit</button> --}}
                                </div>
                            </div>


                        </form>
