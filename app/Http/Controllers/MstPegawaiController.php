<?php

namespace App\Http\Controllers;

use App\Models\MstPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as FacadesFile;
use Throwable;

class MstPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $p = MstPegawai::paginate();

        $pegawai = DB::table('mst_pegawais')
            ->join('mst_jabatans', 'mst_pegawais.mst_jabatan_id', '=', 'mst_jabatans.id')
            ->select('mst_pegawais.id', 'mst_pegawais.nama', 'mst_pegawais.alamat', 'mst_jabatans.nama_jabatan')
            ->where('mst_pegawais.id', 'LIKE', '%' . $search . '%')
            ->orwhere('mst_pegawais.nama', 'LIKE', '%' . $search . '%')
            ->orWhere('mst_pegawais.alamat', 'LIKE', '%' . $search . '%')
            ->paginate($p->perPage());


        return view('mst-pegawai.index', compact('pegawai'))->with('i', (request()->input('page', 1) - 1) * $p->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jabatan = DB::table('mst_jabatans')->pluck('nama_jabatan', 'id');
        $pegawai = new MstPegawai();
        return view('mst-pegawai.create', compact('pegawai', 'jabatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(MstPegawai::$rules);
        DB::beginTransaction();
        try {
            //menjalankan query builder untuk menyimpan ke tabel pegawai
            $file = $request->file('file_foto');
            $ext = $file->getClientOriginalExtension();
            $fileFoto = $request->id . "." . $ext;
            //menyimpan ke folder /file
            $request->file('file_foto')->move("foto/", $fileFoto);
            DB::table('mst_pegawais')->insert([
                'id' => $request->id,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'jenis_kel' => $request->jenis_kel,
                'agama' => $request->agama,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'file_foto' => $fileFoto,
                'mst_jabatan_id' => $request->mst_jabatan_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            //jika tidak ada kesalahan commit/simpan
            DB::commit();
            // mengembalikan tampilan ke view index (halaman sebelumnya)
            return redirect()->route('mst-pegawai.index')
                ->with('success', 'Pegawai telah berhasil disimpan.');
        } catch (Throwable $e) {
            //jika terjadi kesalahan batalkan semua
            DB::rollback();
            return redirect()->route('mst-pegawai.index')
                ->with('success', 'Penyimpanan dibatalkan semua, ada kesalahan...');
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
        $pegawai = MstPegawai::find($id);
        //menampikan ke view show
        return view('mst-pegawai.show', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pegawai = MstPegawai::find($id);
        $jabatan = DB::table('mst_jabatans')->pluck('nama_jabatan', 'id');
        //menampikan 1 rekaman ke view edit
        return view('mst-pegawai.edit', compact('pegawai', 'jabatan'));
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
        request()->validate(MstPegawai::$rules);
        //mulai transaksi
        DB::beginTransaction();
        try {
            $pegawai = MstPegawai::find($id);
            if ($request->hasFile('file_foto')) {
                $image_path = "foto/" . $pegawai->file_foto;
                if (FacadesFile::exists($image_path)) {
                    FacadesFile::delete($image_path);
                }
                $file = $request->file('file_foto');
                $ext = $file->getClientOriginalExtension();
                $fileFoto = $id . "." . $ext;
                $destinationPath = 'foto/';
                $file->move($destinationPath, $fileFoto);
            } else {
                $fileFoto = $pegawai->file_foto;
            }
            DB::table('mst_pegawais')->where('id', $id)->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'jenis_kel' => $request->jenis_kel,
                'agama' => $request->agama,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'file_foto' => $fileFoto,
                'mst_jabatan_id' => $request->mst_jabatan_id,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            DB::commit();
            //mengembalikan tampilan ke view index (halaman sebelumnya)
            return redirect()->route('mst-pegawai.index')
                ->with('success', 'Pegawai berhasil diubah');
        } catch (Throwable $e) {
            //jika terjadi kesalahan batalkan semua
            return redirect()->route('mst-pegawai.index')
                ->with('success', 'Pegawai batal diubah, ada kesalahan');
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
            //menghapus 1 rekaman tabel pegawai
            MstPegawai::find($id)->delete();
            DB::commit();
            // mengembalikan tampilan ke view index (halaman sebelumnya)
            return redirect()->route('mst-pegawai.index')
                ->with('success', 'Pegawai berhasil dihapus');
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->route('mst-pegawai.index')
                ->with('success', 'Pegawai ada kesalahan hapus batal... ');
        }
    }
}
