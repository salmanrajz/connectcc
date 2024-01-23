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

<!-- Page Content -->
<div class="content">
    <!-- Overview -->
    <!-- Labels on top -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ __('Customer Details') }}</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-12 space-y-5">
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

                    <!-- Form Labels on top - Default Style -->
    <form class="form-horizontal form-label-left input_mask" method="post"
                            action="{{route('admin.user.store')}}" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <!-- Plan name -->
                            <div class="form-group">

                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Agent
                                    Name</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="inputSuccess3" name="name"
                                        placeholder="Type Agent Name Here" type="text" value="{{ old('name') }}" autocomplete="off">
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Email</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="inputSuccess3" name="email"
                                        placeholder="Type Email Here" type="email" value="{{ old('email') }}">
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Phone</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="inputSuccess3" name="phone"
                                        placeholder="Type Email Here" type="tel" value="{{ old('phone') }}">
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Call Center IP</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="call_center_ip" name="call_center_ip"
                                        placeholder="Type Call Center IP" type="text" value="{{ old('phone') }}">
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Call Center Secondary IP</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="secondary_ip" name="secondary_ip"
                                        placeholder="Type Call Center Secondary IP" type="text" value="{{ old('phone') }}">
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Select Group</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <select name="agent_group" id="agent_group" class="form-control select2">
                                        @foreach ($CallCenter as $item)
                                    <option value="{{$item->call_center_code}}">{{$item->call_center_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Select Multi Group</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <select name="multi_agentcode[]" id="multi_agentcode" class="form-control select2" multiple>
                                        <option value="1" selected>None</option>
                                        @foreach ($CallCenter as $item)
                                    <option value="{{$item->call_center_code}}">{{$item->call_center_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Select Role</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <select name="role" id="role" class="form-control select2">
                                        @foreach ($role as $r)
                                    <option value="{{$r->name}}">{{$r->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Select Emirate</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                   <select name="emirates[]" multiple id="emirate" class="emirates form-control select2" >
                                    <option value="">Select Emirates</option>
                                    @foreach($emirates as $emirate)
                                        <option value="{{ $emirate->name }}" @if (old('emirates') == $emirate->name) {{ 'selected' }} @else @endif>{{ $emirate->name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Team Leader</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                   <select name="emirates[]" multiple id="emirate" class="emirates form-control select2" >
                                    <option value="">Select Team Leader</option>
                                    @foreach($leaders as $lead)
                                        <option value="{{ $lead->id }}" >{{ $lead->name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Job Type</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                   <select name="jobtype"  id="jobtype" class="form-control" >
                                    <option value="">Select Job Type</option>
                                    <option value="fixed" selected>Fixed</option>
                                    <option value="Targeted">Targeted</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="permissions">Permissions</label>
                                @foreach ($permissions as $pp)
                                <div class="col-md-12">
                                    <input type="checkbox" name="permissions[]" id="permission" value="{{$pp->name}}">
                                    <label for="Permissions">{{$pp->name}}</label>
                                </div>
                                @endforeach

                                {{-- <div class="col-md-12">
                                    <input type="checkbox" name="permissions[]" id="permission" value="manage postpaid">
                                    <label for="Permissions">Manage PostPaid</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="checkbox" name="permissions[]" id="permission" value="manage itproducts">
                                    <label for="Permissions">Manage IT Products</label>
                                </div> --}}
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Password</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="inputSuccess3" name="password"
                                        placeholder="*********" type="password" >
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">Confirm Password</label>
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                    <input class="form-control has-feedback-left" id="inputSuccess3" name="password_confirmation"
                                        placeholder="*********" type="password" >
                                    {{-- <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span> --}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="profile_pic">Profile Picture</label>
                                <input type="file" name="img" id="profile_pic" class="form-control-file">
                                <img id="myImg" src="#" alt="your image" style="width:25%"/>
                            </div>
                            <div class="form-group">
                                <label for="profile_pic">CNIC Front</label>
                                <input type="file" name="cnic_front" id="cnic_front" class="form-control-file">
                                <img id="myImg" src="#" alt="your image" style="width:25%"/>
                            </div>
                            <div class="form-group">
                                <label for="profile_pic">CNIC Back</label>
                                <input type="file" name="cnic_back" id="cnic_backedi" class="form-control-file">
                                <img id="myImg" src="#" alt="your image" style="width:25%"/>
                            </div>


                    </div>
                    <!--  #7-->
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <!-- <button type="button" class="btn btn-primary">Can cel</button> -->
                            <button class="btn btn-primary" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success" name="upload">Submit</button>
                        </div>
                    </div>


                    </form>
            </div>
        </div>
    </div>
    {{--  --}}

                    <!-- END Form Labels on top - Default Style -->

                    <!-- Form Labels on top - Alternative Style -->

                    <!-- END Form Labels on top - Alternative Style -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Labels on top -->
    <!-- END Overview -->
{{-- @include('chat.chat-main') --}}


</div>
<!-- END Page Content -->
@endsection
