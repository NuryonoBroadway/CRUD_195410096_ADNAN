<?php

namespace App\Http\Controllers;

use App\Models\MstJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MstJabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = request()->get('search');
        $p = MstJabatan::paginate();
        $mstJabatan = DB::table('mst_jabatans')
            ->where('nama_jabatan', 'LIKE', '%' . $search . '%')->paginate($p->perPage());

        // dd($mstJabatan);
        return view('mst-jabatan.index', compact('mstJabatan'))
            ->with('i', (request()->input('page', 1) - 1) * $p->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mstJabatan = new MstJabatan();
        return view('mst-jabatan.create', compact('mstJabatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //cek validasi
        request()->validate(MstJabatan::$rules);
        //mulai transaksi

        DB::beginTransaction();
        try {
            //simpan ke tabel kota
            DB::table('mst_jabatans')->insert([
                'nama_jabatan' => $request->nama_jabatan, 'tunjangan' => $request->tunjangan, 'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => date('Y-m-d H:m:s')
            ]);
            //jika tidak ada kesalahan commit/simpan
            DB::commit();
            return redirect()->route('mst-jabatan.index')
                ->with('success', 'Master Tabel Jabatan created successfully.');
        } catch (\Throwable $e) {
            //jika terjadi kesalahan batalkan semua
            DB::rollback();
            return redirect()->route('mst-jabatan.index')->with(
                'success',
                'Penyimpanan dibatalkan semua, ada kesalahan......'
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mstJabatan = MstJabatan::find($id);
        return view('mst-jabatan.show', compact('mstJabatan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mstJabatan = MstJabatan::find($id);
        return view('mst-jabatan.edit', compact('mstJabatan'));
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
        request()->validate(MstJabatan::$rules);
        //mulai transaksi
        DB::beginTransaction();
        try {
            DB::table('mst_jabatans')->where('id', $id)->update([
                'nama_jabatan' => $request->nama_jabatan,
                'tunjangan' => $request->tunjangan, 'updated_at' => date('Y-m-d H:m:s')
            ]);
            DB::commit();
            return redirect()->route('mst-jabatan.index')
                ->with('success', 'Tabel Jabatan updated successfully');
        } catch (\Throwable $e) {
            //jika terjadi kesalahan batalkan semua
            DB::rollback();
            return redirect()->route('mst-jabatan.index')->with(
                'success',
                'Tabel Jabatan Batal diubah, ada kesalahan'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            //hapus rekaman tabel kota
            MstJabatan::find($id)->delete();
            DB::commit();
            return redirect()->route('mst-jabatan.index')
                ->with('success', 'Tabel Jabatan deleted successfully');
        } catch (\Throwable $e) {
            //jika terjadi kesalahan batalkan semua
            DB::rollback();
            return redirect()->route('mst-jabatan.index')
                ->with(
                    'success',
                    'Tabel Jabatan ada kesalahan hapus batal... '
                );
        }
    }
}
