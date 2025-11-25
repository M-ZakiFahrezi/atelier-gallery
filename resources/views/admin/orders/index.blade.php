@extends('layouts.admin')

@section('title', 'Kelola Orders')

@section('content')

<style>
/* Container styling */
.order-container {
    background: rgba(255, 255, 255, 0.6);
    border-radius: 14px;
    padding: 25px;
    border: 1px solid rgba(212,175,55,0.25);
    box-shadow: 0 6px 25px rgba(0,0,0,0.15);
    backdrop-filter: blur(10px);
}

/* Table */
.order-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
}

.order-table thead th {
    background: rgba(212,175,55,0.2);
    color: #5b4a00;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid rgba(212,175,55,0.35);
}

.order-row {
    background: rgba(255,255,255,0.9);
    transition: 0.25s;
    border: 1px solid rgba(212,175,55,0.25);
}

.order-row:hover {
    background: rgba(212,175,55,0.16);
    transform: scale(1.01);
}

.order-row td {
    padding: 12px;
    color: #333;
    font-size: 0.95rem;
}

/* Status badge */
.badge-status {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.86rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.badge-belum {
    background: #f9d8d8;
    color: #b30000;
}

.badge-dikirim {
    background: #fff4cc;
    color: #8a5b00;
}

.badge-diterima {
    background: #d4f8d4;
    color: #0f7100;
}

/* Update button */
.btn-update {
    background: #d4af37;
    color: #fff;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    transition: 0.25s;
}

.btn-update:hover {
    background: #c39a2f;
}

/* Select */
.select-status {
    border-radius: 6px;
    border: 1px solid rgba(212,175,55,0.4);
    padding: 6px 8px;
}
</style>

<div class="order-container">

    <h2 class="mb-4" style="color:#5b4a00; font-weight:600;">Kelola Status Pengiriman</h2>

    {{-- 🔍 SEARCH FORM --}}
    <form method="GET" class="mb-3">
        <input type="text" name="kode" class="form-control" placeholder="Cari kode transaksi..."
               value="{{ request('kode') }}">
    </form>

    <table class="order-table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Karya</th>
                <th>Kolektor</th>
                <th>Status Pembayaran</th>
                <th>Status Pengiriman</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $order)
            <tr class="order-row">

                <td>{{ $order->kode_transaksi }}</td>
                <td>{{ $order->judul_karya }}</td>
                <td>{{ $order->nama_kolektor }}</td>

                {{-- STATUS PEMBAYARAN --}}
                <td>
                    <span class="badge-status 
                        {{ strtolower($order->status_bayar) == 'lunas' ? 'badge-diterima' : 'badge-belum' }}">
                        <i class="fa {{ strtolower($order->status_bayar) == 'lunas' ? 'fa-check' : 'fa-clock' }}"></i>
                        {{ ucfirst($order->status_bayar) }}
                    </span>
                </td>

                {{-- STATUS PENGIRIMAN --}}
                <td>
                    @php
                        $badgeClass = [
                            'belum_dikirim' => 'badge-belum',
                            'dikirim'       => 'badge-dikirim',
                            'diterima'      => 'badge-diterima'
                        ][$order->status_pengiriman] ?? 'badge-belum';

                        $icon = [
                            'belum_dikirim' => 'fa-clock',
                            'dikirim'       => 'fa-truck',
                            'diterima'      => 'fa-check'
                        ][$order->status_pengiriman] ?? 'fa-clock';
                    @endphp

                    <span class="badge-status {{ $badgeClass }}">
                        <i class="fa {{ $icon }}"></i>
                        {{ ucfirst(str_replace('_',' ', $order->status_pengiriman)) }}
                    </span>
                </td>

                {{-- AKSI --}}
                <td>

                    {{-- Tombol Detail (AJAX Modal) --}}
                    <button type="button" class="btn btn-sm btn-outline-dark mb-1"
                        onclick="openOrderDetail({{ $order->id_transaksi }})">
                        <i class="fa fa-eye"></i> Detail
                    </button>

                    {{-- Form Update --}}
                    <form action="{{ route('admin.orders.update-status', $order->id_transaksi) }}" method="POST">
                        @csrf
                        <select name="status_pengiriman" class="select-status mb-2">
                            <option value="belum_dikirim" {{ $order->status_pengiriman == 'belum_dikirim' ? 'selected' : '' }}>Belum Dikirim</option>
                            <option value="dikirim" {{ $order->status_pengiriman == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="diterima" {{ $order->status_pengiriman == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        </select>

                        <button class="btn-update" type="submit">Update</button>
                    </form>

                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>


{{-- ===================================================== --}}
{{-- MODAL DETAIL TRANSAKSI --}}
{{-- ===================================================== --}}
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="modal-content-area">
            <!-- Konten AJAX akan dimuat di sini -->
        </div>
    </div>
</div>

<script>
function openOrderDetail(id) {
    fetch('/admin/orders/' + id + '/detail')
        .then(res => res.text())
        .then(html => {
            document.getElementById('modal-content-area').innerHTML = html;
            new bootstrap.Modal(document.getElementById('orderDetailModal')).show();
        });
}

</script>

@endsection
