@extends('layouts.app')

@section('title')
    Pengguna
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
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
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
                        <h1>Pendaftar</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active"><i class="fas fa-users"></i> Pendaftar</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <h3 class="card-title">Data Pengguna/user Yang Terdaftar</h3>
                                <a href="{{url('/pengguna/tambah')}}">
                                    <button class="btn btn-sm btn-success float-right shadow">Tambah</button>
                                </a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm text-sm nowrap">
                                    <thead class="bg-info">
                                        <tr>
                                            <th class="text-center align-middle">Foto</th>
                                            <th class="text-center align-middle">Nama Lengkap</th>
                                            <th class="text-center align-middle">NIK</th>
                                            <th class="text-center align-middle">Status Akun</th>
                                            <th class="text-center align-middle">Jenis Kelamin</th>
                                            <th class="text-center align-middle">Tempat, Tgl Lahir</th>
                                            <th class="text-center align-middle">Nomor HP</th>
                                            <th class="text-center align-middle">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <img src="{{asset('assets/profil/foto/' . $item->biodata->foto)}}" alt="Foto Profil" width="125px">
                                                </td>

                                                <td class="text-center align-middle">{{$item->nama_lengkap}}</td>
                                                <td class="text-center align-middle">{{$item->biodata->nik}}</td>

                                                @if ($item->biodata->status_verifikasi == 0)
                                                    <td class="text-center align-middle">
                                                        <div class="btn btn-sm text-danger" data-toggle="modal" data-target="#modal-verifikasi{{$item->id}}">Belum Terverifikasi</div>
                                                    </td>
                                                @else
                                                    <td class="text-center align-middle">
                                                        <div class="btn btn-sm text-success" data-toggle="modal" data-target="#modal-verifikasi{{$item->id}}">Terverifikasi</div>
                                                    </td>
                                                @endif

                                                <td class="text-center align-middle">{{$item->biodata->jenis_kelamin}}</td>

                                                @if (($item->biodata->tempat_lahir == null) || ($item->biodata->tanggal_lahir == null))
                                                    <td class="text-center align-middle text-danger">Belum Update</td>
                                                @else
                                                    <td class="text-center align-middle">{{$item->biodata->tempat_lahir}}, {{date('d M Y', strtotime($item->biodata->tanggal_lahir))}}</td>
                                                @endif
                                                                                                
                                                <td class="text-center align-middle">{{$item->biodata->no_hp}}</td>


                                                <td class="text-center align-middle">
                                                    <a href="{{url('/profile/' . $item->id)}}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>

                                                    <a href="{{url('/pengguna/edit/' . $item->id)}}"><button class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i></button></a>

                                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus{{$item->id}}"><i class="fas fa-trash-alt"></i></button>
                                                </td>
                                            </tr>

                                            <!-- Modal Verifikasi Akun -->
                                            <div class="modal fade" id="modal-verifikasi{{$item->id}}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{url('/pengguna/verifikasi/' . $item->id)}}" method="post">
                                                            @csrf
                                                            @method('put')
                                                            <div class="modal-header bg-success text-center">
                                                                <h4 class="modal-title">Verifikasi Pengguna</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="text-center">
                                                                    <div class="row justify-content-center text-sm">
                                                                        <div class="col-4 col-lg-3">
                                                                            Nama Lengkap<br>
                                                                            NIK<br>
                                                                            Jenis Kelamin<br>
                                                                            Nomor HP<br>

                                                                        </div>
                                                                        <div class="col-8 col-lg-8">
                                                                            : {{$item->nama_lengkap}}<br>
                                                                            : {{$item->biodata->nik}}<br>
                                                                            : {{$item->biodata->jenis_kelamin}}<br>
                                                                            : {{$item->biodata->no_hp}}<br>

                                                                        </div>
                                                                    </div>

                                                                    <div class="row text-center">
                                                                        <div class="col-12">
                                                                            <label for="status_verifikasi" class="col-form-label" style="margin-bottom: -100px; margin-top: 25px">Status Verifikasi</label>
                                                                            <select name="status_verifikasi" id="" class="mt-2 form-control @error('status_verifikasi') is-invalid @enderror">    
                                                                                <option value="1" <?php if($item->biodata->status_verifikasi == 1) echo 'selected' ?>>
                                                                                    Terverifikasi
                                                                                </option>
                                                                                <option value="0" <?php if($item->biodata->status_verifikasi == 0) echo 'selected' ?>>
                                                                                    Belum Verifikasi
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
                                                            <h4 class="modal-title">Hapus Akun Pendaftar</h4>
                                                            <!-- <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button> -->
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row justify-content-center text-sm">
                                                                <div class="col-4 col-lg-3">
                                                                    Nama Lengkap<br>
                                                                    NIK<br>
                                                                    Jenis Kelamin<br>
                                                                    Nomor HP<br>

                                                                </div>
                                                                <div class="col-8 col-lg-8">
                                                                    : {{$item->nama_lengkap}}<br>
                                                                    : {{$item->biodata->nik}}<br>
                                                                    : {{$item->biodata->jenis_kelamin}}<br>
                                                                    : {{$item->biodata->no_hp}}<br>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                            <form action="{{url('/pengguna/' . $item->id)}}" method="post" class="">
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