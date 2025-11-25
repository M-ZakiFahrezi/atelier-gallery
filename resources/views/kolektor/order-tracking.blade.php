@extends('layouts.kolektor-dashboard')

@section('title', 'Order Tracking')

@push('page-styles')
<style>
/* THEME (match My Gallery) */
:root {
    --gold-strong: #d4af37;
    --gold-matte: #c2a86d;
    --shadow-deep: rgba(0,0,0,0.45);
    --shadow-soft: rgba(0,0,0,0.25);

    --bg-main-light: #faf7ef;
    --bg-card-light: rgba(255,255,255,0.75);
    --text-main-light: #3a2f21;

    --bg-main-dark: #0f0f0f;
    --bg-card-dark: rgba(25,25,25,0.75);
    --text-main-dark: #e6d8b8;
}

body.light-mode {
    --bg-main: var(--bg-main-light);
    --bg-card: var(--bg-card-light);
    --text-main: var(--text-main-light);
}

body.dark-mode {
    --bg-main: var(--bg-main-dark);
    --bg-card: var(--bg-card-dark);
    --text-main: var(--text-main-dark);
}

/* Page wrapper */
.tracking-wrapper {
    color: var(--text-main);
    animation: fadeIn 0.6s ease forwards;
}

@keyframes fadeIn { from { opacity:0; transform:translateY(12px);} to {opacity:1; transform:none;} }

/* Summary boxes */
.summary-row {
    display: flex;
    gap: 18px;
    margin-bottom: 18px;
}

.summary-card {
    flex: 1;
    padding: 18px;
    border-radius: 12px;
    background: var(--bg-card);
    border: 1px solid rgba(212,175,55,0.18);
    box-shadow: 0 4px 12px var(--shadow-soft);
    backdrop-filter: blur(8px);
    text-align: center;
}

.summary-card h3 { margin:0 0 6px; font-family: 'Cinzel', serif; color:var(--gold-matte); }
.summary-card p { margin:0; opacity:0.85; }

/* Table */
.table-card {
    background: var(--bg-card);
    border: 1px solid rgba(212,175,55,0.18);
    border-radius: 12px;
    padding: 14px;
    box-shadow: 0 4px 12px var(--shadow-soft);
    backdrop-filter: blur(8px);
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table thead th {
    text-align: left;
    padding: 10px;
    font-weight: 600;
    color: var(--gold-strong);
    font-size: 0.95rem;
    border-bottom: 1px solid rgba(212,175,55,0.12);
}

.orders-table tbody td {
    padding: 12px 10px;
    border-bottom: 1px solid rgba(212,175,55,0.06);
    vertical-align: middle;
    font-size: 0.95rem;
}

/* small image */
.karya-thumb {
    width: 60px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid rgba(0,0,0,0.06);
}

/* action button */
.btn-track {
    background: var(--gold-strong);
    color: #fff;
    padding: 8px 12px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    border: none;
}

.btn-track:hover { background: var(--gold-matte); }

/* Progress bar modal */
.progress-steps {
    display:flex;
    gap:10px;
    align-items:center;
    margin: 18px 0;
}
.progress-step {
    flex:1;
    text-align:center;
    position:relative;
}
.progress-step .dot {
    width:18px;height:18px;border-radius:50%;margin:0 auto 8px;background:#ddd;
    box-shadow:0 2px 6px rgba(0,0,0,0.08);
}
.progress-step.active .dot {
    background:var(--gold-strong);
    box-shadow:0 4px 14px rgba(212,175,55,0.28);
}
.progress-step .label { font-size:0.9rem; opacity:0.8; }

/* connector line */
.progress-step::after {
    content:'';
    position:absolute;
    height:3px;
    background: rgba(212,175,55,0.18);
    left:50%;
    right:-50%;
    top:9px;
    z-index:-1;
}
.progress-step:last-child::after { display:none; }

/* Modal */
.modal-backdrop {
    position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.45);
    display:none;align-items:center;justify-content:center;z-index:1200;
}
.modal {
    width: 920px;
    max-width: calc(100% - 40px);
    background: var(--bg-card);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(212,175,55,0.18);
    box-shadow: 0 8px 30px rgba(0,0,0,0.35);
}

