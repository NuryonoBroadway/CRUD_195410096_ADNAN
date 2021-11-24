<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatPangkatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_pangkats', function (Blueprint $table) {
            $table->id();
            $table->integer("pegawai_id")->unsigned();
            $table->integer("mst_pangkat_id")->unsigned();
            $table->date("tanggal_tmt_pangkat");
            $table->string("no_sk_pangkat");
            $table->integer("gaji_pokok");
            $table->integer("status");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_pangkats');
    }
}
