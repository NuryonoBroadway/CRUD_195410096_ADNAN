<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-group">
            {{ Form::label('Nama','Nama',
            ['class' => 'col-sm-3 col-form-label','style'=>'font-weight:bold;']) }}
            <div class="col-sm-9">
                {{ Form::text('nama', $users->nama, ['class' => 'form-control' .
                ($errors->has('nama') ? ' is-invalid' : ''), 'placeholder' => 'Nama']) }}
                @error('nama') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('email','Email',['class' => 'col-sm-3 col-form-label',
            'style'=>'font-weight:bold;']) }}
            <div class="col-sm-9">
                {{ Form::text('email', $users->email, ['class' => 'form-control' .
                ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) }}
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('mst_jabatan_id','Jabatan Struktural',
            ['class' => 'col-sm-3 col-form-label','style'=>'font-weight:bold;']) }}
            <div class="col-sm-9">
                {{ Form::select('mst_jabatan_id',$jabatan, $users->mst_jabatan_id ,
                ['id'=>'mst_jabatan_id','class' => 'form-control' .
                ($errors->has('mat_jabatan_id') ? '
                is-invalid' : ''), 'placeholder' => '
                --- pilih ---']) }}
                @error('mst_jabatan_id') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        @if ($action == "edit")
        <div class="form-group">
            {{ Form::label('old_password','Old Password',['class' => 'col-sm-3 col-form-label',
            'style'=>'font-weight:bold;']) }}
            <div class="col-sm-9">
                {{ Form::password('old_password', null, ['class' => 'form-control' .
                ($errors->has('old_password') ? ' is-invalid' : ''), 'placeholder' => 'Password']) }}
                @error('old_password') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif
        <div class="form-group">
            {{ Form::label('password','Password',['class' => 'col-sm-3 col-form-label',
            'style'=>'font-weight:bold;']) }}
            <div class="col-sm-9">
                {{ Form::password('password', null, ['class' => 'form-control' .
                ($errors->has('password') ? ' is-invalid' : ''), 'placeholder' => 'Password']) }}
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('password_confirmation','Password Confirmation',['class' => 'col-sm-3 col-form-label',
            'style'=>'font-weight:bold;']) }}
            <div class="col-sm-9">
                {{ Form::password('password_confirmation', null, ['class' => 'form-control w-100' .
                ($errors->has('password_confirmation') ? ' is-invalid' : ''), 'placeholder' => 'Password Confirmation']) }}
                @error('password_confirmation') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('file_foto','Foto File (jpg)',
            ['class' => 'col-sm-3 col-form-label','style'=>'font-weight:bold;']) }}
            <div class="col-sm-9">
                {{ Form::file('file_foto', null, ['class' => 'form-control' .
                ($errors->has('file_foto') ? ' is-invalid' : ''),
                'placeholder' => 'Foto']) }}
                @error('file_foro') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        @if ($action == "edit")
        <div class="form-group">
            {{ Form::label('file_foto','Foto File (jpg)',
            ['class' => 'col-sm-3 col-form-label','style'=>'font-weight:bold;']) }}
            <div class="col-sm-9">
                @if($users->file_foto)
                <img style="object-fit: cover" id="original" src="{{ url('/foto/'.$users->file_foto) }}" height="120"
                    width="100">
                @endif
            </div>
        </div>
        @endif
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</div>
</div>