@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@push('page-styles')
<style>
    .detail-section {
        padding: 30px;
    }

    .detail-card {
        background: #ffffff10;
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #ffffff25;
        box-shadow: 0 0 20px #00000040;
        max-width: 700px;
        margin: auto;
    }

    .detail-title {
        color: white;
        font-size: 22px;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .detail-item {
        margin-bottom: 12px;
        color: #ddd;
        font-size: 16px;
    }

    .btn-confirm {
        margin-top: 20px;
        width: 100%;
        padding: 12px;
        background: #d4a017;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        color: white;
        font-size: 17px;
    }

    .btn-confirm:hover {
        background: #f5c85c;
    }

    .btn-back {
        text-decoration: none;
        color: #ccc;
        margin-top: 15px;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="detail-section">
    <div class="detail-card">

        <div class="detail-title">Detail Transaksi</div>

        <div class="detail-item"><strong>ID Transaksi:</strong> {{ $t->id_transaksi }}</div>
        <div class="detail-item"><strong>Kolektor:</strong> {{ $t->nama_kolektor }} ({{ $t->email }})</div>
        <div class="detail-item"><strong>Karya:</strong> {{ $t->judul_karya }}</div>

        <div class="detail-item">
            <strong>Harga:</strong>  
            Rp {{ number_format($t->harga_terjual, 0, ',', '.') }}
        </div>

        <div class="detail-item"><strong>Metode Pembayaran:</strong> {{ $t->metode_pembayaran }}</div>
        <div class="detail-item"><strong>Status Pembayaran:</strong> {{ $t->status_bayar }}</div>

        <form action="{{ route('admin.transaksi.confirm', $t->id_transaksi) }}" method="POST">
            @csrf
            <button type="submit" class="btn-confirm">
                Konfirmasi Pembayaran
            </button>
        </form>

        <a href="{{ route('admin.transaksi.index') }}" class="btn-back">← Kembali</a>

    </div>
</div>
@endsection
