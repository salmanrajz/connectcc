                        <form class="form-horizontal form-label-left input_mask" method="post"
                            action="{{route('number-system.store')}}" id="FormID">
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
                            <div class="form-group">

                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Number Category</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <select name="number_category" id="number_category" class="form-control">
                                        <option value="">Select Number Type</option>
                                        <option value="Standard"
                                            {{$operation->type == 'Standard' ? 'selected' : ''}}>Standard</option>
                                        <option value="Silver" {{$operation->type == 'Silver' ? 'selected' : ''}}>
                                            Silver</option>
                                        <option value="Gold" {{$operation->type == 'Gold' ? 'selected' : ''}}>Golden
                                        </option>
                                        <option value="Gold Plus" {{$operation->type == 'Gold Plus' ? 'selected' : ''}}>Gold Plus
                                        </option>
                                        <option value="Silver Plus" {{$operation->type == 'Silver Plus' ? 'selected' : ''}}>Silver Plus
                                        </option>
                                        <option value="Silver Star" {{$operation->type == 'Silver Star' ? 'selected' : ''}}>Silver Star
                                        </option>
                                        <option value="Platinum"
                                            {{$operation->type == 'Platinum' ? 'selected' : ''}}>Platinum</option>
                                    </select>
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>

                            </div>
                            <!-- Plan name end -->

                            <!--  #1 -->
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Number Passcode</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="inputSuccess3"
                                        name="number_passcode" placeholder="Passcode" type="tel"
                                        value="{{$operation->passcode}}">
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>

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
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Channel Partner</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <select name="channel_partner" id="channel_partner" class="form-control">
                                       <option value="TTF" {{$operation->channel_type == 'TTF' ? 'selected' : ''}}>TTF</option>
                                        <option value="ExpressDial" {{$operation->channel_type == 'ExpressDial' ? 'selected' : ''}}>ExpressDial</option>
                                        <option value="MWH" {{$operation->channel_type == 'MWH' ? 'selected' : ''}}>MWH</option>
                                        <option value="Ideacorp" {{$operation->channel_type == 'Ideacorp' ? 'selected' : ''}}>Idea Corp</option>
                                    </select>
                                </div>
                                {{--  --}}
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Status</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input type="text" name="status" id="status" class="form-control" value="{{$operation->status}}">
                                </div>
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Number Status</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input type="text" name="number_status" id="number_status" class="form-control" value="{{$operation->identity}}">
                                </div>
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
                                <button class="btn btn-success" type="button" name="submit" onclick="VerifyLead('{{route('NumberEdit')}}','FormID','{{route('checknumberoriginal.status')}}')">Proceed</button>
                                    <!-- <button type="button" class="btn btn-primary">Can cel</button> -->
                                    {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                                    {{-- <button type="submit" class="btn btn-success" name="upload">Submit</button> --}}
                                </div>
                            </div>


                        </form>
