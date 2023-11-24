<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $aplikasi = App\Models\ApplicationName::first();
    @endphp
    <title>@yield('title') - {{$aplikasi->application_owner}}</title>
    <link rel="icon" href="{{asset('assets/web-config/' . $aplikasi->application_logo)}}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/fontawesome-free/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/dist/css/adminlte.min.css">
    @stack('styles')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/toastr/toastr.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{asset('AdminLTE')}}/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div> --}}

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                {{-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li> --}}
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{url('/')}}" class="brand-link">
                <img src="{{asset('assets/web-config/' . $aplikasi->application_logo)}}" alt="Website Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{$aplikasi->application_name}}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{asset('assets/logo-user.png')}}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{Auth::user()->nama_lengkap}}</a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="{{ url('/dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/profile/' . Auth::user()->id) }}" class="nav-link">
                                <i class="nav-icon fas fa-user-circle"></i>
                                <p>
                                    Profil
                                </p>
                            </a>
                        </li>
                        
                        @if (Auth::user()->role_id == 1)
                            <li class="nav-item">
                                <a href="{{ url('/pengguna') }}" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Pengguna
                                    </p>
                                </a>
                            </li>

                        @endif

                        <li class="nav-item">
                            <a href="{{ url('/data-barang') }}" class="nav-link">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>
                                    Data Barang
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/data-fasilitas') }}" class="nav-link">
                                <i class="nav-icon fas fa-building"></i>
                                <p>
                                    Data Fasilitas
                                </p>
                            </a>
                        </li>
                        
                        @if ((Auth::user()->role_id == 1) || ((Auth::user()->biodata->tempat_lahir != null) && (Auth::user()->biodata->tanggal_lahir != null) && (Auth::user()->biodata->alamat != null)))
                            <li class="nav-item">
                                <a href="{{ url('/data-peminjaman') }}" class="nav-link">
                                    <i class="nav-icon fas fa-file-download"></i>
                                    <p>
                                        Data Peminjaman
                                    </p>
                                </a>
                            </li>
                        @else
                            <li class="nav-item swalDefaultError">
                                <a type="button" class="nav-link">
                                    <i class="nav-icon fas fa-file-download"></i>
                                    <p>
                                        Data Peminjaman
                                    </p>
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Settings
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview text-sm" style="background-color: #41434D">

                                @if(Auth::user()->role_id == 1)
                                    <li class="nav-item">
                                        <a href="{{url('/data-aplikasi')}}" class="nav-link">
                                        <i class="nav-icon fas fa-wrench"></i>
                                            <p>Data Aplikasi</p>
                                        </a>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a href="{{url('/akun/ubah-password')}}" class="nav-link">
                                        <i class="nav-icon fas fa-lock"></i>
                                        <p>Ubah Password</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{url('/contact-person')}}" class="nav-link">
                                <i class="nav-icon fas fa-headset"></i>
                                <p>
                                    Contact Person
                                </p>
                            </a>
                        </li>                

                        <li class="nav-item">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>
                                    Sign Out
                                </p>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        @yield('content')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2023 <a href="{{$aplikasi->link_application_developer}}" target="_blank">{{$aplikasi->application_developer}}</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> {{$aplikasi->application_version}}
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{asset('AdminLTE')}}/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="{{asset('AdminLTE')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="{{asset('AdminLTE')}}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('AdminLTE')}}/dist/js/adminlte.js"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="{{asset('AdminLTE')}}/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/raphael/raphael.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/jquery-mapael/maps/usa_states.min.js"></script>
    <!-- ChartJS -->
    <script src="{{asset('AdminLTE')}}/plugins/chart.js/Chart.min.js"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('AdminLTE')}}/dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{asset('AdminLTE')}}/dist/js/pages/dashboard2.js"></script>
    @include('sweetalert::alert')
    @stack('scripts')
    <!-- SweetAlert2 -->
    <script src="{{asset('AdminLTE')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="{{asset('AdminLTE')}}/plugins/toastr/toastr.min.js"></script>

    <script>
        $(function() {
          var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
          });
      
          $('.swalDefaultSuccess').click(function() {
            Toast.fire({
              icon: 'success',
              title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.swalDefaultInfo').click(function() {
            Toast.fire({
              icon: 'info',
              title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.swalDefaultError').click(function() {
            Toast.fire({
              icon: 'error',
              title: 'Belum melengkapi data profile.'
            })
          });
          $('.swalDefaultWarning').click(function() {
            Toast.fire({
              icon: 'warning',
              title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.swalDefaultQuestion').click(function() {
            Toast.fire({
              icon: 'question',
              title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
      
          $('.toastrDefaultSuccess').click(function() {
            toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
          });
          $('.toastrDefaultInfo').click(function() {
            toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
          });
          $('.toastrDefaultError').click(function() {
            toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
          });
          $('.toastrDefaultWarning').click(function() {
            toastr.warning('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
          });
      
          $('.toastsDefaultDefault').click(function() {
            $(document).Toasts('create', {
              title: 'Toast Title',
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.toastsDefaultTopLeft').click(function() {
            $(document).Toasts('create', {
              title: 'Toast Title',
              position: 'topLeft',
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.toastsDefaultBottomRight').click(function() {
            $(document).Toasts('create', {
              title: 'Toast Title',
              position: 'bottomRight',
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.toastsDefaultBottomLeft').click(function() {
            $(document).Toasts('create', {
              title: 'Toast Title',
              position: 'bottomLeft',
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.toastsDefaultAutohide').click(function() {
            $(document).Toasts('create', {
              title: 'Toast Title',
              autohide: true,
              delay: 750,
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.toastsDefaultNotFixed').click(function() {
            $(document).Toasts('create', {
              title: 'Toast Title',
              fixed: false,
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.toastsDefaultFull').click(function() {
            $(document).Toasts('create', {
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
              title: 'Toast Title',
              subtitle: 'Subtitle',
              icon: 'fas fa-envelope fa-lg',
            })
          });
          $('.toastsDefaultFullImage').click(function() {
            $(document).Toasts('create', {
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
              title: 'Toast Title',
              subtitle: 'Subtitle',
              image: '../../dist/img/user3-128x128.jpg',
              imageAlt: 'User Picture',
            })
          });
          $('.toastsDefaultSuccess').click(function() {
            $(document).Toasts('create', {
              class: 'bg-success',
              title: 'Toast Title',
              subtitle: 'Subtitle',
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.toastsDefaultInfo').click(function() {
            $(document).Toasts('create', {
              class: 'bg-info',
              title: 'Toast Title',
              subtitle: 'Subtitle',
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.toastsDefaultWarning').click(function() {
            $(document).Toasts('create', {
              class: 'bg-warning',
              title: 'Toast Title',
              subtitle: 'Subtitle',
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.toastsDefaultDanger').click(function() {
            $(document).Toasts('create', {
              class: 'bg-danger',
              title: 'Toast Title',
              subtitle: 'Subtitle',
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
          $('.toastsDefaultMaroon').click(function() {
            $(document).Toasts('create', {
              class: 'bg-maroon',
              title: 'Toast Title',
              subtitle: 'Subtitle',
              body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
          });
        });
    </script>
</body>
</html>
