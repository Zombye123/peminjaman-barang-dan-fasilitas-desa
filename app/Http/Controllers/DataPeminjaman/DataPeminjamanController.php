<?php

namespace App\Http\Controllers\DataPeminjaman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Alert;
use App\Models\User;
use App\Models\DataBarang;
use App\Models\DataFasilitas;
use App\Models\DataPeminjamanBarang;
use App\Models\DataPeminjamanFasilitas;

class DataPeminjamanController extends Controller
{
    public function index()
    {
        // Admin
        $dataPinjamBarang = DataPeminjamanBarang::orderBy('created_at', 'desc')->get();
        $dataPinjamFasilitas = DataPeminjamanFasilitas::orderBy('created_at', 'desc')->get();

        // User
        $dataPinjamBarangUser = DataPeminjamanBarang::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $dataPinjamFasilitasUser = DataPeminjamanFasilitas::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        if((Auth::user()->role_id == 1))
        {
            return view('data-peminjaman.index', compact('dataPinjamBarang', 'dataPinjamFasilitas'));
        }
        elseif(((Auth::user()->biodata->tempat_lahir != null) && (Auth::user()->biodata->tanggal_lahir != null) && (Auth::user()->biodata->alamat != null)))
        {
            return view('data-peminjaman.index', compact('dataPinjamBarangUser', 'dataPinjamFasilitasUser'));
        }
        else
        {
            abort(403);
        }
    }

    public function createPinjamanBarang()
    {
        $dataBarang = DataBarang::orderBy('nama_barang', 'asc')->get();
        $peminjams = User::where('role_id', 2)->get();

        if((Auth::user()->role_id == 1))
        {
            return view('data-peminjaman.barang.create', compact('dataBarang', 'peminjams'));
        }
        elseif(((Auth::user()->biodata->tempat_lahir != null) && (Auth::user()->biodata->tanggal_lahir != null) && (Auth::user()->biodata->alamat != null)))
        {
            return view('data-peminjaman.barang.create', compact('dataBarang', 'peminjams'));
        }
        else
        {
            abort(403);
        }
    }

    public function getbarang(Request $request)
    {
        $id_data_barang = $request->id_data_barang;

        $barang = DataBarang::where('id', $id_data_barang)->first();
        $tersedia = $barang->jumlah_barang;
        $satuan = $barang->satuan_barang;

        echo "Tersedia : " . "<b>" . $tersedia . " " . $satuan . "</b>";
    }

    public function storePinjamanBarang(Request $request)
    {
        $id_barang  = $request->data_barang_id;
        $barang = DataBarang::where('id', $id_barang)->first();
        $tersedia = $barang->jumlah_barang;

        $request->validate([
            'data_barang_id' => ['required'],
            'user_id' => ['required'],
            'jumlah_pinjaman' => ['required', 'numeric', 'min:1', 'max:'.$tersedia],
            'tanggal_pinjam' => ['required'],
            'tanggal_kembali' => ['required'],
            'keterangan' => ['required'],
        ]);

        DataPeminjamanBarang::create([
            'data_barang_id' => $request->data_barang_id,
            'user_id' => $request->user_id,
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'keterangan' => $request->keterangan,
        ]);

        alert()->success('Berhasil', 'Tambah Data Peminjaman Barang ' . $barang->nama_barang);
        return redirect('/data-peminjaman');
    }

    public function editBarang($id)
    {
        $dataPinjaman = DataPeminjamanBarang::find($id);
        $user = User::where('id', $dataPinjaman->user_id)->first();
        $dataBarang = DataBarang::orderBy('nama_barang', 'asc')->get();
        $peminjams = User::where('role_id', 2)->get();

        if((Auth::user()->role_id == 1) && ($dataPinjaman->status_pinjaman != "Selesai") && ($dataPinjaman->status_pinjaman != "Disetujui"))
        {
            return view('data-peminjaman.barang.edit', compact('dataBarang', 'peminjams', 'dataPinjaman'));
        }
        elseif((Auth::user()->id == $user->id) && ((Auth::user()->biodata->tempat_lahir != null) && (Auth::user()->biodata->tanggal_lahir != null) && (Auth::user()->biodata->alamat != null)) && ($dataPinjaman->status_pinjaman != "Selesai") && ($dataPinjaman->status_pinjaman != "Disetujui"))
        {
            return view('data-peminjaman.barang.edit', compact('dataBarang', 'peminjams', 'dataPinjaman'));
        }
        else
        {
            abort(403);
        }
    }