/* modal grid */
.modal-grid { display:grid; grid-template-columns: 1fr 360px; gap: 16px; }
.modal-left img { width:100%; height:220px; object-fit:cover; border-radius:8px; }

/* responsive */
@media (max-width: 900px) {
    .summary-row { flex-direction:column; }
    .modal-grid { grid-template-columns: 1fr; }
    .profile-layout { grid-template-columns: 1fr; }
}

</style>
@endpush

@section('dashboard-content')
<div class="tracking-wrapper">
    <h1 class="gallery-title">Order Tracking</h1>
    <p class="gallery-subtitle">Lacak status pengiriman pesanan Anda.</p>

    <!-- summary boxes -->
    <div class="summary-row">
        <div class="summary-card">
            <h3>Total Order</h3>
            <p>{{ $totalOrders ?? 0 }}</p>
        </div>
        <div class="summary-card">
            <h3>Dalam Pengiriman</h3>
            <p>{{ $inTransit ?? 0 }}</p>
        </div>
        <div class="summary-card">
            <h3>Selesai</h3>
            <p>{{ $completed ?? 0 }}</p>
        </div>
    </div>

    <!-- table -->
    <div class="table-card">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Karya</th>
                    <th>Harga</th>
                    <th>Tgl Pesan</th>
                    <th>Status Bayar</th>
                    <th>Status Kirim</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $o)
                    <tr>
                        <td><strong>{{ $o->kode_transaksi }}</strong></td>
                        <td style="min-width:240px;">
                            <img class="karya-thumb" src="{{ $o->gambar_karya ? asset('/images/asset/karyaSeni/'.$o->gambar_karya) : '/mnt/data/0bfbdf04-658e-4763-ad57-3a9a22960d38.png' }}" alt="karya">
                            <span style="margin-left:10px;">{{ $o->judul_karya ?? '—' }}<br><small style="opacity:0.7;">{{ $o->nama_seniman ?? '' }}</small></span>
                        </td>
                        <td>Rp {{ number_format($o->harga_terjual,0,',','.') }}</td>
                        <td>{{ date('d M Y', strtotime($o->tanggal_transaksi)) }}</td>
                        <td style="text-transform:capitalize;">{{ $o->status_bayar }}</td>
                        <td style="text-transform:capitalize;">{{ $o->status_pengiriman }}</td>
                        <td>
                            <button class="btn-track" data-id="{{ $o->id_transaksi }}" onclick="openTrackingModal({{ $o->id_transaksi }})">
                                Lacak Pesanan
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="opacity:0.7; text-align:center;">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="modalBackdrop" class="modal-backdrop" onclick="closeModal(event)">
    <div class="modal" role="dialog" aria-modal="true" onclick="event.stopPropagation()">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <h3 style="margin:0;font-family:'Cinzel',serif;color:var(--gold-matte)">Tracking Detail</h3>
            <button style="background:transparent;border:none;font-size:20px;cursor:pointer" onclick="closeModal()">✕</button>
        </div>

        <div class="modal-grid">
            <div class="modal-left">
                <img id="modalImage" src="/mnt/data/0bfbdf04-658e-4763-ad57-3a9a22960d38.png" alt="karya">
                <h4 id="modalTitle" style="margin-top:10px"></h4>
                <p id="modalArtist" style="opacity:0.8;margin:4px 0"></p>
                <p id="modalPrice" style="font-weight:700;color:var(--gold-strong);margin-top:6px"></p>

                <div style="margin-top:12px;">
                    <div style="font-weight:600;margin-bottom:6px">Alamat Pengiriman</div>
                    <div id="modalAddress" style="opacity:0.9"></div>
                </div>
            </div>

            <div class="modal-right" style="padding-left:6px;">
                <div style="font-weight:700;">{{ __('Informasi Transaksi') }}</div>
                <div style="margin-top:6px;">
                    <div><strong>Kode:</strong> <span id="modalKode"></span></div>
                    <div><strong>Tanggal:</strong> <span id="modalTanggal"></span></div>
                    <div><strong>Metode Pembayaran:</strong> <span id="modalPayment"></span></div>
                    <div><strong>Status Bayar:</strong> <span id="modalStatusBayar"></span></div>
                    <div style="margin-top:12px;"><strong>Catatan:</strong><div id="modalNote" style="opacity:0.85;margin-top:6px"></div></div>
                </div>

                <hr style="margin:12px 0;border:none;border-top:1px solid rgba(212,175,55,0.08)">

                <div style="font-weight:700;">Status Pengiriman</div>

                <!-- Progress bar -->
                <div id="progressBar" class="progress-steps" aria-hidden="false">
                    <div class="progress-step" id="step-created">
                        <div class="dot"></div>
                        <div class="label">Pesanan Dibuat</div>
                    </div>
                    <div class="progress-step" id="step-paid">
                        <div class="dot"></div>
                        <div class="label">Pembayaran</div>
                    </div>
                    <div class="progress-step" id="step-shipped">
                        <div class="dot"></div>
                        <div class="label">Dikirim</div>
                    </div>
                    <div class="progress-step" id="step-delivered">
                        <div class="dot"></div>
                        <div class="label">Diterima</div>
                    </div>
                </div>

                <div style="margin-top:10px;"><strong>Tanggal Pengiriman:</strong> <div id="modalTglKirim" style="margin-top:6px"></div></div>

            </div>
        </div>

    </div>
