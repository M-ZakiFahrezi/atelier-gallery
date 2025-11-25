<h2>Status Pengiriman Order #{{ $order->kode_transaksi }}</h2>

<p>Halo {{ $order->kolektor->nama_kolektor }},</p>

<p>Status pengiriman karya "<strong>{{ $order->karya->judul_karya }}</strong>" telah diperbarui menjadi: 
<strong>{{ ucfirst(str_replace('_',' ', $order->status_pengiriman)) }}</strong>.</p>

@if($order->tanggal_pengiriman)
<p>Tanggal Pengiriman: {{ $order->tanggal_pengiriman }}</p>
@endif

<p>Terima kasih telah berbelanja di galeri kami.</p>
