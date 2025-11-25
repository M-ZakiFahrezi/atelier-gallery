@extends('layouts.kolektor')

@section('title', 'Pembayaran QRIS')
@push('page-styles')
<style>
.container-qris {
    padding: 60px 20px;
    display: flex;
    justify-content: center;
    font-family: 'Times New Roman', serif;
}

.qris-wrapper {
    display: flex;
    gap: 45px;
    justify-content: center;
    align-items: flex-start;
    max-width: 1100px;
}

/* ================= LEFT CARD ================= */
.qris-left {
    background: #faf7f2;
    border: 1px solid #d9cbb9;
    border-radius: 20px;
    padding: 40px 30px;
    width: 430px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    transition: .2s ease;
}

.qris-left:hover {
    box-shadow: 0 14px 40px rgba(0,0,0,0.17);
}

.header-wrapper img {
    height: 52px;
    margin: 0 5px;
}

/* QR BOX */
.qris-img {
    width: 270px;
    height: 270px;
    border-radius: 12px;
    padding: 12px;
    background: white;
    border: 1.5px solid #cab8a3;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    margin: 18px 0 20px;
}

/* Wallet Icons */
.wallet-logos img {
    width: 40px;
    opacity: 0.9;
    margin: 0 5px;
}

/* Button */
.qr-btn {
    display: block;
    width: 100%;
    padding: 12px;
    margin-top: 12px;
    border-radius: 10px;
    border: 1px solid #c4b39e;
    background: #f0ebe5;
    color: #4a443d;
    font-weight: 600;
    transition: .15s;
    text-decoration: none;
    text-align: center;
}
.qr-btn:hover {
    background: #e6dfd8;
}

/* ================= RIGHT CARD ================= */
.qris-right {
    background: #f7f2ec;
    width: 350px;
    padding: 30px 25px;
    border-radius: 18px;
    border: 1px solid #d9cbb9;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.qris-right h4 {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 12px;
}

.qris-right ol {
    counter-reset: qris-counter;
    padding-left: 0;
}

.qris-right ol li {
    list-style: none;
    counter-increment: qris-counter;
    margin: 10px 0;
    padding-left: 38px;
    position: relative;
    line-height: 1.5;
}

.qris-right ol li::before {
    content: counter(qris-counter);
    position: absolute;
    left: 0;
    top: 3px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #e6dac8;
    border: 1px solid #c8b59e;
    color: #4a433d;
    font-weight: bold;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}


/* Highlight Warning */
.qris-warning {
    margin-top: 18px;
    background: #fff7cd;
    padding: 12px 14px;
    border-left: 5px solid #e6b800;
    border-radius: 6px;
}

/* Responsive */
@media (max-width: 900px) {
    .qris-wrapper {
        flex-direction: column;
        align-items: center;
        gap: 30px;
    }
}
</style>
@endpush

@section('content')
<div class="container-qris">

    <div class="qris-wrapper">

        {{-- LEFT CARD --}}
        <div class="qris-left">

            <div class="header-wrapper mb-3">
                <img src="{{ asset('images/qris-header1.png') }}">
                <img src="{{ asset('images/qris-header2.png') }}">
            </div>

            <h3>Pembayaran QRIS</h3>
            <h4 class="mt-2">Rp {{ number_format($transaksi->harga_terjual, 0, ',', '.') }}</h4>

            <img src="{{ $transaksi->qris_url }}" class="qris-img">

            <div class="wallet-logos mb-3">
                <img src="{{ asset('images/wallets/gopay.png') }}">
                <img src="{{ asset('images/wallets/ovo.png') }}">
                <img src="{{ asset('images/wallets/dana.png') }}">
                <img src="{{ asset('images/wallets/linkaja.png') }}">
                <img src="{{ asset('images/wallets/spay.png') }}">
            </div>

            <a href="{{ $transaksi->qris_url }}" download="qris-{{ $transaksi->id_transaksi }}.png" class="qr-btn">
                Download QRIS
            </a>
        </div>

        {{-- RIGHT CARD --}}
        <div class="qris-right">

            <h4>Cara Pembayaran</h4>
            <ol>
                <li>Buka aplikasi e-wallet atau mobile banking.</li>
                <li>Pilih menu <strong>Scan QR</strong>.</li>
                <li>Arahkan kamera ke QR.</li>
                <li>Pastikan nominal benar.</li>
                <li>Konfirmasi pembayaran.</li>
            </ol>

            <div class="qris-warning">
                ⚠️ QRIS berlaku selama <strong>15 menit</strong>.
            </div>

            <a href="{{ route('kolektor.transaksi.detail', $transaksi->id_transaksi) }}" class="qr-btn mt-4">
                ➜ Lanjutkan
            </a>

            <form action="{{ route('kolektor.transaksi.batalkan', $transaksi->id_transaksi) }}" method="POST">
                @csrf
                <button class="qr-btn mt-2" type="submit">✖ Batalkan Transaksi</button>
            </form>

        </div>

    </div>

</div>
@endsection

