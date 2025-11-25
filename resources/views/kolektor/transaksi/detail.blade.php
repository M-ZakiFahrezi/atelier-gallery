@extends('layouts.kolektor')

@section('content')
<style>
/* ---------- THEME / TYPOGRAPHY ---------- */
:root{
  --ivory: #FBF7F2;
  --card-bg: #ffffff;
  --muted: #7a6f63;
  --ink: #22201c;
  --gold: #BFA24A;         /* subtle gold */
  --gold-dark: #9a7d33;
  --soft-border: #e9e3d8;
  --glass-shadow: 0 8px 30px rgba(34,32,28,0.06);
}

/* Body adjustments (inherit from layout) */
.container.detail-container { padding: 48px 24px; }

/* ---------- MAIN CARD ---------- */
.master-card {
  background: linear-gradient(180deg, var(--ivory) 0%, var(--card-bg) 100%);
  border: 1px solid var(--soft-border);
  border-radius: 18px;
  padding: 28px;
  box-shadow: var(--glass-shadow);
  color: var(--ink);
  max-width: 1120px;
  margin: 0 auto 48px;
}

/* Header */
.card-header {
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:20px;
  margin-bottom: 18px;
}
.header-left {
  display:flex;
  flex-direction:column;
}
.title {
  font-family: 'Georgia', serif;
  font-size:1.55rem;
  letter-spacing:0.6px;
  font-weight:700;
  color:var(--ink);
}
.subtitle {
  font-size:0.95rem;
  color:var(--muted);
  margin-top:6px;
}

/* Amount */
.amount {
  text-align:right;
}
.amount .amount-value {
  font-family: 'Georgia', serif;
  font-size:1.4rem;
  font-weight:700;
  color:var(--gold-dark);
}
.amount .amount-label {
  font-size:0.85rem;
  color:var(--muted);
}

/* ---------- CONTENT GRID (inside single card) ---------- */
/* We'll keep everything inside the same card but in a two-column grid */
.content-grid {
  display: grid;
  grid-template-columns: 1fr 420px;
  gap: 26px;
}

/* Left column list (info) */
.info-block {
  background: transparent;
  border-radius: 10px;
  padding: 6px 2px;
}

/* Clean info rows; museum label style */
.info-row {
  display:flex;
  gap:12px;
  padding: 10px 6px;
  align-items:flex-start;
  border-bottom:1px dashed #eee;
}
.info-row:last-child { border-bottom: none; }
.info-key {
  width: 180px;
  font-weight:600;
  color:var(--muted);
  text-transform: capitalize;
  font-size:0.95rem;
}
.info-val {
  color:var(--ink);
  font-size:0.97rem;
  line-height:1.35;
}

/* RIGHT column - artwork card */
.artwork {
  border-radius: 12px;
  background: #fff;
  border:1px solid var(--soft-border);
  padding: 14px;
  box-shadow: 0 8px 26px rgba(34,32,28,0.04);
  height:100%;
  display:flex;
  flex-direction:column;
  justify-content:flex-start;
  gap:12px;
}
.artwork .img-wrap {
  border-radius:10px;
  overflow:hidden;
  border:1px solid #f2f0ee;
  background: #faf7f3;
}
.artwork img {
  display:block;
  width:100%;
  height:320px;
  object-fit:cover;
  transition: transform .35s ease;
}
.artwork img:hover { transform: scale(1.02); }

.artwork h5 {
  margin:0;
  font-weight:700;
  font-family: 'Georgia', serif;
  letter-spacing:0.4px;
  color:var(--ink);
}
.artwork .meta { color:var(--muted); font-size:0.93rem; }

/* ---------- ACTIONS ---------- */
.actions {
  display:flex;
  gap:12px;
  justify-content:flex-end;
  margin-top:18px;
  flex-wrap:wrap;
}

/* Buttons: subtle gold outline, solid primary, danger (download PDF) */
.btn {
  padding:10px 18px;
  border-radius:10px;
  font-weight:600;
  letter-spacing:0.2px;
  cursor:pointer;
  border:1px solid transparent;
  transition: all .18s ease;
  text-decoration:none;
  display:inline-flex;
  align-items:center;
  gap:8px;
}

/* Gold outline */
.btn-outline-gold {
  background:transparent;
  color:var(--gold-dark);
  border:1.5px solid rgba(191,162,74,0.18);
  box-shadow: 0 2px 10px rgba(189,158,62,0.04);
}
.btn-outline-gold:hover {
  background: linear-gradient(90deg, rgba(201,162,39,0.08), rgba(201,162,39,0.03));
  transform: translateY(-2px);
}

/* Gold solid (primary) - subtle gradient */
.btn-gold {
  color: #fff;
  background: linear-gradient(180deg, var(--gold) 0%, var(--gold-dark) 100%);
  border: none;
  box-shadow: 0 8px 22px rgba(191,162,74,0.12);
}
.btn-gold:hover { transform: translateY(-2px); }

