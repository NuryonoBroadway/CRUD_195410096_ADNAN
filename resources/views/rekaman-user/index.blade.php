@extends('adminlte::page')
@section('title', 'Tabel Rekaman User') @section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">User</li>
    </ol>
</nav>
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span id="card_title">
                        <h3>Tabel Rekaman User</h3>
                    </span>
                    <div class="float-right">
                        <a href="{{ route('user.create') }}" class="btn btn-primary btn
sm"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
                    </div>
                </div>
            </div>
                @if ($message = Session::get('success')) <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-center table-bordered table-striped table-hover" style="width: 100%;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>id</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>E-Mail</th>
                                    <th>Jabatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td><img src="{{ URL::to('/foto/'.
                                        $user->file_foto) }}" height="50" width="50" style="object-fit: cover; border-radius: 50%"></td>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->nama_jabatan }}</td>
                                    <td>
                                        <form action="{{ route('user.destroy',$user->id) }}" method="POST">
                                            <a class="btn btn-sm btn-success" href="{{ route('user.edit',$user->id) }}"><i class="fa fa-fw fa-edit"></i> Ubah</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn
btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- {!! $pegawai->links('pagination::bootstrap-4') !!} --}}
        </div>
    </div>
</div>
@endsection