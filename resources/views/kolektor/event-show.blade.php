@extends('layouts.kolektor')

@section('title', $event->nama_pameran ?? 'Detail Pameran')

@section('content')
<section style="padding:35px 20px;">

    <div style="
        background:linear-gradient(180deg, rgba(212,175,55,0.08), rgba(0,0,0,0.15));
        padding:32px;
        border-radius:14px;
        border:1px solid rgba(212,175,55,0.15);
        box-shadow:0 6px 20px rgba(0,0,0,0.25);
    ">

        <div style="display:flex; flex-wrap:wrap; gap:30px; align-items:center;">

            {{-- Bagian Teks --}}
            <div style="flex:1 1 360px;">

                <h1 style="
                    font-family:'Cinzel', serif;
                    font-size:2.2rem;
                    color:#d4af37;
                    margin-bottom:10px;
                ">
                    {{ $event->nama_pameran }}
                </h1>

                <div style="
                    height:2px;
                    width:80px;
                    background:#d4af37;
                    margin-bottom:18px;
                    border-radius:3px;
                "></div>

                <p style="color:#b8b8b8; font-family:'Poppins', sans-serif; line-height:1.6; font-size:0.98rem;">
                    <strong style="color:#d4af37;">Lokasi:</strong>
                    {{ $event->lokasi_pameran }} <br>

                    <strong style="color:#d4af37;">Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') }}
                    —
                    {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d M Y') }}
                </p>

                <a href="{{ route('kolektor.event.artworks', $event->id_pameran) }}"
                   class="btn"
                   style="
                        display:inline-block;
                        background:#d4af37;
                        padding:12px 18px;
                        border-radius:10px;
                        color:#fff;
                        text-decoration:none;
                        margin-top:16px;
                        font-weight:600;
                        transition:0.25s;
                   "
                   onmouseover="this.style.boxShadow='0 0 12px rgba(212,175,55,0.65)'"
                   onmouseout="this.style.boxShadow='none'">
                    Lihat Karya Dipajang ({{ $countDipajang }})
                </a>

            </div>

            {{-- Bagian Gambar --}}
            <div style="
                width:360px;
                height:220px;
                border-radius:12px;
                overflow:hidden;
                border:1px solid rgba(212,175,55,0.15);
                background:rgba(255,255,255,0.03);
                box-shadow:0 4px 14px rgba(0,0,0,0.2);
            ">
                <img src="{{ asset('images/event.png') }}"
                     alt="Event Image"
                     style="
                        width:100%;
                        height:100%;
                        object-fit:cover;
                        object-position:center;
                     ">
            </div>

        </div>
    </div>
</section>
@endsection
