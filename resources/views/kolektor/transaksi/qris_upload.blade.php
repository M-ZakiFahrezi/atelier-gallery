@extends('layouts.kolektor')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<section style="padding:30px;">
    <div style="max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:10px;">

        <h3 style="font-family:Cinzel, serif; color:#d4af37;">Upload Bukti Pembayaran</h3>

        <p>
            Kode Transaksi: <strong>{{ $trx->kode_transaksi }}</strong><br>
            Total Pembayaran: <strong>Rp {{ number_format($trx->harga_terjual,0,',','.') }}</strong>
        </p>

        <form action="{{ route('kolektor.transaksi.qris.konfirmasi', $trx->id_transaksi) }}" 
              method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-top:15px;">
                <label><strong>Upload Bukti Pembayaran (jpg/png)</strong></label>
                <input type="file" name="bukti_pembayaran" required
                       style="display:block; margin-top:8px;">
            </div>

            <div style="margin-top:15px;">
                <label>Catatan Tambahan (opsional)</label>
                <textarea name="catatan" rows="3"
                          style="width:100%; padding:8px; border-radius:6px;"></textarea>
            </div>

            <button type="submit" 
                style="margin-top:20px; background:#d4af37; padding:10px 16px; color:white; border-radius:8px; border:none;">
                Kirim Bukti Pembayaran
            </button>

        </form>

    </div>
</section>
@endsection
