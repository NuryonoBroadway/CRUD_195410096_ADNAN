<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstJabatan extends Model
{
    use HasFactory;

    protected $table = 'mst_jabatans';
    protected $primaryKey = 'id';


    static $rules = [
        'nama_jabatan' => 'required',
        'tunjangan' => 'required'
    ];

    protected $perPage = 10;

    protected $fillable = ['nama_jabatan', 'tunjangan'];
}
