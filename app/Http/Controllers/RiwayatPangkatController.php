<?php

namespace App\Http\Controllers;

use App\Models\MstPegawai;
use App\Models\RiwayatPangkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatPangkatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $p = MstPegawai::paginate(); //mangatur tampil perhalaman
        //menjalankan query builder 
        $pegawai = DB::table('mst_pegawais')
            ->join('mst_jabatans', 'mst_pegawais.mst_jabatan_id', '=', 'mst_jabatans.id')
            ->select(
                'mst_pegawais.id',
                'mst_pegawais.nama',
                'mst_pegawais.alamat',
                'mst_jabatans.nama_jabatan'
            )
            ->where('mst_pegawais.id', 'LIKE', '%' . $search . '%')
            ->orwhere('mst_pegawais.nama', 'LIKE', '%' . $search . '%')
            ->orWhere('mst_pegawais.alamat', 'LIKE', '%' . $search . '%')
            ->paginate($p->perPage());
        //memanggil view dan menyertakan hasil quey 
        return view('riwayat-pangkat.index', compact('pegawai'))
            ->with('i', (request()->input('page', 1) - 1) * $p->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $pangkat = DB::table('mst_pangkats')->pluck(
            DB::raw("CONCAT(pangkat_gol,' ',nama_pangkat) as nama_pangkat"),
            'id'
        );
        $rw = new RiwayatPangkat();
        //membaca identitas pegawai
        $peg = MstPegawai::find($id);
        return view('riwayat-pangkat.create', compact('rw', 'pangkat', 'peg'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rwp = new RiwayatPangkat;
        $rwp->pegawai_id
            = $request->pegawai_id;
        $rwp->mst_pangkat_id = $request->mst_pangkat_id;
        $rwp->no_sk_pangkat = $request->no_sk_pangkat;
        $rwp->tanggal_tmt_pangkat = $request->tanggal_tmt_pangkat;
        $rwp->gaji_pokok
            = $request->gaji_pokok;
        $rwp->status
            = $request->status;
        $rwp->save(); //simpan
        //baca lagi setelah penambahan
        $rw = RiwayatPangkat::where('pegawai_id', $request->pegawai_id)->get();
        $peg = MstPegawai::find($request->pegawai_id);
        //tampilkan kembalai ke index1
        return view('riwayat-pangkat.proses', compact('rw', 'peg'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rw = RiwayatPangkat::find($id);
        //baca pegawai_id tabel riwayat_pangkat 
        $peg_id = RiwayatPangkat::where('id', $id)->first();
        $peg = MstPegawai::find($peg_id->pegawai_id);
        //baca tabel mst_pangkat untuk pilihan dropdown list
        $pangkat = DB::table('mst_pangkats')->pluck(
            DB::raw("CONCAT(pangkat_gol,' ',nama_pangkat) as nama_pangkat"),
            'id'
        );
        //menampikan 1 rekaman ke view edit
        return view('riwayat-pangkat.edit', compact('rw', 'peg', 'pangkat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rwp = RiwayatPangkat::find($id);
        $rwp->pegawai_id
            = $request->pegawai_id;
        $rwp->mst_pangkat_id = $request->mst_pangkat_id;
        $rwp->no_sk_pangkat = $request->no_sk_pangkat;
        $rwp->tanggal_tmt_pangkat = $request->tanggal_tmt_pangkat;
        $rwp->gaji_pokok
            = $request->gaji_pokok;
        $rwp->status
            = $request->status;
        $rwp->save(); //sinpan
        //baca lagi riwayat_pangkat setelah diubah
        $rw = RiwayatPangkat::where('pegawai_id', $request->pegawai_id)->get();
        //baca identitas pegawai
        $peg = MstPegawai::find($request->pegawai_id);
        //kembilkan tampilkan ke daftar riwayat pangkat 
        return view('riwayat-pangkat.index1', compact('rw', 'peg'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $peg_id = RiwayatPangkat::find($id)->pegawai_id;
        //menghapus 1 rekaman tabel pegawai
        $rwy = RiwayatPangkat::find($id)->delete();
        // mengembalikan tampilan ke view index (halaman sebelumnya)
        $rw = RiwayatPangkat::where('pegawai_id', $peg_id)->get();
        $peg = MstPegawai::find($peg_id);
        return view('riwayat-pangkat.proses', compact('rw', 'peg'));
    }

    public function proses($id)
    {
        //baca kepangkatan perpegawai
        $rw = RiwayatPangkat::where('pegawai_id', $id)->get();
        //baca identitas pegawai
        $peg = MstPegawai::find($id);

        // dd($rw);

        //tampilkan riwayat pangkat pegawai tertentu
        return view('riwayat-pangkat.proses', compact('rw', 'peg'));
    }

    public function cetak($id)
    {
        //baca kepangkatan perpegawai
        $rw = RiwayatPangkat::where('pegawai_id', $id)->get(); //baca identitas pegawai
        $peg = MstPegawai::find($id);
        //tampilkan riwayat pangkat pegawai tertentu
        return view('riwayat-pangkat.cetak', compact('rw', 'peg'));
    }
}
