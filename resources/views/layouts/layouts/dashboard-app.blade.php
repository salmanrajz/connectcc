<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-header></x-header>
</head>
<body class="v-light vertical-nav fix-header fix-sidebar">
<x-pre-loader></x-pre-loader>

      <div id="main-wrapper">
        <!-- header -->
       <x-main-header></x-main-header>
        <!-- #/ header -->
        <!-- sidebar -->
        <x-sidebar></x-sidebar>
        <!-- #/ sidebar -->
        <!-- content body -->

        @yield('main-content')
        <!-- #/ content body -->
        <!-- footer -->
        <div class="footer">
            <div class="copyright">
                {{-- <p>Copyright &copy; <a href="https://ule.merkulov.design">Ule</a> 2019, by <a href="https://1.envato.market/tf-merkulove" target="_blank">merkulove</a></p> --}}
            </div>
        </div>
        <!-- #/ footer -->
    </div>
    </div>
    <x-footer></x-footer>
</body>
</html>
