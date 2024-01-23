@inject('HomeCount', 'App\Http\Controllers\CoordinaterController')
@inject('provider', 'App\Http\Controllers\HomeController')
 <div class="row items-push">
            <div class="col-sm-3 col-xxl-3">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$HomeCount::emirate_timeout_lead()}}
                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Due for Update') }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-gem fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{route('emirate.time.out')}}">
                            <span>{{ __('View all Leads') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            <div class="col-sm-3 col-xxl-3">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$HomeCount::emirate_appointment_lead_count()}}

                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Lead Reminder') }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-gem fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{route('emirate.appointment')}}">
                            <span>{{ __('Lead Reminder') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            <div class="col-sm-3 col-xxl-3">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$HomeCount::emirate_later_today()}}

                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Later Leads') }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-gem fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{route('emirate.laterlead.today')}}">
                            <span>{{ __('View Leads') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            {{--  --}}
            <div class="col-sm-3 col-xxl-3">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$HomeCount::emirate_mycount_daily()}}


                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Un Assigned Leads') }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-gem fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{route('emirate.activation.proceed.daily')}}">
                            <span>{{ __('View Leads') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            {{--  --}}
            {{--  --}}
            <div class="col-sm-3 col-xxl-3">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$HomeCount::emirate_agent_count_daily()}}


                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Assigned Leads') }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-gem fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{route('emirate.my.proceed.daily')}}">
                            <span>{{ __('View Lead') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            {{--  --}}
            {{--  --}}
            <div class="col-sm-3 col-xxl-3">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$HomeCount::emirate_followup_daily()}}


                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Follow Up Leads') }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-gem fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{route('emirate.followup.combine.today')}}">
                            <span>{{ __('View Leads') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            {{--  --}}

            {{--  --}}
            <div class="col-sm-3 col-xxl-3">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                {{$HomeCount::emirate_active_lead_count_daily()}}


                            </dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                {{ __('Active Leads') }}</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-gem fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{route('emirate.total-active-lead.daily')}}">
                            <span>{{ __('View Leads') }}</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            {{--  --}}
        </div>
