@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@push('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('AdminLTE')}}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/jszip/jszip.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": false, "lengthChange": false, "autoWidth": false, "ordering": true,
                "buttons": ["csv", "excel", "pdf", "print",] , "scrollX" : true
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $("#example3").DataTable({
                "responsive": false, "lengthChange": false, "autoWidth": false, "ordering": true,
                "buttons": ["csv", "excel", "pdf", "print",] , "scrollX" : true
            }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "buttons": ["csv", "excel", "pdf", "print",] , "scrollX" : true
            });
        });
    </script>

    <!-- FLOT CHARTS -->
    <script src="{{asset('AdminLTE')}}/plugins/flot/jquery.flot.js"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="{{asset('AdminLTE')}}/plugins/flot/plugins/jquery.flot.resize.js"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="{{asset('AdminLTE')}}/plugins/flot/plugins/jquery.flot.pie.js"></script>

    <script>
        $(function () {
          /*
           * DONUT CHART
           * -----------
           */
      
          var donutData = [
            {
              label: '<i class="fas fa-mars"></i> Laki-laki',
              data : {{$lakiLaki}},
              color: '#3B71CA'
            },
            {
              label: '<i class="fas fa-venus"></i> Perempuan',
              data : {{$perempuan}},
              color: 'pink'
            }
          ]
          $.plot('#donut-chart', donutData, {
            series: {
              pie: {
                show       : true,
                radius     : 1,
                innerRadius: 0.5,
                label      : {
                  show     : true,
                  radius   : 2 / 3,
                  formatter: labelFormatter,
                  threshold: 0.1
                }
      
              }
            },
            legend: {
              show: false
            }
          })
          /*
           * END DONUT CHART
           */
        })

        /*
         * Custom Label formatter
         * ----------------------
         */
        function labelFormatter(label, series) {
          return '<div style="font-size:15px; text-align:center; padding:5px; color: black; font-weight: 600;">'
            + label
            + '<br>'
            + Math.round(series.percent) + '%</div>'
        }
    </script>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                            <li class="breadcrumb-item active"><i class="fas fa-tachometer-alt"></i> Dashboard</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (Auth::user()->role_id == 1)
                    <!-- Info boxes -->
                    <div class="row justify-content-center">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total User</span>
                                    <span class="info-box-number">
                                        {{$allUser}} Pengguna
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-check"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">User Terverifikasi</span>
                                    <span class="info-box-number">{{$userVerified}} Pengguna</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-times"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">User Not Verified</span>
                                    <span class="info-box-number">{{$userNotVerified}} Pengguna</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>
                        
                    </div>
                    <!-- /.row -->
                @endif

                <!-- Info boxes -->
                <div class="row justify-content-center">
                    @if (Auth::user()->role_id == 1)
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-boxes"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total Barang</span>
                                    <span class="info-box-number">
                                        {{$totalBarang}} Aset
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-building"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total Fasilitas</span>
                                    <span class="info-box-number">
                                        {{$totalFasilitas}} Aset
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-file-download"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Ajuan Peminjaman Barang</span>
                                    <span class="info-box-number">{{$totalAjuanBarang}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-file-download"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Ajuan Peminjaman Fasilitas</span>
                                    <span class="info-box-number">{{$totalAjuanFasilitas}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    @endif

                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <h5 class="text-center col-12 mb-2 mt-1"><strong>Peminjaman Fasilitas & Barang</strong></h5>

                    @if (Auth::user()->role_id == 1)
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-clipboard-check"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Barang Disetujui</span>
                                    <span class="info-box-number">{{$ajuanBarangSetuju}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-clipboard-check"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Fasilitas Disetujui</span>
                                    <span class="info-box-number">{{$ajuanFasilitasSetuju}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clipboard-check"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Barang Waiting</span>
                                    <span class="info-box-number">{{$ajuanBarangWait}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
    
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clipboard-check"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Fasilitas Waiting</span>
                                    <span class="info-box-number">{{$ajuanFasilitasWait}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clipboard-check"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Barang Ditolak</span>
                                    <span class="info-box-number">{{$ajuanBarangReject}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clipboard-check"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Fasilitas Ditolak</span>
                                    <span class="info-box-number">{{$ajuanFasilitasReject}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    @else
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-boxes"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total Barang</span>
                                    <span class="info-box-number">
                                        {{$totalBarang}} Aset
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-clipboard-check"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Barang Disetujui</span>
                                    <span class="info-box-number">{{$ajuanBarangSetuju}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
    
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-clipboard-check"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Fasilitas Disetujui</span>
                                    <span class="info-box-number">{{$ajuanFasilitasSetuju}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-building"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total Fasilitas</span>
                                    <span class="info-box-number">
                                        {{$totalFasilitas}} Aset
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clipboard-check"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Barang Ditolak</span>
                                    <span class="info-box-number">{{$ajuanBarangReject}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
    
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clipboard-check"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Barang Waiting</span>
                                    <span class="info-box-number">{{$ajuanBarangWait}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
    
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clipboard-check"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Fasilitas Waiting</span>
                                    <span class="info-box-number">{{$ajuanFasilitasWait}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
    
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clipboard-check"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Fasilitas Ditolak</span>
                                    <span class="info-box-number">{{$ajuanFasilitasReject}} Ajuan</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    @endif

                    
                </div>
                <!-- /.row -->

                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <div class="col-md-8">

                        <!-- TABLE: Ajuan Baru -->
                        <div class="card">
                            <div class="card-header border-transparent bg-info">
                                @if (Auth::user()->role_id == 1)
                                    <h3 class="card-title">Ajuan Peminjaman Baru</h3>
                                @else
                                    <h3 class="card-title">Ajuan Peminjaman Anda</h3>
                                @endif

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm text-sm nowrap" id="example2">
                                        <thead class="bg-info">
                                            <tr>
                                                <th class="text-center align-middle">Tanggal Ajuan Peminjaman</th>
                                                <th class="text-center align-middle">Barang/Fasilitas Dipinjam</th>

                                                @if (Auth::user()->role_id == 1)
                                                    <th class="text-center align-middle">Nama Peminjam</th>
                                                @endif

                                                <th class="text-center align-middle">Jumlah Yang Dipinjam</th>
                                                <th class="text-center align-middle">Tanggal Peminjaman</th>
                                                <th class="text-center align-middle">Tanggal Pengambalian</th>
                                                <th class="text-center align-middle">Keperluan Pinjaman</th>
                                                <th class="text-center align-middle">Status Pinjaman</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (Auth::user()->role_id == 1)
                                                @foreach ($dataPinjamBarang as $item)
                                                    <tr>
                                                        <td class="text-center align-middle">{{date('H:i:s - d M Y', strtotime($item->created_at))}}</td>
                                                        <td class="text-center align-middle">{{$item->barang->nama_barang}}</td>
                                                        <td class="text-center align-middle"><a href="{{url('/profile/' . $item->user->id)}}">{{$item->user->nama_lengkap}}</a></td>
                                                        <td class="text-center align-middle">{{$item->jumlah_pinjaman}} {{$item->barang->satuan_barang}}</td>
                                                        <td class="text-center align-middle">{{date('d M Y', strtotime($item->tanggal_pinjam))}}</td>
                                                        <td class="text-center align-middle">{{date('d M Y', strtotime($item->tanggal_kembali))}}</td>
                                                        <td class="text-center align-middle">{{$item->keterangan}}</td>
    
                                                        @if ($item->status_pinjaman == "Menunggu Persetujuan")
                                                            <td class="text-center align-middle text-warning">
                                                                <div class="btn btn-sm text-warning" data-toggle="modal" data-target="#modal-approve{{$item->id}}"><b>{{$item->status_pinjaman}}</b></div>
                                                            </td>
                                                        @elseif($item->status_pinjaman == "Disetujui")
                                                            <td class="text-center align-middle">
                                                                <div class="btn btn-sm text-success" data-toggle="modal" data-target="#modal-approve{{$item->id}}"><b>{{$item->status_pinjaman}}</b></div>
                                                            </td>
                                                        @else
                                                            <td class="text-center align-middle"><div class="btn btn-sm text-danger" data-toggle="modal" data-target="#modal-approve{{$item->id}}"><b>{{$item->status_pinjaman}}</b></div></td>
                                                        @endif
                                                    </tr>
                                                @endforeach

                                                @foreach ($dataPinjamFasilitas as $item)
                                                    <tr>
                                                        <td class="text-center align-middle">{{date('H:i:s - d M Y', strtotime($item->created_at))}}</td>
                                                        <td class="text-center align-middle">{{$item->fasilitas->nama_fasilitas}}</td>
                                                        <td class="text-center align-middle"><a href="{{url('/profile/' . $item->user->id)}}">{{$item->user->nama_lengkap}}</a></td>
                                                        <td class="text-center align-middle">{{$item->jumlah_pinjaman}} {{$item->fasilitas->satuan_fasilitas}}</td>
                                                        <td class="text-center align-middle">{{date('d M Y', strtotime($item->tanggal_pinjam))}}</td>
                                                        <td class="text-center align-middle">{{date('d M Y', strtotime($item->tanggal_kembali))}}</td>
                                                        <td class="text-center align-middle">{{$item->keterangan}}</td>

                                                        @if ($item->status_pinjaman == "Menunggu Persetujuan")
                                                            <td class="text-center align-middle text-warning">
                                                                <div class="btn btn-sm text-warning" data-toggle="modal" data-target="#modal-approve-fasilitas{{$item->id}}"><b>{{$item->status_pinjaman}}</b></div>
                                                            </td>
                                                        @elseif($item->status_pinjaman == "Disetujui")
                                                            <td class="text-center align-middle">
                                                                <div class="btn btn-sm text-success" data-toggle="modal" data-target="#modal-approve-fasilitas{{$item->id}}"><b>{{$item->status_pinjaman}}</b></div>
                                                            </td>
                                                        @else
                                                            <td class="text-center align-middle"><div class="btn btn-sm text-danger" data-toggle="modal" data-target="#modal-approve-fasilitas{{$item->id}}"><b>{{$item->status_pinjaman}}</b></div></td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            
                                            @else
                                                @foreach ($dataPinjamBarangUser as $item)
                                                    <tr>
                                                        <td class="text-center align-middle">{{date('H:i:s - d M Y', strtotime($item->created_at))}}</td>
                                                        <td class="text-center align-middle">{{$item->barang->nama_barang}}</td>
                                                        <td class="text-center align-middle">{{$item->jumlah_pinjaman}} {{$item->barang->satuan_barang}}</td>
                                                        <td class="text-center align-middle">{{date('d M Y', strtotime($item->tanggal_pinjam))}}</td>
                                                        <td class="text-center align-middle">{{date('d M Y', strtotime($item->tanggal_kembali))}}</td>
                                                        <td class="text-center align-middle">{{$item->keterangan}}</td>

                                                        @if ($item->status_pinjaman == "Menunggu Persetujuan")
                                                            <td class="text-center align-middle text-warning">
                                                                <b>{{$item->status_pinjaman}}</b>
                                                            </td>
                                                        @elseif($item->status_pinjaman == "Disetujui")
                                                            <td class="text-center align-middle text-success">
                                                                <b>{{$item->status_pinjaman}}</b>
                                                            </td>
                                                        @elseif($item->status_pinjaman == "Selesai")
                                                            <td class="text-center align-middle text-white">
                                                                <button class="btn btn-sm btn-success disabled"><b>{{$item->status_pinjaman}}</b></button>
                                                            </td>
                                                        @else
                                                            <td class="text-center align-middle text-danger">
                                                                <b>{{$item->status_pinjaman}}</b>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach

                                                @foreach ($dataPinjamFasilitasUser as $item)
                                                    <tr>
                                                        <td class="text-center align-middle">{{date('H:i:s - d M Y', strtotime($item->created_at))}}</td>
                                                        <td class="text-center align-middle">{{$item->fasilitas->nama_fasilitas}}</td>
                                                        <td class="text-center align-middle">{{$item->jumlah_pinjaman}} {{$item->fasilitas->satuan_fasilitas}}</td>
                                                        <td class="text-center align-middle">{{date('d M Y', strtotime($item->tanggal_pinjam))}}</td>
                                                        <td class="text-center align-middle">{{date('d M Y', strtotime($item->tanggal_kembali))}}</td>
                                                        <td class="text-center align-middle">{{$item->keterangan}}</td>

                                                        @if ($item->status_pinjaman == "Menunggu Persetujuan")
                                                            <td class="text-center align-middle text-warning">
                                                                <b>{{$item->status_pinjaman}}</b>
                                                            </td>
                                                        @elseif($item->status_pinjaman == "Disetujui")
                                                            <td class="text-center align-middle text-success">
                                                                <b>{{$item->status_pinjaman}}</b>
                                                            </td>
                                                        @elseif($item->status_pinjaman == "Selesai")
                                                            <td class="text-center align-middle text-white">
                                                                <button class="btn btn-sm btn-success disabled"><b>{{$item->status_pinjaman}}</b></button>
                                                            </td>
                                                        @else
                                                            <td class="text-center align-middle text-danger">
                                                                <b>{{$item->status_pinjaman}}</b>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.card-body -->

                            @if ((Auth::user()->role_id == 1) || ((Auth::user()->biodata->tempat_lahir != null) && (Auth::user()->biodata->tanggal_lahir != null) && (Auth::user()->biodata->alamat != null)))
                                <div class="card-footer clearfix">
                                    <a href="{{url('/data-peminjaman')}}" class="btn btn-sm btn-info float-right shadow">Lihat Semua</a>
                                </div>
                            @else
                                <div class="card-footer clearfix swalDefaultError">
                                    <button type="button" class="btn btn-sm btn-info float-right shadow">Lihat Semua</button>
                                </div>
                            @endif
                            <!-- /.card-footer -->
                        </div>
                        <!-- /.card -->

                        @if (Auth::user()->role_id == 1)
                            <!-- Papan Informasi -->
                            <div class="card">
                                <div class="card-header bg-info border-transparent">
                                    <h3 class="card-title">Informasi</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <img src="{{asset('assets/' . $informasi->foto_informasi)}}" alt="Gambar Informasi" width="500px" class="rounded mx-auto d-block img-fluid">
                                    </div>
                                    <h5>Selamat Datang, {{Auth::user()->nama_lengkap}}</h5>
                                    {!! $informasi->deskripsi_informasi !!}
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    @if (Auth::user()->role_id == 1)
                                        <a href="{{url('/informasi/deskripsi/edit')}}" class="btn btn-sm btn-success float-right"><i class="fas fa-edit"></i> Edit</a>
                                    @endif
                                </div>
                                <!-- /.card-footer -->
                            </div>
                            <!-- /.card -->
                        @endif
                    </div>
                    <!-- /.col -->

                    @if (Auth::user()->role_id == 1)
                        <div class="col-md-4">
                            <!-- USERS LIST -->
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">Pengguna Baru</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($newUser as $item)
                                            <div class="col-3 col-lg-3 col-sm-3">
                                                <img src="{{asset('assets/profil/foto/' . $item->biodata->foto)}}" alt="User Image" width="80px" height="80px" class="rounded-circle">
                                                <a class="users-list-name" href="{{url('/profile/' . $item->id)}}">{{$item->nama_lengkap}}</a>

                                                @php
                                                    $created = date('d M y', strtotime($item->created_at));
                                                @endphp

                                                @if ($created == $today)
                                                    <span class="users-list-date">Today</span>
                                                @else
                                                    <span class="users-list-date">{{date('H:i - d M', strtotime($item->created_at))}}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- /.users-list -->
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-center">
                                    <a href="{{url('/pengguna')}}">View All Users</a>
                                </div>
                                <!-- /.card-footer -->
                            </div>
                            <!--/.card -->
                            
                            <!-- Klasifikasi Gender Pengguna -->
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="far fa-chart-bar"></i>
                                        Klasifikasi Gender Pengguna
                                    </h3>
                
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="donut-chart" style="height: 300px;"></div>
                                </div>
                                <!-- /.card-body-->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    @else
                        <div class="col-md-4">
                            <!-- Papan Informasi -->
                            <div class="card">
                                <div class="card-header bg-info border-transparent">
                                    <h3 class="card-title">Informasi</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <img src="{{asset('assets/' . $informasi->foto_informasi)}}" alt="Gambar Informasi" width="500px" class="rounded mx-auto d-block img-fluid">
                                    </div>
                                    <h5>Selamat Datang, {{Auth::user()->nama_lengkap}}</h5>
                                    {!! $informasi->deskripsi_informasi !!}
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    @if (Auth::user()->role_id == 1)
                                        <a href="{{url('/informasi/deskripsi/edit')}}" class="btn btn-sm btn-success float-right"><i class="fas fa-edit"></i> Edit</a>
                                    @endif
                                </div>
                                <!-- /.card-footer -->
                            </div>
                            <!-- /.card -->
                        </div>
                    @endif
                </div>
                <!-- /.row -->
            </div>
            <!--/. container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection