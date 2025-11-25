@extends('layouts.admin')

@section('title', 'Event — Daftar Pameran')

@section('content')
<section>

    {{-- HEADER --}}
    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        margin-bottom:18px;">
        
        <h2 style="
            font-family:'Cinzel', serif;
            color:#d4af37;
            font-size:1.6rem;
            margin:0;">
            Daftar Pameran
        </h2>

        <a href="{{ route('admin.event.create') }}"
           style="
                background:#d4af37;
                color:#fff;
                padding:10px 14px;
                border-radius:8px;
                text-decoration:none;
                font-weight:600;">
            + Tambah Event
        </a>
    </div>



    {{-- LIST EVENT --}}
    @if($events->count())
    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
        gap:18px;">

        @foreach($events as $ev)
        <div style="
            background:#fff;
            border-radius:12px;
            padding:14px;
            box-shadow:0 6px 18px rgba(0,0,0,0.06);">

            {{-- EVENT HEADER --}}
            <div style="display:flex; gap:12px; align-items:center;">

                <div style="
                    width:84px;
                    height:84px;
                    border-radius:8px;
                    background:linear-gradient(135deg,
                        rgba(212,175,55,0.08),
                        rgba(0,0,0,0.03));
                    display:flex;
                    align-items:center;
                    justify-content:center;">
                    <i class="fa-solid fa-calendar-days"
                       style="font-size:28px; color:rgba(212,175,55,0.8)"></i>
                </div>

                <div style="flex:1;">
                    <h3 style="
                        font-family:'Cinzel', serif;
                        margin:0 0 6px 0;
                        font-size:1.05rem;
                        color:#2f2a1e;">
                        {{ $ev->nama_pameran }}
                    </h3>

                    <p style="margin:0; color:#6b6b6b; font-size:0.95rem;">
                        {{ \Carbon\Carbon::parse($ev->tanggal_mulai)->format('d M Y') }}
                        —
                        {{ \Carbon\Carbon::parse($ev->tanggal_selesai)->format('d M Y') }}
                    </p>

                    <p style="margin:6px 0 0 0; color:#8b7b5a; font-size:0.9rem;">
                        {{ $ev->lokasi_pameran }}
                    </p>
                </div>
            </div>



            {{-- ACTION AREA --}}
            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-top:12px;">

                {{-- COUNT KARYA --}}
                <div style="color:#6b6b6b; font-size:0.9rem;">
                    Karya:
                    <strong style="color:#2f2a1e;">
                        {{ $karyaCounts[$ev->id_pameran] ?? 0 }}
                    </strong>
                </div>


                {{-- BUTTON GROUP --}}
                <div style="display:flex; gap:10px;">

                    {{-- KELOLA KARYA --}}
                    <a href="{{ route('admin.event.artworks', $ev->id_pameran) }}"
                       style="
                            padding:8px 12px;
                            border-radius:8px;
                            text-decoration:none;
                            background:rgba(212,175,55,0.08);
                            border:1px solid rgba(212,175,55,0.35);
                            color:#b0892b;
                            font-weight:600;">
                        Kelola
                    </a>

                    {{-- EDIT --}}
                    <a href="{{ route('admin.event.edit', $ev->id_pameran) }}"
                       style="
                            padding:8px 12px;
                            border-radius:8px;
                            text-decoration:none;
                            background:#d4af37;
                            color:#fff;
                            font-weight:600;">
                        Edit
                    </a>

                    {{-- DELETE --}}
                    <form action="{{ route('admin.event.destroy', $ev->id_pameran) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus event ini?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            style="
                                padding:8px 12px;
                                border-radius:8px;
                                border:none;
                                background:#b82121;
                                color:#fff;
                                font-weight:600;
                                cursor:pointer;">
                            Hapus
                        </button>
                    </form>

                </div>
            </div>

        </div>
        @endforeach
    </div>


    @else
    <p style="color:#6b6b6b;">Tidak ada pameran. Silakan tambahkan pameran baru.</p>
    @endif

</section>
@endsection