    public function updateBarang(Request $request, $id)
    {
        $id_barang  = $request->data_barang_id;
        $barang = DataBarang::where('id', $id_barang)->first();
        $tersedia = $barang->jumlah_barang;

        $request->validate([
            'data_barang_id' => ['required'],
            'user_id' => ['required'],
            'jumlah_pinjaman' => ['required', 'numeric', 'min:1', 'max:'.$tersedia],
            'tanggal_pinjam' => ['required'],
            'tanggal_kembali' => ['required'],
            'keterangan' => ['required'],
        ]);

        DataPeminjamanBarang::find($id)->update([
            'data_barang_id' => $request->data_barang_id,
            'user_id' => $request->user_id,
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'keterangan' => $request->keterangan,
            'status_pinjaman' => "Menunggu Persetujuan",
        ]);

        alert()->success('Berhasil', 'Update Data Peminjaman Barang ' . $barang->nama_barang);
        return redirect('/data-peminjaman');
    }

    public function approveBarang(Request $request, $id)
    {
        $dataPinjaman = DataPeminjamanBarang::find($id);
        $barang = DataBarang::where('id', $dataPinjaman->data_barang_id)->first();
        $nama_barang = $barang->nama_barang;

        if($dataPinjaman->status_pinjaman == "Menunggu Persetujuan"){
            if($request->status_pinjaman == "Disetujui"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang - $jumlahPinjam;
            }
            elseif($request->status_pinjaman == "Ditolak"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang;
            }
            elseif($request->status_pinjaman == "Selesai"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang;
            }
            else{
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang;
            }
        }
        elseif($dataPinjaman->status_pinjaman == "Disetujui"){
            if($request->status_pinjaman == "Disetujui"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang;
            }
            elseif($request->status_pinjaman == "Ditolak"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang + $jumlahPinjam;
            }
            elseif($request->status_pinjaman == "Selesai"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang + $jumlahPinjam;
            }
            else{
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang + $jumlahPinjam;
            }
        }
        elseif($dataPinjaman->status_pinjaman == "Selesai"){
            if($request->status_pinjaman == "Disetujui"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang - $jumlahPinjam;
            }
            elseif($request->status_pinjaman == "Ditolak"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang;
            }
            elseif($request->status_pinjaman == "Selesai"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang;
            }
            else{
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang;
            }
        }
        else{
            if($request->status_pinjaman == "Disetujui"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang - $jumlahPinjam;
            }
            elseif($request->status_pinjaman == "Ditolak"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang;
            }
            elseif($request->status_pinjaman == "Selesai"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang;
            }
            else{
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaBarangPinjaman = $barang->jumlah_barang;
            }
        }

        if($sisaBarangPinjaman >= 0){
            $sisaBarang = $sisaBarangPinjaman;
            $status = $request->status_pinjaman;
        }else{
            $sisaBarang = $barang->jumlah_barang;
            $status = $dataPinjaman->status_pinjaman;
        }

        DataPeminjamanBarang::find($id)->update([
            'data_barang_id' => \DB::raw('data_barang_id'),
            'user_id' => \DB::raw('user_id'),
            'jumlah_pinjaman' => \DB::raw('jumlah_pinjaman'),
            'tanggal_pinjam' => \DB::raw('tanggal_pinjam'),
            'tanggal_kembali' => \DB::raw('tanggal_kembali'),
            'keterangan' => \DB::raw('keterangan'),
            'status_pinjaman' => $status,
        ]);

        DataBarang::where('id', $dataPinjaman->data_barang_id)->update([
            'kode_barang' => \DB::raw('kode_barang'),
            'nama_barang' => \DB::raw('nama_barang'),
            'satuan_barang' => \DB::raw('satuan_barang'),
            'jumlah_barang' => $sisaBarang,
        ]);

        if($sisaBarangPinjaman >= 0){
            alert()->success('Berhasil', 'Update Status Peminjaman Barang ' . $nama_barang);
        }else{
            alert()->error('Gagal Update', 'Barang tidak cukup/habis');
        }
        
        return redirect('/data-peminjaman');
    }

    public function destroyBarang($id)
    {
        $dataPinjaman = DataPeminjamanBarang::find($id);
        $barang = DataBarang::where('id', $dataPinjaman->data_barang_id)->first();

        if($dataPinjaman->status_pinjaman == "Menunggu Persetujuan"){
            $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
            $sisaBarang = $barang->jumlah_barang;
        }
        elseif($dataPinjaman->status_pinjaman == "Ditolak"){
            $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
            $sisaBarang = $barang->jumlah_barang;
        }
        elseif($dataPinjaman->status_pinjaman == "Selesai"){
            $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
            $sisaBarang = $barang->jumlah_barang;
        }
        else{
            $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
            $sisaBarang = $barang->jumlah_barang + $jumlahPinjam;
        }
        
        DataBarang::where('id', $dataPinjaman->data_barang_id)->update([
            'kode_barang' => \DB::raw('kode_barang'),
            'nama_barang' => \DB::raw('nama_barang'),
            'satuan_barang' => \DB::raw('satuan_barang'),
            'jumlah_barang' => $sisaBarang,
        ]);

        $dataPinjaman->delete();

        alert()->success('Berhasil', 'Hapus Data Peminjaman Barang');
        return back();
    }