/* Small badge for status */
.badge-status {
  display:inline-block;
  padding:6px 12px;
  border-radius:20px;
  font-weight:700;
  font-size:0.85rem;
  color:#fff;
}
.badge-success { background: #2b8a4a; }
.badge-pending { background: #c48b1f; color:#fff;}
.badge-cancel { background:#b63b3b; }

/* Responsive */
@media (max-width: 980px) {
  .content-grid { grid-template-columns: 1fr; }
  .artwork img { height: 260px; }
  .amount { text-align:left; }
}
</style>

<div class="container detail-container">

  <div class="master-card">

    {{-- Header with title + status + amount --}}
    <div class="card-header">
      <div class="header-left">
        <div class="title">Detail Transaksi</div>
        <div class="subtitle">
          {{-- show readable small subtitle --}}
          {{ $trx->kode_transaksi }} • {{ \Illuminate\Support\Str::ucfirst($trx->status_transaksi) }}
        </div>
      </div>

      <div class="amount">
        <div class="amount-label">Total Pembayaran</div>
        <div class="amount-value">Rp {{ number_format($trx->harga_terjual, 0, ',', '.') }}</div>
      </div>
    </div>

    {{-- Single card content grid (left: info; right: artwork) --}}
    <div class="content-grid">

      {{-- LEFT: All info packed inside (still visually separated by rows) --}}
      <div class="info-block">

        {{-- TRANSACTION INFO GROUP --}}
        <div class="info-row">
          <div class="info-key">Kode Transaksi</div>
          <div class="info-val">{{ $trx->kode_transaksi }}</div>
        </div>

        <div class="info-row">
          <div class="info-key">Tanggal Transaksi</div>
          <div class="info-val">{{ $trx->tanggal_transaksi }}</div>
        </div>

        <div class="info-row">
          <div class="info-key">Status</div>
          <div class="info-val">
            @if($trx->status_transaksi == 'berhasil')
              <span class="badge-status badge-success">✓ Berhasil</span>
            @elseif($trx->status_transaksi == 'pending')
              <span class="badge-status badge-pending">⏳ Menunggu Pembayaran</span>
            @elseif($trx->status_transaksi == 'dibatalkan')
              <span class="badge-status badge-cancel">✖ Dibatalkan</span>
            @else
              <span class="badge-status" style="background:#6c6c6c;">{{ $trx->status_transaksi }}</span>
            @endif
          </div>
        </div>

        <div class="info-row">
          <div class="info-key">Tanggal Pelunasan</div>
          <div class="info-val">{{ $trx->tanggal_pelunasan ?? '-' }}</div>
        </div>

        {{-- COLLECTOR INFO GROUP --}}
        <div style="height:10px"></div>
        <div style="margin:8px 0 12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:0.6px;">Data Kolektor</div>

        <div class="info-row">
            <div class="info-key">Nama</div>
            <div class="info-val">{{ $kolektor->nama_kolektor }}</div>
        </div>

        <div class="info-row">
            <div class="info-key">Alamat</div>
            <div class="info-val">{{ $kolektor->alamat ?? '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-key">Telepon</div>
            <div class="info-val">{{ $kolektor->kontak ?? '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-key">Jenis Kolektor</div>
            <div class="info-val">{{ ucfirst($kolektor->jenis_kolektor) }}</div>
        </div>

        <div class="info-row">
            <div class="info-key">Username</div>
            <div class="info-val">{{ $kolektor->username }}</div>
        </div>

        {{-- EXHIBITION INFO (if exists) --}}
        @if($trx->nama_pameran)
          <div style="height:10px"></div>
          <div style="margin:8px 0 12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:0.6px;">Informasi Pameran</div>

          <div class="info-row">
            <div class="info-key">Nama Pameran</div>
            <div class="info-val">{{ $trx->nama_pameran }}</div>
          </div>

          <div class="info-row">
            <div class="info-key">Lokasi</div>
            <div class="info-val">{{ $trx->lokasi_pameran }}</div>
          </div>

          <div class="info-row">
            <div class="info-key">Tanggal</div>
            <div class="info-val">{{ $trx->tanggal_mulai }} — {{ $trx->tanggal_selesai }}</div>
          </div>
        @endif

      </div> {{-- end info-block --}}

      {{-- RIGHT: Artwork preview + minimal meta --}}
      <div class="artwork">
        <div class="img-wrap">
          {{-- Primary: stored artwork; Fallback: uploaded local test file (preview) --}}
            @if($karya && $karya->gambar)
                <img src="{{ asset('images/asset/karyaSeni/' . $karya->gambar) }}"
                    class="img-fluid rounded shadow-sm" alt="{{ $karya->judul_karya }}">
            @else
                <div class="text-muted">Tidak ada gambar</div>
            @endif
        </div>

        <div>
          <h5>{{ $trx->judul_karya }}</h5>
          <div class="meta" style="margin-top:6px;">
            <div><strong>Seniman:</strong> {{ $trx->nama_seniman ?? '-' }}</div>
            <div><strong>Tahun:</strong> {{ $trx->tahun_pembuatan ?? '-' }}</div>
            <div style="margin-top:6px;"><strong>Harga Asli:</strong> Rp {{ number_format($trx->harga_karya ?? 0,0,',','.') }}</div>
          </div>
        </div>

        <div style="margin-top:8px; color:var(--muted); font-size:0.92rem;">
          {{ \Illuminate\Support\Str::limit($trx->deskripsi, 220) }}
        </div>
      </div> {{-- end artwork --}}

    </div> {{-- end content-grid --}}

    {{-- ACTIONS (still inside same card) --}}
    <div class="actions">

        @if($trx->status_transaksi == 'berhasil')
            <a href="{{ url('/kolektor/transaksi/'.$trx->id_transaksi.'/invoice') }}" class="btn btn-outline-gold">
                Lihat Invoice
            </a>
        @endif

        @if($trx->status_transaksi == 'pending')
            <a href="{{ route('kolektor.transaksi.qris.show', $trx->id_transaksi) }}" class="btn btn-gold">
                Lanjutkan Pembayaran
            </a>
        @endif
    </div>


  </div> {{-- end master-card --}}

</div> {{-- end container --}}
@endsection
