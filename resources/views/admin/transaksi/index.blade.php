@extends('layouts.admin')

@section('title', 'Transaksi Pending')

@push('page-styles')
<style>
    .transaksi-section {
        padding: 30px;
    }

    .transaksi-card {
        background: #ffffff10;
        backdrop-filter: blur(8px);
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 0 20px #00000030;
        border: 1px solid #ffffff25;
    }

    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
        overflow: hidden;
        border-radius: 10px;
    }

    table thead {
        background: #111111;
        color: #fff;
    }

    table th, table td {
        padding: 14px 16px;
        border-bottom: 1px solid #ffffff20;
    }

    tr:hover {
        background: #ffffff08;
    }

    .btn-detail {
        padding: 8px 14px;
        background: #d4a017;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
    }

    .btn-detail:hover {
        background: #f0c24a;
    }
</style>
@endpush

@section('content')
<div class="transaksi-section">
    <div class="transaksi-card">

        <h2 style="color: white; margin-bottom: 15px;">Daftar Transaksi Pending</h2>

        @if ($transaksi->isEmpty())
            <p style="color: #ddd;">Tidak ada transaksi menunggu konfirmasi.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Kolektor</th>
                        <th>Judul Karya</th>
                        <th>Harga</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($transaksi as $t)
                        <tr>
                            <td>{{ $t->id_transaksi }}</td>
                            <td>{{ $t->nama_kolektor }}</td>
                            <td>{{ $t->judul_karya }}</td>
                            <td>Rp {{ number_format($t->harga_terjual, 0, ',', '.') }}</td>
                            <td>{{ $t->tanggal_transaksi }}</td>
                            <td>
                                <a href="{{ route('admin.transaksi.show', $t->id_transaksi) }}" class="btn-detail">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        @endif
    </div>
</div>
@endsection
