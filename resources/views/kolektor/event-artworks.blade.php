@extends('layouts.kolektor')

@section('title', 'Karya — ' . ($event->nama_pameran ?? 'Pameran'))

@section('content')
<section style="padding:30px 20px;">
    <header style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <div>
            <h2 style="font-family:'Cinzel', serif; color:#d4af37;">{{ $event->nama_pameran }}</h2>
            <p style="color:#666;">{{ $event->lokasi_pameran }} • {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') }} — {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d M Y') }}</p>
        </div>
    </header>

    <div style="display:grid; grid-template-columns: repeat(auto-fill,minmax(220px,1fr)); gap:18px;">
        @forelse ($artworks as $a)
        <div style="background:rgba(255,255,255,0.03); border-radius:10px; padding:12px; box-shadow:0 6px 14px rgba(0,0,0,0.06);">
            <div style="height:180px; border-radius:8px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#f4f1ec;">
                @if(!empty($a->gambar))
                    <img src="{{ asset('images/asset/karyaSeni/' . $a->gambar) }}"
     alt="{{ $a->judul_karya }}"
     style="max-width:100%; max-height:100%; object-fit:cover;">

                @else
                    <i class="fa-solid fa-image" style="font-size:32px; color:rgba(212,175,55,0.6)"></i>
                @endif
            </div>

            <h3 style="margin-top:10px; font-family:'Cinzel', serif; font-size:1.05rem;">{{ $a->judul_karya }}</h3>
            <p style="font-size:0.9rem; color:#777;">Posisi: {{ $a->posisi_pajang ?? '-' }}</p>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:10px;">
                @if($a->is_sold)
                    <span style="background:#c0392b; color:#fff; padding:6px 8px; border-radius:8px; font-size:0.9rem;">SOLD</span>
                @else
                    <a href="{{ route('kolektor.transaksi.checkout', ['id_karya' => $a->id_karya]) }}"
                    style="background:#d4af37; padding:8px 12px; border-radius:8px; color:#fff; text-decoration:none;">
                    Purchase
                    </a>
                @endif

                <a href="#" onclick="alert('Catatan Kuratorial: {{ addslashes($a->catatan_kuratorial ?? '-') }}')" style="text-decoration:none; color:#8b7b5a; font-size:0.9rem;">
                    Catatan Kurator
                </a>
            </div>
        </div>
        @empty
        <p>Tidak ada karya yang sedang dipajang.</p>
        @endforelse
    </div>

    @if(session('success'))
        <div style="margin-top:18px; color:green;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="margin-top:18px; color:red;">{{ session('error') }}</div>
    @endif
</section>
@endsection
