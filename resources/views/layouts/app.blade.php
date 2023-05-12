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
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('img/brand/favicon.png') }}" rel="icon" type="image/png">
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
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      <a class="navbar-brand pt-0" href="{{ url('/') }}">
        <img src="{{ asset('img/brand/logo.png') }}" class="navbar-brand-img" alt="{{ config('app.name', 'Farmavision') }}">
      </a>
      <!-- User -->
      <ul class="nav align-items-center d-md-none">
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
              <img src="{{ asset('img/icons/user.png') }}">
              </span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
            <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
            </div>
            @guest
            @else

            <a href="{{ route('edituser') }}" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>{{ __('Edit Profile') }}</span>
            </a>

            <a href="{{ env('APP_COMPANY_SUPPORT') }}" class="dropdown-item">
              <i class="ni ni-support-16"></i>
              <span>{{ __('Contact support') }}</span>
            </a>


            <div class="dropdown-divider"></div>

            <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                          document.getElementById('logout-forms').submit();">
                <i class="ni ni-user-run"></i>
                <span>{{ __('Logout') }}</span>
            </a>

            <form id="logout-forms" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            @endguest
          </div>
        </li>
      </ul>
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="{{ url('/') }}">
                <img src="{{ asset('img/brand/logo.png') }}" alt="{{ config('app.name', 'Canal') }}">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Navigation -->
        @guest
        @else
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
              <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
            </a>
          </li>

          @guest

            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">
                  <i class="ni ni-key-25 text-info"></i> {{ __('Login') }}
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">
                  <i class="ni ni-circle-08 text-pink"></i> {{ __('Register') }}
                </a>
            </li>

          @else

            @if(Auth::user()->hasRole('admin'))

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('userlist') }}">
                    <i class="ni ni-bullet-list-67 text-red"></i> {{ __('User\'s list') }}
                    </a>
                </li>
            @endif

            @if(Auth::user()->hasRole('user'))

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('lista') }}">
                    <i class="ni ni-bullet-list-67 text-red"></i> {{ __('Playlists') }}
                    </a>
                </li>

            @endif

            <li class="nav-item">
                <a class="nav-link" href="{{ route('edituser') }}">
                    <i class="ni ni-single-02 text-yellow"></i> {{ __('Edit Profile') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="ni ni-user-run text-info"></i> {{ __('Logout') }}
                </a>
            </li>


            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          @endguest
        </ul>
        @endguest

        <hr class="my-3">
        <ul class="navbar-nav mb-md-3">
          <li class="nav-item">
            <a class="nav-link" href="{{ env('APP_MANUAL_CONTROL') }}">
              <i class="ni ni-box-2"></i> {{ __('Download Manual') }}
            </a>
          </li>
        </ul>
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
                <li class="nav-item">
                  <a href="{{ env('APP_COMPANY_SUPPORT') }}" class="nav-link" target="_blank">{{ __('Contact support') }}</a>
                </li>
              </ul>
            </div>
          </div>
        </footer>
      </div>
    </div>

    {{--Dropzone Preview Template--}}
    <div id="preview" style="display: none;">
      <div class="dz-preview dz-file-preview">
        <div class="dz-image"><img data-dz-thumbnail /></div>
        <div class="dz-details">
          <div class="dz-size"><span data-dz-size></span></div>
          <div class="dz-filename"><span data-dz-name></span></div>
        </div>
        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
        <div class="dz-error-message"><span data-dz-errormessage></span></div>
        <div class="dz-success-mark"><i class="fas fa-check-circle" style="font-size:54px;"></i></div>
        <div class="dz-error-mark"><i class="fas fa-exclamation-circle" style="font-size:54px;"></i></div>
      </div>
    </div>
    {{--End of Dropzone Preview Template--}}

    <script src="{{ asset('js/dropzone-config.js') }}"></script>
</body>
</html>
