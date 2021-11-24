<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPangkat extends Model
{
    use HasFactory;

    protected $table = "riwayat_pangkats";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getPegawai()
    {
        return $this->belongsTo(MstPegawai::class, 'pegawai_id');
    }

    public function getPangkat()
    {
        return $this->belongsTo(MstPangkat::class, 'mst_pangkat_id');
    }

    static function listStatus()
    {
        return array(0 => 'Tidak berlaku', 1 => 'Berlaku');
    }

    //membaca pangkat terahir yang diberi status=1 
    static function pGolTerahir($id)
    {
        $pang = self::where('status', 1)->where('pegawai_id', $id)
            ->orderby('created_at', 'desc')->first();
        return $pang;
    }
}
