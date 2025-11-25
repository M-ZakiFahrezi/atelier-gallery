@extends('layouts.kolektor')

@section('title', 'Pameran')

@section('content')
<section class="container" style="padding:40px 20px;">
    <h2 style="font-family:'Cinzel', serif; font-size:2rem; color:#d4af37; margin-bottom:18px;">Pameran</h2>

    <div style="display:grid; grid-template-columns: repeat(auto-fill,minmax(280px,1fr)); gap:22px;">
        @forelse ($events as $ev)
        <article style="background:rgba(255,255,255,0.06); border-radius:12px; padding:14px; box-shadow: 0 6px 18px rgba(0,0,0,0.08);">

    <div style="height:160px; border-radius:10px; overflow:hidden;">
        <img src="{{ asset('images/event.png') }}"
             alt="Event Image"
             style="width:100%; height:100%; object-fit:cover; object-position:center;">
    </div>

    <h3 style="margin-top:12px; font-family:'Cinzel', serif;">{{ $ev->nama_pameran }}</h3>

    <p style="font-family:'Poppins',sans-serif; color:#666; font-size:0.95rem;">
        {{ \Carbon\Carbon::parse($ev->tanggal_mulai)->format('d M Y') }}
        —
        {{ \Carbon\Carbon::parse($ev->tanggal_selesai)->format('d M Y') }}
    </p>

    <p style="color:#8b7b5a; font-size:0.9rem; margin-bottom:12px;">{{ $ev->lokasi_pameran }}</p>

    <div style="display:flex; gap:10px;">
        <a href="{{ route('kolektor.event.show', $ev->id_pameran) }}" class="btn" style="padding:8px 12px; border-radius:8px; text-decoration:none; background:transparent; border:1px solid rgba(212,175,55,0.6); color:#d4af37;">
            Lihat
        </a>

        <a href="{{ route('kolektor.event.artworks', $ev->id_pameran) }}" class="btn" style="padding:8px 12px; border-radius:8px; text-decoration:none; background:#d4af37; color:#fff;">
            Lihat Karya ({{ \DB::table('karya_pameran')->where('id_pameran',$ev->id_pameran)->where('status_display','dipajang')->count() }})
        </a>
    </div>

</article>

        @empty
        <p>Tidak ada pameran tersedia.</p>
        @endforelse
    </div>
</section>
@endsection
