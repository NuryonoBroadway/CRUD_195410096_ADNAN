@extends('adminlte::page')
@section('title', 'Kenaikan Pangkat Pegawai')
@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="/riwayat-pangkat">Pegawai</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{$peg->nama}}</li>
    </ol>
</nav>
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; 
align-items: center;">
                        <span id="card_title">
                            <h3>Riwayat Kepangkatan Pegawai</h3>
                        </span>
                        <div class="float-right">
                            <div class="input-group custom-search-form">
                                <a href="{{ route('riwayat-pangkat.create', $peg->id)
                                    }}" class="btn btn-primary btn-sm">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    Tambah</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif
                <font size="4" face="Arial">
                    <table align="center" width="60%">
                        <tr>
                            <td>NIP </td>
                            <td> : {{ $peg->id }} </td>
                        </tr>
                        <tr>
                            <td>Nama </td>
                            <td> : {{ $peg->nama }} </td>
                        </tr>
                        <tr>
                            <td>Pangkat/Golongan Terakhir </td>
                            <td> : {{ $peg->pGolTerahir() }}</td>
                        </tr>
                        <tr>
                            <td>Masa Kerja Pangkat/Golongan </td>
                            <td> : {{$peg->masaKerjaGol($peg->id ) }}
                            </td>
                        </tr>
                    </table>
                </font>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>
                                    <th>TGL TMT</th>
                                    <th>NO SK</th>
                                    <th>NAMA PANGKAT</th>
                                    <th>GOL/RUANG</th>
                                    <th>GAJI POKOK</th>
                                    <th>STATUS</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=0 @endphp
                                @foreach ($rw as $riwPang)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $riwPang->tanggal_tmt_pangkat }}</td>
                                    <td>{{ $riwPang->no_sk_pangkat }}</td>
                                    <td>{{ $riwPang->getPangkat->nama_pangkat ==
                                        null ? "Belum punya" :
                                        $riwPang->getPangkat->nama_pangkat}}
                                    </td>
                                    <td>{{ $riwPang->getPangkat->pangkat_gol
                                        }}</td>
                                    <td>{{ $riwPang->gaji_pokok}}</td>
                                    <td>{{ $riwPang->status==0
                                        ?"Tidak":"Berlaku"}}</td>
                                    </td>
                                    <td>
                                        <form action="{{ route('riwayat-pangkat.destroy',$riwPang->id) }}" method="POST">
                                            <a class="btn btn-sm btn-primary" href="{{ route('riwayat-pangkat.show',$riwPang->id) }}">
                                                <i class="fa fa-fw fa-eye"></i>
                                                Lihat</a>
                                            <a class="btn btn-sm btn-success" href="{{ route('riwayat-pangkat.edit',$riwPang->id) }}">
                                                <i class="fa fa-fw fa-edit"></i>
                                                Ubah</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-fw fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection