<?php

namespace App\Http\Controllers;

use App\Models\RekamanUser;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Throwable;

class RekamanUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $p = RekamanUser::paginate();

        $users = DB::table('rekaman_users')
            ->join('mst_jabatans', 'rekaman_users.mst_jabatan_id', '=', 'mst_jabatans.id')
            ->select('rekaman_users.id', 'rekaman_users.nama', 'rekaman_users.email', 'rekaman_users.file_foto', 'mst_jabatans.nama_jabatan')
            ->paginate($p->perPage());


        return view('rekaman-user.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * $p->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jabatan = DB::table('mst_jabatans')->pluck('nama_jabatan', 'id');
        $users = new RekamanUser();
        $title = "Menambah User";
        $action = "tambah";
        return view('rekaman-user.create', compact('users', 'action', 'title', 'jabatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(RekamanUser::$rules);
        DB::beginTransaction();
        try {
            //menjalankan query builder untuk menyimpan ke tabel pegawai
            $file = $request->file('file_foto');
            $ext = $file->getClientOriginalExtension();
            $fileFoto = $request->id . "." . $ext;
            //menyimpan ke folder /file
            $request->file('file_foto')->move("foto/", $fileFoto);
            DB::table('rekaman_users')->insert([
                'nama' => $request->nama,
                'email' => $request->email,
                'file_foto' => $fileFoto,
                'mst_jabatan_id' => $request->mst_jabatan_id,
                'password' => Hash::make($request->password),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            //jika tidak ada kesalahan commit/simpan
            DB::commit();
            // mengembalikan tampilan ke view index (halaman sebelumnya)
            return redirect()->route('user.index')
                ->with('success', 'User telah berhasil disimpan.');
        } catch (Throwable $e) {
            //jika terjadi kesalahan batalkan semua
            DB::rollback();
            return redirect()->route('user.index')
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
        $users = RekamanUser::find($id);
        $jabatan = DB::table('mst_jabatans')->pluck('nama_jabatan', 'id');
        $title = "Mengedit User";
        $action = "edit";
        return view('rekaman-user.edit', compact('users', 'action', 'title', 'jabatan'));
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
        request()->validate(RekamanUser::$rules);
        request()->validate([
            'old_password' => ['required', new MatchOldPassword($id)],
            'password' => 'required|confirmed',
        ]);
        //mulai transaksi
        DB::beginTransaction();
        try {
            $pegawai = RekamanUser::find($id);
            if ($request->hasFile('file_foto')) {
                $image_path = "foto/" . $pegawai->file_foto;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                $file = $request->file('file_foto');
                $ext = $file->getClientOriginalExtension();
                $fileFoto = $id . "." . $ext;
                $destinationPath = 'foto/';
                $file->move($destinationPath, $fileFoto);
            } else {
                $fileFoto = $pegawai->file_foto;
            }
            DB::table('rekaman_users')->where('id', $id)->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'file_foto' => $fileFoto,
                'mst_jabatan_id' => $request->mst_jabatan_id,
                'password' => Hash::make($request->password),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            DB::commit();
            //mengembalikan tampilan ke view index (halaman sebelumnya)
            return redirect()->route('user.index')
                ->with('success', 'User berhasil diubah');
        } catch (Throwable $e) {
            //jika terjadi kesalahan batalkan semua
            return redirect()->route('user.index')
                ->with('success', 'User batal diubah, ada kesalahan');
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
            RekamanUser::find($id)->delete();
            DB::commit();
            // mengembalikan tampilan ke view index (halaman sebelumnya)
            return redirect()->route('user.index')
                ->with('success', 'User berhasil dihapus');
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->route('user.index')
                ->with('success', 'User ada kesalahan hapus batal... ');
        }
    }
}
