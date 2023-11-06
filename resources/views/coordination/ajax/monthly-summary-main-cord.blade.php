@inject('HomeCount', 'App\Http\Controllers\CoordinaterController')
@inject('provider', 'App\Http\Controllers\HomeController')
<div class="row items-push">
           {{--  --}}
    <div class="col-sm-3 col-xxl-2">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                    {{$HomeCount::all_later()}}
                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Later Lead') }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-gem fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{ route('pending.lead','ConnectCC') }}">
                            <span>{{ __('Due for Update') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            {{--  --}}
           {{--  --}}
    <div class="col-sm-3 col-xxl-2">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                    {{$HomeCount::active_lead_count()}}
                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Activate Lead') }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-gem fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{ route('pending.lead','ConnectCC') }}">
                            <span>{{ __('Activate Lead') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            {{--  --}}
           {{--  --}}
    <div class="col-sm-3 col-xxl-2">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$provider::TotalLeadActiveNonVerified('1.11','postpaid','TTF')}}
                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Active Non Verified') }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-gem fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{ route('pending.lead','ConnectCC') }}">
                            <span>{{ __('Active Non Verified') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            {{--  --}}
           {{--  --}}
    <div class="col-sm-3 col-xxl-2">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                    {{$provider::my_group_yesterday_assigned('Salman')}}
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
                            href="{{ route('pending.lead','ConnectCC') }}">
                            <span>{{ __('Yesterday Leads') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            {{--  --}}
</div>
