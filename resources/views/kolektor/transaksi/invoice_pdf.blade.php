<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice {{ $trx->kode_transaksi }}</title>

<style>
@page {
    size: A4;
    margin: 18mm 15mm;
}

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
    color: #22201c;
    background: #fff; /* dompdf lebih stabil jika putih */
}

.wrapper {
    max-width: 680px;
    margin: 0 auto;
}

.header-table {
    width: 100%;
    margin-bottom: 18px;
    padding-bottom: 8px;
    border-bottom: 1px solid #ddd;
}

.section-title {
    font-weight: bold;
    text-transform: uppercase;
    color: #7a6f63;
    font-size: 12px;
    margin-top: 18px;
    margin-bottom: 6px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    padding: 4px 0;
}

.art-image {
    width: 120px;
    height: auto; /* object-fit tidak aman */
    border: 1px solid #ccc;
    margin-right: 10px;
}

.summary-table th, .summary-table td {
    padding: 6px 0;
    border-bottom: 1px solid #ccc;
}

.total-row td {
    font-weight: bold;
    font-size: 13px;
}

.footer {
    text-align: center;
    font-size: 11px;
    margin-top: 20px;
    color: #7a6f63;
}
</style>
</head>

<body>
<div class="wrapper">

    <table class="header-table">
        <tr>
            <td width="60">
                <img src="{{ public_path('images/logo.png') }}" width="50">
            </td>
            <td>
                <h2 style="margin:0; font-size:20px;">Atelier Art Gallery</h2>
            </td>
            <td style="text-align:right;">
                <strong>Invoice:</strong> {{ $trx->kode_transaksi }}<br>
                <strong>Tanggal:</strong> {{ $trx->tanggal_transaksi }}
            </td>
        </tr>
    </table>

    <div class="section-title">Data Kolektor</div>
    <table>
        <tr><td width="150">Nama</td><td>: {{ $kolektor->nama_kolektor }}</td></tr>
        <tr><td>Alamat</td><td>: {{ $kolektor->alamat }}</td></tr>
        <tr><td>Kontak</td><td>: {{ $kolektor->kontak }}</td></tr>
        <tr><td>Jenis Kolektor</td><td>: {{ ucfirst($kolektor->jenis_kolektor) }}</td></tr>
    </table>

    <div class="section-title">Informasi Transaksi</div>
    <table>
        <tr><td width="150">Kode Transaksi</td><td>: {{ $trx->kode_transaksi }}</td></tr>
        <tr><td>Status Transaksi</td><td>: {{ ucfirst($trx->status_transaksi) }}</td></tr>
        <tr><td>Tanggal Pelunasan</td><td>: {{ $trx->tanggal_pelunasan ?? '-' }}</td></tr>
    </table>

    <div class="section-title">Detail Karya Seni</div>
    <table>
        <tr>
            <td width="130">
                @if($trx->gambar_karya)
                <img src="{{ public_path('images/asset/karyaSeni/' . $trx->gambar_karya) }}" class="art-image">
                @else
                <div style="width:120px; height:120px; background:#ddd; text-align:center; line-height:120px;">No Image</div>
                @endif
            </td>
            <td>
                <strong>{{ $trx->judul_karya }}</strong><br>
                Seniman: {{ $trx->nama_seniman }}<br>
                Tahun: {{ $trx->tahun_pembuatan }}<br><br>
                {{ $trx->deskripsi }}
            </td>
        </tr>
    </table>

    @if($trx->nama_pameran)
    <div class="section-title">Informasi Pameran</div>
    <table>
        <tr><td width="150">Nama Pameran</td><td>: {{ $trx->nama_pameran }}</td></tr>
        <tr><td>Lokasi</td><td>: {{ $trx->lokasi_pameran }}</td></tr>
        <tr><td>Waktu</td><td>: {{ $trx->tanggal_mulai }} — {{ $trx->tanggal_selesai }}</td></tr>
    </table>
    @endif

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
        Terima kasih telah melakukan pembelian di Atelier Art Gallery.<br>
        Invoice ini dihasilkan otomatis oleh sistem.
    </div>

</div>
</body>
</html>
