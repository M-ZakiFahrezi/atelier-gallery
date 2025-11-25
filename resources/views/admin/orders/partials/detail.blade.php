<div class="modal-header">
    <h5 class="modal-title">Detail Order #{{ $order->kode_transaksi }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<p><strong>Karya:</strong> {{ $order->karya->judul_karya ?? $order->judul_karya }}</p>
<p><strong>Kolektor:</strong> {{ $order->kolektor->nama_kolektor ?? $order->nama_kolektor }}</p>
    <p><strong>Email:</strong> {{ $order->kolektor->email ?? $order->email }}</p>
    <p><strong>Status Bayar:</strong> {{ $order->status_bayar }}</p>
    <p><strong>Status Pengiriman:</strong> {{ $order->status_pengiriman }}</p>
    <p><strong>Tanggal Transaksi:</strong> {{ $order->tanggal_transaksi }}</p>
    @if($order->tanggal_pengiriman)
    <p><strong>Tanggal Pengiriman:</strong> {{ $order->tanggal_pengiriman }}</p>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
