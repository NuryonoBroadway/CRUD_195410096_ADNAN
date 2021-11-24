<div class="box box-info padding-1">
    <div class="box-body">
        {{ Form::hidden('pegawai_id',$peg->id) }}
        <div class="form-group">
            {{ Form::label('Nomor SK') }}
            {{ Form::text('no_sk_pangkat', $rw->no_sk_pangkat,['class' => 'form-control' .
            ($errors->has('no_sk_pangkat') ? ' is-invalid' : ''), 'placeholder' => '']) }}
            @error('mat_pangkat_id') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            {{ Form::label('Tanggal Terhitung Mulai') }}
            {{ Form::date('tanggal_tmt_pangkat', $rw->tanggal_tmt_pangkat,['class' => 'form-control' .
            ($errors->has('tanggal_tmt_pangkat') ? ' is-invalid' : ''), 'placeholder' => ''])
            }}
            @error('tanggal_tmt_pangkat') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            {{ Form::label('pangkat_golongan') }}
            {{ Form::select('mst_pangkat_id',$pangkat, $rw->mst_pangkat_id ,
            ['id'=>'mst_pangkat_id','class' => 'form-control' .
            ($errors->has('mat_pangkat_id') ? ' is-invalid' : ''), 'placeholder' =>
            '
            --- pilih ---']) }}
            @error('mat_pangkat_id') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            {{ Form::label('Gaji Pokok') }}
            {{ Form::number('gaji_pokok', $rw->gaji_pokok,['class' => 'form-control' .
            ($errors->has('gaji_pokok') ? ' is-invalid' : ''), 'placeholder' => '']) }}
            @error('gaji_pokok') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            {{ Form::label('Status') }}
            {{ Form::select('status',\App\Models\RiwayatPangkat::listStatus(),
            $rw->status, ['id'=>'status','class' => 'form-control' .
            ($errors->has('status') ? 'is-invalid' : ''), 'placeholder' => '--- pilih -
            --']) }}
            @error('status') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="box-footer mt20">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</div>