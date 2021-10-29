<?php

namespace App\Models;

use App\Rules\MatchOldPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RekamanUser extends Model
{
    use HasFactory;

    protected $table = 'rekaman_users';
    protected $primaryKey = 'id';

    static $rules = [
        'nama' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed',
        'mst_jabatan_id' => 'required',
    ];

    protected $perPage = 20;

    protected $guarded = [];

    public function mstJabatan()
    {
        return $this->hasOne(MstJabatan::class, 'id', 'mst_jabatan_id');
    }

    static function getPassword($id)
    {
        $g = RekamanUser::find($id);
        if ($g == null) //jika tidak ada
            return 0;
        else
            return $g->password;
    }
}
