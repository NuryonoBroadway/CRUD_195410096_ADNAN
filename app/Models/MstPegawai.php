<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MstPegawai extends Model
{
    use HasFactory;

    protected $table = 'mst_pegawais';
    protected $primaryKey = 'id';

    static $rules = [
        'nama' => 'required',
        'alamat' => 'required',
        'jenis_kel' => 'required',
        'agama' => 'required',
        'mst_jabatan_id' => 'required',
    ];

    protected $perPage = 20;

    protected $guarded = [];

    public function mstJabatan()
    {
        return $this->hasOne(MstJabatan::class, 'id', 'mst_jabatan_id');
    }

    static function listAgama()
    {
        return array(
            1 => 'Islam',
            2 => 'Katholik',
            3 => 'Protestan',
            4 => 'Hindu',
            5 => 'Budha',
            6 => 'Konghucu',
        );
    }

    public function getAgama()
    {
        if ($this->agama == "1") return "Islam";
        elseif ($this->agama == "2") return "Katholik";
        elseif ($this->agama == "3") return "Protistan";
        elseif ($this->agama == "4") return "Hindu";
        elseif ($this->agama == "5") return "Budha";
        elseif ($this->agama == "6") return "Konghucu";
        else
            return "Tidak diketahui";
    }

    public function getGapok($id)
    {
        $g = DB::table('riwayat_pangkat')
            ->where('pegawai_id', $id)
            ->where('status', 1)->first();
        if ($g == null) {
            return 0; //jika tidak ada return 0;
        } else {
            return $g->gaji_pokok;
        }
    }

    public function getTunjangan($id)
    {
        $g = DB::table('mst_jabatans')->where('id', $id)->first();
        if ($g == null) //jika tidak ada
            return 0;
        else
            return $g->tunjangan;
    }

    public function pGolTerahir()
    {
        //baca pangkat terakhir
        $pangkat = RiwayatPangkat::where('status', 1)->where('pegawai_id', $this->id)
            ->orderby('gaji_pokok', 'desc')->first();
        if ($pangkat == null) {
            return "Belum punya pangkat";
        } else {
            $pang = MstPangkat::find($pangkat->mst_pangkat_id);
            return $pang->nama_pangkat . " /Golongan : " . $pang->pangkat_gol;
        }
    }

    static function masaKerjaGol($id)
    {
        //menghitung masa kerja golongan
        $pangkat = RiwayatPangkat::where('status', 1)
            ->where('pegawai_id', $id)
            ->orderby('gaji_pokok', 'desc')->first();
        $tgl_sekarang = new \DateTime();
        if ($pangkat == null)
            $tgl_terhitung = new \DateTime();
        else
            $tgl_terhitung = new \DateTime($pangkat->tanggal_tmt_pangkat);
        //tanggal terhitung di sk - tanggal sekarang
        $masa = $tgl_sekarang->diff($tgl_terhitung);
        //hasilnya tahun dan bulan
        return $masa->y . " tahun, " . $masa->m . " bulan";
    }
}
