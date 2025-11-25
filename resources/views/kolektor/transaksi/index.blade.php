@extends('layouts.kolektor-dashboard')

@section('title', 'Transaksi')

@push('page-styles')
<style>

/* ----------------------------------------------------
   THEME VARIABLES
---------------------------------------------------- */
:root {
    --gold-strong: #d4af37;
    --gold-soft: #e9d9a3;
    --gold-matte: #c2a86d;

    --shadow-deep: rgba(0,0,0,0.45);
    --shadow-soft: rgba(0,0,0,0.25);

    /* LIGHT MODE */
    --bg-main-light: #faf7ef;
    --bg-card-light: rgba(255,255,255,0.65);
    --text-main-light: #3a2f21;

    /* DARK MODE */
    --bg-main-dark: #0f0f0f;
    --bg-card-dark: rgba(25,25,25,0.6);
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

/* ----------------------------------------------------
   WRAPPER
---------------------------------------------------- */
.transaksi-wrapper {
    color: var(--text-main);
    animation: fadeIn 0.7s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}

.transaksi-title {
    font-family: 'Cinzel', serif;
    font-size: 2rem;
    color: var(--gold-matte);
    margin-bottom: 10px;
}

.transaksi-subtitle {
    opacity: 0.75;
    margin-bottom: 25px;
}

/* ----------------------------------------------------
   TABLE STYLING – Premium Glass Table
---------------------------------------------------- */
.table-container {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 4px 15px var(--shadow-soft);
    border: 1px solid rgba(212,175,55,0.25);
    backdrop-filter: blur(10px);
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead tr {
    background: rgba(212,175,55,0.25);
    color: var(--gold-strong);
}

.table thead th {
    padding: 14px 12px;
    font-weight: 600;
    text-align: left;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: 0.25s ease;
}

.table tbody tr:hover {
    background: rgba(212,175,55,0.15);
    transform: scale(1.005);
}

.table tbody td {
    padding: 12px 14px;
    border-bottom: 1px solid rgba(212,175,55,0.18);
    font-size: 0.93rem;
}

/* ----------------------------------------------------
   STATUS BADGES
---------------------------------------------------- */
.status {
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.8rem;
    color: #504949ff;
    text-transform: capitalize;
    box-shadow: 0 2px 5px var(--shadow-soft);
}

.status.pending { background: #d4af37; }
.status.sukses { background: #3ca55c; }
.status.gagal { background: #b33; }
.status.dibatalkan { background: #777; }

/* ----------------------------------------------------
   DETAIL BUTTON
---------------------------------------------------- */
.btn-detail {
    padding: 8px 14px;
    background: var(--gold-strong);
    color: #454141ff;
    border-radius: 10px;
    font-weight: 600;
    transition: 0.2s ease;
}

.btn-detail:hover {
    background: var(--gold-matte);
    transform: translateY(-3px);
}

</style>
@endpush

@section('dashboard-content')

<div class="transaksi-wrapper">

    <h1 class="transaksi-title">Riwayat Transaksi</h1>
    <p class="transaksi-subtitle">Daftar transaksi pembelian karya seni Anda.</p>

    <div class="table-container">

        @if ($transaksis->isEmpty())
            <p style="opacity: 0.7;">Belum ada transaksi.</p>
        @else

        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($transaksis as $trx)
                    <tr>
                        <td><strong>{{ $trx->kode_transaksi }}</strong></td>

                        <td>
                            <span class="status {{ strtolower($trx->status_transaksi) }}">
                                {{ $trx->status_transaksi }}
                            </span>
                        </td>

                        <td>{{ date('d M Y, H:i', strtotime($trx->tanggal_transaksi)) }}</td>

                        <td>
                            Rp {{ number_format($trx->harga_terjual, 0, ',', '.') }}
                        </td>

                        <td>
                            <a href="{{ route('kolektor.transaksi.detail', $trx->id_transaksi) }}"
                               class="btn-detail">
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        @endif

    </div>

</div>

@endsection
