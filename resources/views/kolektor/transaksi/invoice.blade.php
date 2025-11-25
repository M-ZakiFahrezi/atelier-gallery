@extends('layouts.kolektor')

@section('content')
<style>
:root{
  --ivory:#FBF7F2;
  --ink:#22201c;
  --muted:#7a6f63;
  --gold:#BFA24A;
  --soft-border:#e9e3d8;
}

.invoice-wrapper {
  max-width: 900px;
  margin:40px auto;
  background: var(--ivory);
  border:1px solid var(--soft-border);
  border-radius:14px;
  padding:32px 40px;
  box-shadow:0 8px 32px rgba(34,32,28,0.06);
  color:var(--ink);
}

/* HEADER */
.header {
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:26px;
  padding-bottom:20px;
  border-bottom:1px solid var(--soft-border);
}

.header-left {
  display:flex;
  align-items:center;
  gap:16px;
}

.header-left img {
  width:60px;
  height:60px;
  object-fit:contain;
}

.atelier-name {
  font-family:'Georgia', serif;
  font-size:1.9rem;
  font-weight:700;
  letter-spacing:0.5px;
}

.header-right div {
  margin-bottom:4px;
}

/* SECTION TITLE */
.section-title {
  font-weight:700;
  margin-top:30px;
  margin-bottom:12px;
  color:var(--muted);
  text-transform:uppercase;
  letter-spacing:0.6px;
}

/* INFO TABLE */
.info-table {
  width:100%;
  border-collapse:collapse;
}
.info-table td {
  padding:6px 0;
  vertical-align:top;
}

/* ARTWORK */
.artwork-box {
  display:flex;
  gap:16px;
  margin-top:16px;
}
.artwork-box img {
  width:150px;
  height:150px;
  object-fit:cover;
  border-radius:10px;
  border:1px solid var(--soft-border);
}

/* SUMMARY TABLE */
.summary-table {
  width:100%;
  border-collapse:collapse;
  margin-top:20px;
}
.summary-table th, .summary-table td {
  padding:10px 0;
  border-bottom:1px solid #ddd;
}
.summary-table th {
  text-align:left;
  font-weight:600;
}
.total-row {
  font-weight:700;
  font-size:1.2rem;
}

/* ACTION BUTTONS */
.actions {
  display:flex;
  gap:12px;
  justify-content:flex-end;
  margin-top:18px;
  flex-wrap:wrap;
}

.btn {
  padding:11px 20px;
  border-radius:10px;
  font-weight:600;
  cursor:pointer;
  border:1px solid transparent;
  transition:all .18s ease;
  text-decoration:none;
}

/* GOLD PREMIUM DOWNLOAD BUTTON */
.btn-download {
  background: linear-gradient(180deg, #d9c07a, #bfa24a);
  color: #fff;
  border:1px solid #a88c3a;
  padding:11px 22px;
  border-radius:10px;
  font-weight:600;
  letter-spacing:0.3px;
  transition: all .18s ease;
  text-decoration:none;
  display:inline-flex;
  align-items:center;
  gap:8px;
  box-shadow:0 3px 10px rgba(191,162,74,0.25);
}

.btn-download:hover {
  transform: translateY(-2px);
  box-shadow:0 6px 14px rgba(191,162,74,0.35);
}

.footer {
  margin-top:40px;
  text-align:center;
  font-size:0.9rem;
  color:var(--muted);
}
</style>

<div class="invoice-wrapper">

  {{-- HEADER --}}
  <div class="header">
    <div class="header-left">
      <img src="{{ asset('images/logo.png') }}" alt="Logo Atelier">
      <div class="atelier-name">Atelier Art Gallery</div>
    </div>

    <div class="header-right">
      <div><strong>Invoice:</strong> {{ $trx->kode_transaksi }}</div>
      <div><strong>Tanggal:</strong> {{ $trx->tanggal_transaksi }}</div>
    </div>
  </div>

  {{-- KOLEKTOR --}}
  <div class="section-title">Data Kolektor</div>
  <table class="info-table">
    <tr><td>Nama</td><td>: {{ $kolektor->nama_kolektor }}</td></tr>
    <tr><td>Alamat</td><td>: {{ $kolektor->alamat }}</td></tr>
    <tr><td>Kontak</td><td>: {{ $kolektor->kontak }}</td></tr>
    <tr><td>Jenis Kolektor</td><td>: {{ ucfirst($kolektor->jenis_kolektor) }}</td></tr>
  </table>

  {{-- TRANSAKSI --}}
  <div class="section-title">Informasi Transaksi</div>
  <table class="info-table">
    <tr><td>Kode Transaksi</td><td>: {{ $trx->kode_transaksi }}</td></tr>
    <tr><td>Status Transaksi</td><td>: {{ ucfirst($trx->status_transaksi) }}</td></tr>
    <tr><td>Tanggal Pelunasan</td><td>: {{ $trx->tanggal_pelunasan ?? '-' }}</td></tr>
  </table>

  {{-- KARYA SENI --}}
  <div class="section-title">Detail Karya Seni</div>
  <div class="artwork-box">
    @if($trx->gambar_karya)
      <img src="{{ asset('images/asset/karyaSeni/' . $trx->gambar_karya) }}">
    @else
      <div style="width:150px; height:150px; background:#ddd; display:flex; align-items:center; justify-content:center;">No Image</div>
    @endif

    <div>
      <div><strong>{{ $trx->judul_karya }}</strong></div>
      <div>Seniman: {{ $trx->nama_seniman }}</div>
      <div>Tahun: {{ $trx->tahun_pembuatan }}</div>
      <div style="margin-top:8px;">{{ $trx->deskripsi }}</div>
    </div>
  </div>

  {{-- PAMERAN --}}
  @if($trx->nama_pameran)
  <div class="section-title">Informasi Pameran</div>
  <table class="info-table">
    <tr><td>Nama Pameran</td><td>: {{ $trx->nama_pameran }}</td></tr>
    <tr><td>Lokasi</td><td>: {{ $trx->lokasi_pameran }}</td></tr>
    <tr><td>Waktu</td><td>: {{ $trx->tanggal_mulai }} — {{ $trx->tanggal_selesai }}</td></tr>
  </table>
  @endif

  {{-- SUMMARY --}}
  <div class="section-title">Ringkasan Pembayaran</div>
  <table class="summary-table">
    <tr>
      <th>Keterangan</th>
      <th>Jumlah</th>
    </tr>
    <tr>
      <td>Harga Karya Seni</td>
      <td>Rp {{ number_format($trx->harga_karya, 0, ',', '.') }}</td>
    </tr>
    <tr class="total-row">
      <td>Total Dibayar</td>
      <td>Rp {{ number_format($trx->harga_terjual, 0, ',', '.') }}</td>
    </tr>
  </table>

  <div class="footer">
    Terima kasih telah melakukan pembelian di Galeri Seni.<br>
    Invoice ini sah dan diproses secara otomatis oleh sistem.
  </div>

{{-- ACTION BUTTON --}}
    <div class="actions" style="margin-top:32px;">
        <a href="{{ url('/kolektor/transaksi/'.$trx->id_transaksi.'/invoice/pdf') }}" class="btn-download">
            Download PDF
        </a>
    </div>

</div>

@endsection
