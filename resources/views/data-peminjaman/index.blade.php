@extends('layouts.app')

@section('title')
    Data Peminjaman
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
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "buttons": ["csv", "excel", "pdf", "print",] , "scrollX" : true
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');;
        });
    </script>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data Peminjaman Barang & Fasilitas</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active"><i class="fas fa-file-download"></i> Data Peminjaman</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <h3 class="card-title">Data Peminjaman Barang Desa</h3>
                                <a href="{{url('/data-peminjaman/barang/tambah')}}">
                                    <button class="btn btn-sm btn-success float-right shadow">Tambah</button>
                                </a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm text-sm nowrap">
                                    <thead class="bg-info">
                                        <tr>
                                            <th class="text-center align-middle">Tanggal Ajuan Peminjaman</th>
                                            <th class="text-center align-middle">Nama Barang Dipinjam</th>
                                            <th class="text-center align-middle">Nama Peminjam</th>
                                            <th class="text-center align-middle">Jumlah Yang Dipinjam</th>
                                            <th class="text-center align-middle">Tanggal Peminjaman</th>
                                            <th class="text-center align-middle">Tanggal Pengambalian</th>
                                            <th class="text-center align-middle">Keperluan Pinjaman</th>
                                            <th class="text-center align-middle">Status Pinjaman</th>
                                            <th class="text-center align-middle">Action</th>
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
                                                    @elseif($item->status_pinjaman == "Selesai")
                                                        <td class="text-center align-middle">
                                                            <div class="btn btn-sm btn-success text-white" data-toggle="modal" data-target="#modal-approve{{$item->id}}"><b>{{$item->status_pinjaman}}</b></div>
                                                        </td>
                                                    @else
                                                        <td class="text-center align-middle"><div class="btn btn-sm text-danger" data-toggle="modal" data-target="#modal-approve{{$item->id}}"><b>{{$item->status_pinjaman}}</b></div></td>
                                                    @endif

                                                    <td class="text-center align-middle">
                                                        @if (($item->status_pinjaman == "Disetujui") || $item->status_pinjaman == "Selesai")
                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus{{$item->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        @elseif($item->status_pinjaman == "Menunggu Persetujuan")
                                                            @if (Auth::user()->role_id == 1)
                                                                <a href="{{url('/data-peminjaman/barang/edit/' . $item->id)}}"><button class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i></button></a>
                                                            @endif
                                                            
                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus{{$item->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        @else
                                                            <a href="{{url('/data-peminjaman/barang/edit/' . $item->id)}}"><button class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i></button></a>

                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus{{$item->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <!-- Modal Approve Akun -->
                                                <div class="modal fade" id="modal-approve{{$item->id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{url('/data-peminjaman/barang/approve/' . $item->id)}}" method="post">
                                                                @csrf
                                                                @method('put')

                                                                <div class="modal-header bg-success text-center">
                                                                    <h4 class="modal-title">Persetujuan Peminjaman Barang</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p class="text-center">
                                                                        <div class="row justify-content-center text-sm">
                                                                            <div class="col-4 col-lg-3">
                                                                                Nama Barang<br>
                                                                                Nama Peminjam<br>
                                                                                Tanggal Pinjam<br>
                                                                                Tanggal Kembali<br>
                                                                                Keperluan<br>

                                                                            </div>
                                                                            <div class="col-8 col-lg-8">
                                                                                : {{$item->barang->nama_barang}}<br>
                                                                                : {{$item->user->nama_lengkap}}<br>
                                                                                : {{date('d M Y', strtotime($item->tanggal_pinjam))}}<br>
                                                                                : {{date('d M Y', strtotime($item->tanggal_kembali))}}<br>
                                                                                : {{$item->keterangan}}<br>

                                                                            </div>
                                                                        </div>

                                                                        <div class="row text-center">
                                                                            <div class="col-12">
                                                                                <label for="status_pinjaman" class="col-form-label" style="margin-bottom: -100px; margin-top: 25px">Status Pinjaman</label>
                                                                                <select name="status_pinjaman" id="" class="mt-2 form-control @error('status_pinjaman') is-invalid @enderror">    
                                                                                    <option value="Menunggu Persetujuan" <?php if($item->status_pinjaman == "Menunggu Persetujuan") echo 'selected' ?>>
                                                                                        Menunggu Persetujuan
                                                                                    </option>
                                                                                    <option value="Disetujui" <?php if($item->status_pinjaman == "Disetujui") echo 'selected' ?>>
                                                                                        Disetujui
                                                                                    </option>
                                                                                    <option value="Ditolak" <?php if($item->status_pinjaman == "Ditolak") echo 'selected' ?>>
                                                                                        Ditolak
                                                                                    </option>
                                                                                    <option value="Selesai" <?php if($item->status_pinjaman == "Selesai") echo 'selected' ?>>
                                                                                        Selesai
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-info float-right" data-dismiss="modal">Tutup</button>
                                                                    <button type="submit" class="btn btn-success float-right">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->

                                                <!-- Modal Hapus -->
                                                <div class="modal fade" id="modal-hapus{{$item->id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger">
                                                                <h4 class="modal-title">Hapus Data Peminjaman Barang</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                            <div class="row justify-content-center text-sm">
                                                                <div class="col-4 col-lg-3">
                                                                    Nama Barang<br>
                                                                    Nama Peminjam<br>
                                                                    Tanggal Pinjam<br>
                                                                    Tanggal Kembali<br>
                                                                    Keperluan<br>

                                                                </div>
                                                                <div class="col-8 col-lg-8">
                                                                    : {{$item->barang->nama_barang}}<br>
                                                                    : {{$item->user->nama_lengkap}}<br>
                                                                    : {{date('d M Y', strtotime($item->tanggal_pinjam))}}<br>
                                                                    : {{date('d M Y', strtotime($item->tanggal_kembali))}}<br>
                                                                    : {{$item->keterangan}}<br>

                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                                <form action="{{url('/data-peminjaman/barang/' . $item->id)}}" method="post" class="">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-danger btn-sm mr-2" data-toggle="modal" data-target="#modal-hapus">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                            @endforeach
                                        @else
                                            @foreach ($dataPinjamBarangUser as $item)
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

                                                    <td class="text-center align-middle">
                                                        @if ($item->status_pinjaman == "Disetujui")
                                                            
                                                        @elseif($item->status_pinjaman == "Menunggu Persetujuan")
                                                            @if (Auth::user()->role_id == 1)
                                                                <a href="{{url('/data-peminjaman/barang/edit/' . $item->id)}}"><button class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i></button></a>
                                                            @endif
                                                            
                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus{{$item->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        @elseif($item->status_pinjaman == "Selesai")

                                                        @else
                                                            <a href="{{url('/data-peminjaman/barang/edit/' . $item->id)}}"><button class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i></button></a>

                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus{{$item->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <!-- Modal Hapus -->
                                                <div class="modal fade" id="modal-hapus{{$item->id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger">
                                                                <h4 class="modal-title">Hapus Data Peminjaman Barang</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                            <div class="row justify-content-center text-sm">
                                                                <div class="col-4 col-lg-3">
                                                                    Nama Barang<br>
                                                                    Nama Peminjam<br>
                                                                    Tanggal Pinjam<br>
                                                                    Tanggal Kembali<br>
                                                                    Keperluan<br>

                                                                </div>
                                                                <div class="col-8 col-lg-8">
                                                                    : {{$item->barang->nama_barang}}<br>
                                                                    : {{$item->user->nama_lengkap}}<br>
                                                                    : {{date('d M Y', strtotime($item->tanggal_pinjam))}}<br>
                                                                    : {{date('d M Y', strtotime($item->tanggal_kembali))}}<br>
                                                                    : {{$item->keterangan}}<br>

                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                                <form action="{{url('/data-peminjaman/barang/' . $item->id)}}" method="post" class="">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-danger btn-sm mr-2" data-toggle="modal" data-target="#modal-hapus">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <h3 class="card-title">Data Peminjaman Fasilitas Desa</h3>
                                <a href="{{url('/data-peminjaman/fasilitas/tambah')}}">
                                    <button class="btn btn-sm btn-success float-right shadow">Tambah</button>
                                </a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example3" class="table table-bordered table-striped table-sm text-sm nowrap">
                                    <thead class="bg-info">
                                        <tr>
                                            <th class="text-center align-middle">Tanggal Ajuan Peminjaman</th>
                                            <th class="text-center align-middle">Nama Fasilitas Dipinjam</th>
                                            <th class="text-center align-middle">Nama Peminjam</th>
                                            <th class="text-center align-middle">Jumlah Yang Dipinjam</th>
                                            <th class="text-center align-middle">Tanggal Peminjaman</th>
                                            <th class="text-center align-middle">Tanggal Pengambalian</th>
                                            <th class="text-center align-middle">Keperluan Pinjaman</th>
                                            <th class="text-center align-middle">Status Pinjaman</th>
                                            <th class="text-center align-middle">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (Auth::user()->role_id == 1)
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
                                                    @elseif($item->status_pinjaman == "Selesai")
                                                        <td class="text-center align-middle">
                                                            <div class="btn btn-sm btn-success text-white" data-toggle="modal" data-target="#modal-approve-fasilitas{{$item->id}}"><b>{{$item->status_pinjaman}}</b></div>
                                                        </td>
                                                    @else
                                                        <td class="text-center align-middle"><div class="btn btn-sm text-danger" data-toggle="modal" data-target="#modal-approve-fasilitas{{$item->id}}"><b>{{$item->status_pinjaman}}</b></div></td>
                                                    @endif

                                                    <td class="text-center align-middle">
                                                        @if (($item->status_pinjaman == "Disetujui") || ($item->status_pinjaman == "Selesai"))
                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus{{$item->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        @elseif($item->status_pinjaman == "Menunggu Persetujuan")
                                                            @if (Auth::user()->role_id == 1)
                                                                <a href="{{url('/data-peminjaman/fasilitas/edit/' . $item->id)}}"><button class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i></button></a>
                                                            @endif
                                                            
                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus{{$item->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        @else
                                                            <a href="{{url('/data-peminjaman/fasilitas/edit/' . $item->id)}}"><button class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i></button></a>

                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus{{$item->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <!-- Modal Approve Akun -->
                                                <div class="modal fade" id="modal-approve-fasilitas{{$item->id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{url('/data-peminjaman/fasilitas/approve/' . $item->id)}}" method="post">
                                                                @csrf
                                                                @method('put')

                                                                <div class="modal-header bg-success text-center">
                                                                    <h4 class="modal-title">Persetujuan Peminjaman Fasilitas</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p class="text-center">
                                                                        <div class="row justify-content-center text-sm">
                                                                            <div class="col-4 col-lg-3">
                                                                                Nama Barang<br>
                                                                                Nama Peminjam<br>
                                                                                Tanggal Pinjam<br>
                                                                                Tanggal Kembali<br>
                                                                                Keperluan<br>

                                                                            </div>
                                                                            <div class="col-8 col-lg-8">
                                                                                : {{$item->fasilitas->nama_fasilitas}}<br>
                                                                                : {{$item->user->nama_lengkap}}<br>
                                                                                : {{date('d M Y', strtotime($item->tanggal_pinjam))}}<br>
                                                                                : {{date('d M Y', strtotime($item->tanggal_kembali))}}<br>
                                                                                : {{$item->keterangan}}<br>

                                                                            </div>
                                                                        </div>

                                                                        <div class="row text-center">
                                                                            <div class="col-12">
                                                                                <label for="status_pinjaman" class="col-form-label" style="margin-bottom: -100px; margin-top: 25px">Status Peminjaman</label>
                                                                                <select name="status_pinjaman" id="" class="mt-2 form-control @error('status_pinjaman') is-invalid @enderror">    
                                                                                    <option value="Menunggu Persetujuan" <?php if($item->status_pinjaman == "Menunggu Persetujuan") echo 'selected' ?>>
                                                                                        Menunggu Persetujuan
                                                                                    </option>
                                                                                    <option value="Disetujui" <?php if($item->status_pinjaman == "Disetujui") echo 'selected' ?>>
                                                                                        Disetujui
                                                                                    </option>
                                                                                    <option value="Ditolak" <?php if($item->status_pinjaman == "Ditolak") echo 'selected' ?>>
                                                                                        Ditolak
                                                                                    </option>
                                                                                    <option value="Selesai" <?php if($item->status_pinjaman == "Selesai") echo 'selected' ?>>
                                                                                        Selesai
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-info float-right" data-dismiss="modal">Tutup</button>
                                                                    <button type="submit" class="btn btn-success float-right">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->

                                                <!-- Modal Hapus -->
                                                <div class="modal fade" id="modal-hapus{{$item->id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger">
                                                                <h4 class="modal-title">Hapus Data Peminjaman Fasilitas</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                            <div class="row justify-content-center text-sm">
                                                                <div class="col-4 col-lg-3">
                                                                    Nama Fasilitas<br>
                                                                    Nama Peminjam<br>
                                                                    Tanggal Pinjam<br>
                                                                    Tanggal Kembali<br>
                                                                    Keperluan<br>

                                                                </div>
                                                                <div class="col-8 col-lg-8">
                                                                    : {{$item->fasilitas->nama_fasilitas}}<br>
                                                                    : {{$item->user->nama_lengkap}}<br>
                                                                    : {{date('d M Y', strtotime($item->tanggal_pinjam))}}<br>
                                                                    : {{date('d M Y', strtotime($item->tanggal_kembali))}}<br>
                                                                    : {{$item->keterangan}}<br>

                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                                <form action="{{url('/data-peminjaman/fasilitas/' . $item->id)}}" method="post" class="">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-danger btn-sm mr-2" data-toggle="modal" data-target="#modal-hapus">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                            @endforeach
                                        @else
                                            @foreach ($dataPinjamFasilitasUser as $item)
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

                                                    <td class="text-center align-middle">
                                                        @if ($item->status_pinjaman == "Disetujui")
                                                            
                                                        @elseif($item->status_pinjaman == "Menunggu Persetujuan")
                                                            @if (Auth::user()->role_id == 1)
                                                                <a href="{{url('/data-peminjaman/fasilitas/edit/' . $item->id)}}"><button class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i></button></a>
                                                            @endif
                                                            
                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus{{$item->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        @elseif($item->status_pinjaman == "Selesai")

                                                        @else
                                                            <a href="{{url('/data-peminjaman/fasilitas/edit/' . $item->id)}}"><button class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i></button></a>

                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus{{$item->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <!-- Modal Hapus -->
                                                <div class="modal fade" id="modal-hapus{{$item->id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger">
                                                                <h4 class="modal-title">Hapus Data Peminjaman Fasilitas</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                            <div class="row justify-content-center text-sm">
                                                                <div class="col-4 col-lg-3">
                                                                    Nama Fasilitas<br>
                                                                    Nama Peminjam<br>
                                                                    Tanggal Pinjam<br>
                                                                    Tanggal Kembali<br>
                                                                    Keperluan<br>

                                                                </div>
                                                                <div class="col-8 col-lg-8">
                                                                    : {{$item->fasilitas->nama_fasilitas}}<br>
                                                                    : {{$item->user->nama_lengkap}}<br>
                                                                    : {{date('d M Y', strtotime($item->tanggal_pinjam))}}<br>
                                                                    : {{date('d M Y', strtotime($item->tanggal_kembali))}}<br>
                                                                    : {{$item->keterangan}}<br>

                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                                <form action="{{url('/data-peminjaman/fasilitas/' . $item->id)}}" method="post" class="">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-danger btn-sm mr-2" data-toggle="modal" data-target="#modal-hapus">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection