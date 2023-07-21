<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/argon.min.js') }}"></script>
    <script src="{{ asset('js/html5sortable.js') }}"></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('img/brand/favicon.png') }}?2" rel="icon" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('css/argon.farma.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
</head>
<body>
  <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      <a class="navbar-brand pt-0" href="{{ url('/') }}">
        <img src="{{ asset('img/brand/logo.png') }}" class="navbar-brand-img" alt="{{ config('app.name', 'Farmavision') }}">
      </a>
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="{{ url('/') }}"><img src="{{ asset('img/brand/logo.png') }}" alt="{{ config('app.name', 'Canal') }}"></a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main">
                <span></span><span></span>
              </button>
            </div>
          </div>
        </div>

        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="{{route('home')}}"><i class="ni ni-tv-2"></i>{{__('Home')}}</a>
          </li>

          @if($isAdmin)
              <li class="nav-item">
                  <a class="nav-link" href="{{route('users')}}"><i class="ni ni-bullet-list-67 text-primary"></i>{{__('Users')}}</a>
              </li>
          @else
            <li class="nav-item">
                <a class="nav-link" href="{{route('playlists.user')}}"><i class="ni ni-bullet-list-67 text-red"></i>{{__('Devices')}}</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('profile',Auth::user()->id)}} "><i class="ni ni-single-02 text-yellow"></i>{{__('Edit Profile')}}</a>
            </li>
          @endif

          <li class="nav-item">
              <a class="nav-link" href="{{route('issues')}} "><i class="ni ni-support-16 text-purple"></i> {{__('Issues')}}</a>
          </li>

          <li class="nav-item">
              <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                  <i class="ni ni-user-run text-info"></i>{{__('Logout')}}
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
          </li>
        </ul>

        @if ($isAdmin)
        <hr class="my-3">
        <h6 class="navbar-heading text-muted">{{__('Actions')}}</h6>

        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="{{ route('EncodeQueue') }}" target="_blank"><i class="ni ni-button-play text-red"></i>{{ __('Encode queue') }}</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('dev.phpinfo') }}" target="_blank"><i class="ni ni-app text-purple"></i>phpinfo</a></li>
        </ul>
        @endif
      </div>
    </div>
  </nav>

  <div class="main-content">
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-4"></div>
      <!-- Page content -->
      <div class="container-fluid mt--8">
        <div class="row">
          <div class="col">
            <div class="card shadow border-0">@yield('content')</div>
          </div>
        </div>
        <!-- Footer -->
        <footer class="footer">
          <div class="row align-items-center justify-content-xl-between">
            <div class="col-xl-6">
              <div class="copyright text-center text-xl-left text-muted">
                <?php echo date('Y'); ?> &copy; <a href="{{ env('APP_COMPANY_URL') }}" target="_blank">{{ env('APP_COMPANY') }}</a>
              </div>
            </div>
            <div class="col-xl-6">
              <ul class="nav nav-footer justify-content-center justify-content-xl-end">
                <li class="nav-item"><a href="{{ env('APP_COMPANY_SUPPORT') }}" class="nav-link" target="_blank">{{ __('Contact support') }}</a></li>
              </ul>
            </div>
          </div>
        </footer>
      </div>
    </div>
</body>
</html>