</div>

@endsection

@push('page-scripts')
<script>
const modalBackdrop = document.getElementById('modalBackdrop');

function openTrackingModal(id) {
    // show backdrop immediately
    modalBackdrop.style.display = 'flex';

    fetch("{{ url('kolektor/order-tracking') }}/" + id, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Order not found');
        return res.json();
    })
    .then(data => {
        // populate modal fields
        document.getElementById('modalImage').src = data.gambar_karya ? "{{ asset('/images/asset/karyaSeni') }}/" + data.gambar_karya : '/mnt/data/0bfbdf04-658e-4763-ad57-3a9a22960d38.png';
        document.getElementById('modalTitle').textContent = data.judul_karya ?? '-';
        document.getElementById('modalArtist').textContent = data.nama_seniman ?? '';
        document.getElementById('modalPrice').textContent = 'Rp ' + (Number(data.harga_karya || data.harga_terjual || 0)).toLocaleString('id-ID');
        document.getElementById('modalAddress').textContent = data.alamat_pengiriman ?? '-';
        document.getElementById('modalKode').textContent = data.kode_transaksi ?? '-';
        document.getElementById('modalTanggal').textContent = data.tanggal_transaksi ? new Date(data.tanggal_transaksi).toLocaleString('id-ID') : '-';
        document.getElementById('modalPayment').textContent = data.metode_pembayaran ?? '-';
        document.getElementById('modalStatusBayar').textContent = data.status_bayar ?? '-';
        document.getElementById('modalNote').textContent = data.catatan_transaksi ?? '-';
        document.getElementById('modalTglKirim').textContent = data.tanggal_pengiriman ?? '-';

        // set progress steps
        // reset
        ['step-created','step-paid','step-shipped','step-delivered'].forEach(id => {
            document.getElementById(id).classList.remove('active');
        });

        // Pesanan dibuat: always active
        document.getElementById('step-created').classList.add('active');

        // Pembayaran: active if status_bayar == 'lunas'
        if ((data.status_bayar || '').toLowerCase() === 'lunas') {
            document.getElementById('step-paid').classList.add('active');
        }

        // Pengiriman: active if status_pengiriman is 'dikirim' or 'diterima'
        const ship = (data.status_pengiriman || '').toLowerCase();
        if (ship === 'dikirim' || ship === 'diterima') {
            document.getElementById('step-shipped').classList.add('active');
        }

        // Delivered
        if (ship === 'diterima') {
            document.getElementById('step-delivered').classList.add('active');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Gagal memuat data order.');
        closeModal();
    });
}

function closeModal(e) {
    if (e && e.target && e.target !== modalBackdrop) return;
    modalBackdrop.style.display = 'none';
}
</script>
@endpush
