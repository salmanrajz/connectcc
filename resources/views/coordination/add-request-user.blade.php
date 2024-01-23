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
                    Welcome {{auth()->user()->name}}
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
                    <!-- Form Labels on top - Default Style -->
                    <form  method="POST" onsubmit="return false;" id="ActiveForm">
                        <div class="container row">
                            <div class="mb-4 col-lg-4">
                                <label class="form-label"
                                    for="example-ltf-email">{{ __('Agent Name') }}</label>
                                <input class="form-control " id="cname" placeholder="Agent Name" name="name"
                                    type="text" required >
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label class="form-label"
                                    for="example-ltf-email">{{ __('CNIC #') }}</label>
                                <input class="form-control " id="cname" placeholder="Email ID" name="cnic"
                                    type="text" required >
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label class="form-label"
                                    for="example-ltf-email">{{ __('Email') }}</label>
                                <input class="form-control " id="email" placeholder="CNIC #" name="email"
                                    type="email" required >
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label class="form-label"
                                    for="example-ltf-email">{{ __('Agent Phone') }}</label>
                                <input class="form-control " id="cname" placeholder="CNIC #" name="phone"
                                    type="email" required >
                            </div>
                        </div>
                        <div class="container row">
                            <div class="mb-4 col-lg-4">
                                <label class="form-label"
                                    for="example-ltf-email">{{ __('Profile Photo') }}</label>
                                <input type="file" name="img" id="profile_pic" class="form-control-file" accept="image/*">
                                <img id="myImg" src="#" alt="your image" style="width:25%"/>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label class="form-label"
                                    for="example-ltf-email">{{ __('CNIC/Adhar Front') }}</label>
                                <input type="file" name="cnic_front" id="cnic_front" class="form-control-file" accept="image/*">
                                <img id="myImg1" src="#" alt="your image" style="width:25%"/>
                            </div>
                            <div class="mb-4 col-lg-4">
                                <label class="form-label"
                                    for="example-ltf-email">{{ __('CNIC/Adhar Back') }}</label>
                                <input type="file" name="cnic_back" id="cnic_backedi" class="form-control-file" accept="image/*">
                                <img id="myImg2" src="#" alt="your image" style="width:25%"/>
                            </div>
                            <div class="alert alert-danger print-error-msg mt-4eb." style="display:none">
                            <ul></ul>
                        </div>
                         <div class="form-group">
        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3 mt-2">
            <input type="button" value="Submit" class="btn btn-success submit_button_new submit_button_on_no"
                name="upload" id="submit_button"
                onclick="SavingActivationLead('{{ route('RequestAgentStore') }}','ActiveForm','{{ route('home') }}')">


        </div>
    </div>

                        </div>
                            {{-- <div class="mb-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div> --}}
                        </div>
                    </form>
                    <!-- END Form Labels on top - Default Style -->

                    <!-- Form Labels on top - Alternative Style -->

                    <!-- END Form Labels on top - Alternative Style -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Labels on top -->
    <!-- END Overview -->


</div>
<!-- END Page Content -->
@endsection