    public function createPinjamanFasilitas()
    {
        $dataFasilitas = DataFasilitas::orderBy('nama_fasilitas', 'asc')->get();
        $peminjams = User::where('role_id', 2)->get();

        if((Auth::user()->role_id == 1))
        {
            return view('data-peminjaman.fasilitas.create', compact('dataFasilitas', 'peminjams'));
        }
        elseif((Auth::user()->biodata->tempat_lahir != null) && (Auth::user()->biodata->tanggal_lahir != null) && (Auth::user()->biodata->alamat != null))
        {
            return view('data-peminjaman.fasilitas.create', compact('dataFasilitas', 'peminjams'));
        }
        else
        {
            abort(403);
        }
    }

    public function getFasilitas(Request $request)
    {
        $id_data_fasilitas = $request->id_data_fasilitas;

        $fasilitas = DataFasilitas::where('id', $id_data_fasilitas)->first();
        $tersedia = $fasilitas->jumlah_fasilitas;
        $satuan = $fasilitas->satuan_fasilitas;

        echo "Tersedia : " . "<b>" . $tersedia . " " . $satuan . "</b>";
    }

    public function storePinjamanFasilitas(Request $request)
    {
        $id_fasilitas  = $request->data_fasilitas_id;
        $fasilitas = DataFasilitas::where('id', $id_fasilitas)->first();
        $tersedia = $fasilitas->jumlah_fasilitas;

        $request->validate([
            'data_fasilitas_id' => ['required'],
            'user_id' => ['required'],
            'jumlah_pinjaman' => ['required', 'numeric', 'min:1', 'max:'.$tersedia],
            'tanggal_pinjam' => ['required'],
            'tanggal_kembali' => ['required'],
            'keterangan' => ['required'],
        ]);

        DataPeminjamanFasilitas::create([
            'data_fasilitas_id' => $request->data_fasilitas_id,
            'user_id' => $request->user_id,
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'keterangan' => $request->keterangan,
        ]);

        alert()->success('Berhasil', 'Tambah Data Peminjaman Fasilitas ' . $fasilitas->nama_fasilitas);
        return redirect('/data-peminjaman');
    }

    public function approveFasilitas(Request $request, $id)
    {
        $dataPinjaman = DataPeminjamanFasilitas::find($id);
        $fasilitas = DataFasilitas::where('id', $dataPinjaman->data_fasilitas_id)->first();
        $nama_fasilitas = $fasilitas->nama_fasilitas;

        if($dataPinjaman->status_pinjaman == "Menunggu Persetujuan"){
            if($request->status_pinjaman == "Disetujui"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas - $jumlahPinjam;
            }
            elseif($request->status_pinjaman == "Ditolak"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas;
            }
            elseif($request->status_pinjaman == "Selesai"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas;
            }
            else{
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas;
            }
        }
        elseif($dataPinjaman->status_pinjaman == "Disetujui"){
            if($request->status_pinjaman == "Disetujui"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas;
            }
            elseif($request->status_pinjaman == "Ditolak"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas + $jumlahPinjam;
            }
            elseif($request->status_pinjaman == "Selesai"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas + $jumlahPinjam;
            }
            else{
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas + $jumlahPinjam;
            }
        }
        elseif($dataPinjaman->status_pinjaman == "Selesai"){
            if($request->status_pinjaman == "Disetujui"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas - $jumlahPinjam;
            }
            elseif($request->status_pinjaman == "Ditolak"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas;
            }
            elseif($request->status_pinjaman == "Selesai"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas;
            }
            else{
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas;
            }
        }
        else{
            if($request->status_pinjaman == "Disetujui"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas - $jumlahPinjam;
            }
            elseif($request->status_pinjaman == "Ditolak"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas;
            }
            elseif($request->status_pinjaman == "Selesai"){
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas;
            }
            else{
                $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
                $sisaFasilitasPinjaman = $fasilitas->jumlah_fasilitas;
            }
        }

        if($sisaFasilitasPinjaman >= 0){
            $sisaFasilitas = $sisaFasilitasPinjaman;
            $status = $request->status_pinjaman;
        }else{
            $sisaFasilitas = $fasilitas->jumlah_fasilitas;
            $status = $dataPinjaman->status_pinjaman;
        }

        DataPeminjamanFasilitas::find($id)->update([
            'data_fasilitas_id' => \DB::raw('data_fasilitas_id'),
            'user_id' => \DB::raw('user_id'),
            'jumlah_pinjaman' => \DB::raw('jumlah_pinjaman'),
            'tanggal_pinjam' => \DB::raw('tanggal_pinjam'),
            'tanggal_kembali' => \DB::raw('tanggal_kembali'),
            'keterangan' => \DB::raw('keterangan'),
            'status_pinjaman' => $status,
        ]);

        DataFasilitas::where('id', $dataPinjaman->data_fasilitas_id)->update([
            'kode_fasilitas' => \DB::raw('kode_fasilitas'),
            'nama_fasilitas' => \DB::raw('nama_fasilitas'),
            'satuan_fasilitas' => \DB::raw('satuan_fasilitas'),
            'jumlah_fasilitas' => $sisaFasilitas,
        ]);

        if($sisaFasilitasPinjaman >= 0){
            alert()->success('Berhasil', 'Update Status Peminjaman Fasilitas ' . $nama_fasilitas);
        }else{
            alert()->error('Gagal Update', 'Fasilitas tidak cukup/habis');
        }
        
        return redirect('/data-peminjaman');
    }

