@extends('layouts.kolektor')

@section('title', 'Checkout — ' . ($karya->judul_karya ?? 'Checkout'))

@section('content')
<section style="padding:30px;">
    <div style="max-width:900px; margin:0 auto;">
        <h2 style="color:#d4af37; font-family:Cinzel, serif;">Checkout</h2>

        <div style="display:flex; gap:20px; margin-top:18px; flex-wrap:wrap;">
            <div style="flex:1 1 320px;">
                <div style="background:#f6f3ee; padding:12px; border-radius:10px;">
                    @if($karya->gambar)
                        <img src="{{ asset('images/asset/karyaSeni/' . ($karya->gambar ?? 'default.jpg')) }}" alt="" 
     style="width:100%; height:320px; object-fit:cover; border-radius:8px;">

                    @else
                        <div style="height:320px; display:flex; align-items:center; justify-content:center;">
                            <i class="fa-solid fa-image" style="font-size:48px; color:rgba(212,175,55,0.6)"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div style="flex:1 1 350px;">
                <h3 style="font-family:Cinzel, serif;">{{ $karya->judul_karya }}</h3>
                <p>Harga: <strong>Rp {{ number_format($karya->harga, 0, ',', '.') }}</strong></p>
                <p>Tahun: {{ $karya->tahun_pembuatan ?? '-' }}</p>

                {{-- FORM CHECKOUT --}}
                <form action="{{ route('kolektor.transaksi.checkout.process') }}" method="POST">
                    @csrf

                    {{-- id karya --}}
                    <input type="hidden" name="id_karya" value="{{ $karya->id_karya }}">

                    {{-- metode pembayaran --}}
                    <div style="margin-top:12px;">
                        <label>Metode Pembayaran : QRIS</label>
                        <input type="hidden" name="metode_pembayaran" value="qris">
                    </div>

                    {{-- alamat pengiriman --}}
                    <div style="margin-top:12px;">
                        <label>Alamat Pengiriman</label>
                        <textarea name="alamat_pengiriman" rows="3" required
                                  style="width:100%; padding:8px; border-radius:6px;"></textarea>
                    </div>

                    {{-- catatan transaksi --}}
                    <div style="margin-top:12px;">
                        <label>Catatan Tambahan (opsional)</label>
                        <textarea name="catatan_transaksi" rows="2"
                                  style="width:100%; padding:8px; border-radius:6px;"></textarea>
                    </div>

                    {{-- submit --}}
                    <div style="margin-top:14px;">
                        <button type="submit" 
                                style="background:#d4af37; border:none; padding:10px 14px; color:#fff; border-radius:8px;">
                            Lanjutkan ke Pembayaran
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection
