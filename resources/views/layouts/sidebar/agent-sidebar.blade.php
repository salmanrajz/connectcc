<div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
          <ul class="nav-main">
            <li class="nav-main-item">
              <a class="nav-main-link{{ request()->is('dashboard') ? ' active' : '' }}" href="{{route('home')}}">
                <i class="nav-main-link-icon si si-cursor"></i>
                <span class="nav-main-link-name">Dashboard</span>
              </a>
            </li>
            {{-- {{auth()->user()->role}} --}}
            @if(auth()->user()->role == 'sale')
            {{-- {{auth()->user}} --}}
            {{-- @role('Sale') --}}
            <li class="nav-main-heading">Leads</li>
            <li class="nav-main-item{{ request()->is('pages/*') ? ' open' : '' }}">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                <i class="nav-main-link-icon si si-bulb"></i>
                <span class="nav-main-link-name">Leads</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item">
                  <a class="nav-main-link{{ request()->is('pages/datatables') ? ' active' : '' }}" href="{{route('add.new.lead')}}">
                    <span class="nav-main-link-name">Add New Postpaid Lead</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link{{ request()->is('pages/slick') ? ' active' : '' }}" href="/pages/slick">
                    <span class="nav-main-link-name">Add New Home Wifi</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link{{ route('mylead') ? ' active' : '' }}" href="{{route('mylead')}}">
                    <span class="nav-main-link-name">View All Leads</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-main-heading">DNC</li>
            <li class="nav-main-item{{ request()->is('pages/*') ? ' open' : '' }}">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                <i class="nav-main-link-icon si si-bulb"></i>
                <span class="nav-main-link-name">DNC CHECKER</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item">
                  <a class="nav-main-link{{ request()->is('pages/datatables') ? ' active' : '' }}" href="#">
                    <span class="nav-main-link-name">Normal DNC Checker</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link{{ request()->is('pages/slick') ? ' active' : '' }}" href="{{route('dnc_checker_number')}}">
                    <span class="nav-main-link-name">WhatsApp DNC Checker</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link{{ request()->is('pages/slick') ? ' active' : '' }}" href="{{route('add.dnc.number.agent')}}">
                    <span class="nav-main-link-name">Informed DNC</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link{{ route('mylead') ? ' active' : '' }}" href="{{route('dncr.request')}}">
                    <span class="nav-main-link-name">DNCR File Request</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-main-heading">Number Pool</li>
            <li class="nav-main-item open">
              <a class="nav-main-link" href="{{route('number-system')}}">
                <i class="nav-main-link-icon si si-globe"></i>
                <span class="nav-main-link-name">New Number Pool</span>
              </a>
            </li>
            <li class="nav-main-item open">
              <a class="nav-main-link" href="/">
                <i class="nav-main-link-icon si si-globe"></i>
                <span class="nav-main-link-name">Reserved Number Pool</span>
              </a>
            </li>
            @endif
            @if(auth()->user()->role == 'Admin')
            <li class="nav-main-item open">
              <a class="nav-main-link" href="{{route('view.users')}}">
                <i class="nav-main-link-icon si si-globe"></i>
                <span class="nav-main-link-name">Users</span>
              </a>
            </li>
            @endif
            {{-- {{auth()->user()->role}} --}}
            @if(auth()->user()->role == 'Cordination')
            <li class="nav-main-item open">
              <a class="nav-main-link" href="{{route('request-agent.index')}}">
                <i class="nav-main-link-icon si si-globe"></i>
                <span class="nav-main-link-name">Request Agent</span>
              </a>
            </li>
            <li class="nav-main-heading">Number Pool</li>
            <li class="nav-main-item open">
              <a class="nav-main-link" href="{{route('number-system')}}">
                <i class="nav-main-link-icon si si-globe"></i>
                <span class="nav-main-link-name">New Number Pool</span>
              </a>
            </li>
            <li class="nav-main-item">
                  <a class="nav-main-link{{ route('admin.ourlead') ? ' active' : '' }}" href="{{route('admin.ourlead')}}">
                    <span class="nav-main-link-name">View All Leads</span>
                  </a>
                </li>

            @endif
            @if(auth()->user()->role == 'Emirate Coordinator' || auth()->user()->role == 'MainCoordinator' || auth()->user()->role == 'Admin' || auth()->user()->role == 'MainAdmin')
            <li class="nav-main-item open">
              <a class="nav-main-link" href="{{route('checknumber.status')}}">
                <i class="nav-main-link-icon si si-globe"></i>
                <span class="nav-main-link-name">Check Number</span>
              </a>
            </li>
            <li class="nav-main-item open">
              <a class="nav-main-link" href="{{route('checkcustomernumber.status')}}">
                <i class="nav-main-link-icon si si-globe"></i>
                <span class="nav-main-link-name">Check Customer Number</span>
              </a>
            </li>
            <li class="nav-main-item open">
              <a class="nav-main-link" href="{{route('checkleadnumber.status')}}">
                <i class="nav-main-link-icon si si-globe"></i>
                <span class="nav-main-link-name">Check Lead Status</span>
              </a>
            </li>
            @endif

            {{-- {{auth()->user()->role}} --}}

          </ul>

        </div>
        <!-- END Side Navigation -->
      </div>
