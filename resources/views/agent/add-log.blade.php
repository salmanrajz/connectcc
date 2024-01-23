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
@inject('provider', 'App\Http\Controllers\ImportExcelController')
@inject('HomeCount', 'App\Http\Controllers\HomeController')


<div class="row items-push">

            <div class="col-sm-4 col-xxl-4">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$provider::MyWhatsAppCount('Follow up')}}
                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Follow Up (Monthly)') }}</dd>
                        </dl>
                    </div>

                </div>
                <!-- END Pending Orders -->
            </div>
            <div class="col-sm-4 col-xxl-4">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$provider::MyWhatsAppCount('verified')}}
                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Verified  (Monthly)') }}</dd>
                        </dl>
                    </div>

                </div>
                <!-- END Pending Orders -->
            </div>


            <div class="col-sm-4 col-xxl-4">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                             @php
                                $ls =  \Carbon\Carbon::parse($HomeCount::LastSaleCounter(auth()->user()->id))->diffForHumans();
                                @endphp
                                @if($ls ==  '1 second ago')
                                {{-- <span class="red alert-danger"> --}}
                                    0
                                {{-- </span> --}}
                                @else
                                Last Sale
                                {{$ls}}
                                @endif

                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Activation (Monthly)') }}</dd>
                        </dl>
                    </div>

                </div>
                <!-- END Pending Orders -->
            </div>
</div>
<div class="row items-push">
    <div class="col-sm-4 col-xxl-4">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$provider::MyWhatsAppCount('connected')}}
                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Connected Calls Today') }}</dd>
                        </dl>
                    </div>

                </div>
                <!-- END Pending Orders -->
            </div>
            <div class="col-sm-4 col-xxl-4">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$provider::MyWhatsAppCount('Follow up')}}
                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Total Follow Up Today') }}</dd>
                        </dl>
                    </div>

                </div>
                <!-- END Pending Orders -->
            </div>