    public function editFasilitas($id)
    {
        $dataPinjaman = DataPeminjamanFasilitas::find($id);
        $user = User::where('id', $dataPinjaman->user_id)->first();
        $dataFasilitas = DataFasilitas::orderBy('nama_fasilitas', 'asc')->get();
        $peminjams = User::where('role_id', 2)->get();

        // dd($dataPinjaman->status_pinjaman);

        if((Auth::user()->role_id == 1) && ($dataPinjaman->status_pinjaman != "Selesai") && ($dataPinjaman->status_pinjaman != "Disetujui"))
        {
            return view('data-peminjaman.fasilitas.edit', compact('dataFasilitas', 'peminjams', 'dataPinjaman'));
        }
        elseif((Auth::user()->id == $user->id) && ((Auth::user()->biodata->tempat_lahir != null) && (Auth::user()->biodata->tanggal_lahir != null) && (Auth::user()->biodata->alamat != null)) && ($dataPinjaman->status_pinjaman != "Selesai") && ($dataPinjaman->status_pinjaman != "Disetujui"))
        {
            return view('data-peminjaman.fasilitas.edit', compact('dataFasilitas', 'peminjams', 'dataPinjaman'));
        }
        else
        {
            abort(403);
        }
    }

    public function updateFasilitas(Request $request, $id)
    {
        $id_fasilitas  = $request->data_fasilitas_id;
        $fasilitas = DataFasilitas::where('id', $id_fasilitas)->first();
        $tersedia = $fasilitas->jumlah_fasilitas;

        $request->validate([
            'data_fasilitas_id' => ['required'],
            'user_id' => ['required'],
            'jumlah_pinjaman' => ['required', 'numeric', 'min:1', 'max:'.$tersedia],
            'tanggal_pinjam' => ['required'],
            'tanggal_kembali' => ['required'],
            'keterangan' => ['required'],
        ]);

        DataPeminjamanFasilitas::find($id)->update([
            'data_fasilitas_id' => $request->data_fasilitas_id,
            'user_id' => $request->user_id,
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'keterangan' => $request->keterangan,
            'status_pinjaman' => "Menunggu Persetujuan",
        ]);

        alert()->success('Berhasil', 'Update Data Peminjaman Fasilitas ' . $fasilitas->nama_fasilitas);
        return redirect('/data-peminjaman');
    }

    public function destroyFasilitas($id)
    {
        $dataPinjaman = DataPeminjamanFasilitas::find($id);
        $fasilitas = DataFasilitas::where('id', $dataPinjaman->data_fasilitas_id)->first();

        if($dataPinjaman->status_pinjaman == "Menunggu Persetujuan"){
            $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
            $sisaFasilitas = $fasilitas->jumlah_fasilitas;
        }
        elseif($dataPinjaman->status_pinjaman == "Ditolak"){
            $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
            $sisaFasilitas = $fasilitas->jumlah_fasilitas;
        }
        elseif($dataPinjaman->status_pinjaman == "Selesai"){
            $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
            $sisaFasilitas = $fasilitas->jumlah_fasilitas;
        }
        else{
            $jumlahPinjam = $dataPinjaman->jumlah_pinjaman;
            $sisaFasilitas = $fasilitas->jumlah_fasilitas + $jumlahPinjam;
        }
        
        DataFasilitas::where('id', $dataPinjaman->data_fasilitas_id)->update([
            'kode_fasilitas' => \DB::raw('kode_fasilitas'),
            'nama_fasilitas' => \DB::raw('nama_fasilitas'),
            'satuan_fasilitas' => \DB::raw('satuan_fasilitas'),
            'jumlah_fasilitas' => $sisaFasilitas,
        ]);

        $dataPinjaman->delete();

        alert()->success('Berhasil', 'Hapus Data Peminjaman Fasilitas');
        return back();
    }
}