</div>
    <!-- Labels on top -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ __('Customer Details') }}</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
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

                            {{-- foreach --}}
                            <!-- Plan name -->
                            <div class="row">

                                <label for="localminutes" class="control-label col-md-3 col-sm-12 col-xs-12">
                                    Call Log Number</label>
                                <label for="localminutes" class="control-label col-md-3 col-sm-12 col-xs-12">
                                    Pitch Number</label>
                                    <label for="localminutes" class="control-label col-md-2 col-sm-12 col-xs-12">
                                        Language</label>
                                    <label for="localminutes" class="control-label col-md-2 col-sm-12 col-xs-12">
                                        Remarks</label>
                                    </div>
                            {{-- @for($i = 0; $i<=300 ; $i++) --}}
                            @foreach ($k as $i => $item)
                                    {{-- {{$item->number}} --}}
                             <form class="form-horizontal form-label-left input_mask" method="post"
                                autocomplete="off" id="call_log_{{$i}}" onsubmit="return false">
                            @csrf
                            <div class="form-group row mb-3">


                                <div class="col-md-3 col-sm-4 col-xs-12 form-group has-feedback">
                                    <p style="margin:0px;">
                                        {{$item->number}}
                                        {{-- {{substr_replace($item->number,"0",0,3)}} --}}
                                    </p>
                                    <small style="color:red;">Num Gen QTY:                                 {{$provider::MyTotalCount($item->number)}}</small>
                                    {{-- <p>
                                        <div class="fa fa-whatsapp" onclick="window.location.href='https://wa.me/923121337222?text=Hello'"> Click WhatsApp</div>
                                    </p> --}}
                                    <input class="form-control hidden d-none" placeholder="Customer Number i.e 0551234567" name="number"
                                maxlength="10" required type="tel"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                onkeypress="return isNumberKey(event)" id="number"
                                value="{{substr_replace($item->number,"0",0,3)}}" />
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-12 form-group has-feedback">
                                    <p style="color:red;">
                                        {{$item->number}}
                                        {{-- {{substr_replace($item->number,"0",0,3)}} --}}
                                    </p>
                                    {{-- <p>
                                        <div class="fa fa-whatsapp" onclick="window.location.href='https://wa.me/923121337222?text=Hello'"> Click WhatsApp</div>
                                    </p> --}}
                                    <input class="form-control hidden d-none" placeholder="Customer Number i.e 0551234567" name="number"
                                maxlength="10" required type="tel"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                onkeypress="return isNumberKey(event)" id="number"
                                value="{{substr_replace($item->number,"0",0,3)}}" />
                                </div>
                                <input type="hidden" name="number_id" value="{{$item->id}}">
                                <input type="hidden" name="userid" value="{{auth()->user()->id}}">
                                    <div class="col-md-2"  id="language">


                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback" >
                                    <select name="language" id="language_{{$i}}" class="form-control" required>
                                                <option value="English">English</option>
                                                <option value="Arabic">Arabic</option>
                                                <option value="Urdu/Hindi">Urdu - Hindi</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class=" col-md-2" id="remarks_{{$i}}">

                                        {{-- <label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12">
                                            Remarks</label> --}}
                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                            <select name="status" id="status" class="form-control">
                                                <option value="No Answer">No Answer</option>
                                                <option value="Not Interested">Not Interested</option>
                                                <option value="Switch Off">Switch Off</option>
                                                <option value="Not Valid">Not Valid</option>
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Lead">Lead</option>
                                                <option value="Less Salary">Less Salary</option>
                                                <option value="Interested But Less Salary">Interested But Less Salary</option>
                                                <option value="No Docs Interested">No Docs Interested</option>
                                                <option value="DNC">DNC</option>
                                                <option value="soft_dnd">Soft DNC</option>
                                                {{-- <option value="Interested" @if( $item->remarks == 'Interested')  selected @endif>Interested</option>
                                                <option value="Not Interested" @if( $item->remarks == 'Not Interested')  selected @endif>Not Interested</option>
                                                <option value="Invalid" @if( $item->remarks == 'Invalid')  selected @endif>Invalid</option>
                                                <option value="Lead" @if( $item->remarks == 'Lead')  selected @endif>Lead</option>
                                                <option value="No Answer" @if( $item->remarks == 'No Answer')  selected @endif>No Answer</option>
                                                <option value="DNC" @if( $item->remarks == 'DNC')  selected @endif>DNC</option>
                                                <option value="Follow Up" @if( $item->remarks == 'Follow Up')  selected @endif>Follow Up</option> --}}
                                            </select>
                                        {{-- <textarea name="remarks" id="remarks" cols="30" rows="10" class="form-control" placeholder="add remarks">{{old('remarks')}}</textarea> --}}
                                        </div>

                                    </div>

                                    <!--  #7-->
                                    {{-- <div class="ln_solid"></div> --}}
                                    {{-- <div class="form-group"> --}}
                                    <div class="col-md-2 col-sm-12 col-xs-12 col-md-offset-3">
                                    <!-- <button type="button" class="btn btn-primary">Can cel</button> -->
                                    {{-- <button class="btn btn-primary" type="reset">Reset</button> --}}
                                    @if ($item->status != '')
                                    <input type="button" class="btn btn-info" name="Updated" id="btn_{{$item->id}}"  value="Updated">
                                    @else
                                    <input type="submit" class="btn btn-success" name="upload" id="btn_{{$item->id}}" onclick="CallLogForm('{{$item->id}}','call_log_{{$i}}','{{route('number.feedback.submit')}}')" value="Update">

                                    @endif
                                </div>
                            {{-- </div> --}}
                            </div>
                        </form>
                            @endforeach
                            {{ $k->links() }}
{{-- <script>
    $('.pagination a').on('click', function (event) {
    event.preventDefault();
    if ($(this).attr('href') != '#') {
        $('#ajaxContent').load($(this).attr('href'));
    }
});
</script> --}}

                            {{-- @endfor --}}


                    </div>
                </div>
            </div>

        </div>
        </div>
    </div>
    <!-- END Labels on top -->
    <!-- END Overview -->


</div>
<!-- END Page Content -->
@endsection
