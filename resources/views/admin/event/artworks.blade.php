@extends('layouts.admin')

@section('title', 'Kelola Karya - ' . $event->nama_pameran)

@section('content')
<section style="max-width:1000px; margin:auto;">

    <h2 style="font-family:'Cinzel', serif; color:#d4af37; font-size:1.6rem;">
        Kelola Karya • {{ $event->nama_pameran }}
    </h2>

    {{-- Alert --}}
    @if(session('success'))
        <div style="background:#e8f7e2; padding:12px 16px; border-radius:8px; margin:15px 0; color:#2c6c2c;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Tambah --}}
    <div style="margin-top:25px; background:#fff; padding:22px; border-radius:12px; box-shadow:0 6px 16px rgba(0,0,0,0.05);">

        <h3 style="color:#444; margin-bottom:16px; font-size:1.2rem; font-weight:600;">
            Tambahkan Karya ke Event
        </h3>

        <form action="{{ route('admin.event.artworks.add', $event->id_pameran) }}" method="POST">
            @csrf

            <div style="display:flex; gap:20px; flex-wrap:wrap;">

                {{-- Pilih Karya --}}
                <div style="flex:1;">
                    <label style="font-weight:600;">Pilih Karya</label>
                    <select name="id_karya" required
                            style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;">
                        <option value="">-- Pilih Karya --</option>
                        @foreach($karya as $k)
                            <option value="{{ $k->id_karya }}">{{ $k->judul_karya }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div style="flex:1;">
                    <label style="font-weight:600;">Status Display</label>
                    <select name="status_display" required
                            style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;">
                        <option value="dipajang">Dipajang</option>
                        <option value="disimpan">Disimpan</option>
                    </select>
                </div>

                {{-- Posisi --}}
                <div style="flex:1;">
                    <label style="font-weight:600;">Posisi Pajang (Opsional)</label>
                    <input type="text" name="posisi_pajang"
                           style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;">
                </div>
            </div>

            {{-- Catatan --}}
            <div style="margin-top:15px;">
                <label style="font-weight:600;">Catatan Kuratorial (Opsional)</label>
                <textarea name="catatan_kuratorial"
                          style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; min-height:80px;"></textarea>
            </div>

            <div style="margin-top:18px; text-align:right;">
                <button type="submit"
                        style="padding:10px 16px; background:#d4af37; color:#fff; border:none; border-radius:8px; font-weight:600;">
                    Tambahkan Karya
                </button>
            </div>

        </form>
    </div>

    {{-- List Karya --}}
    <h3 style="margin-top:35px; font-size:1.25rem; color:#333; font-weight:600;">Daftar Karya dalam Event</h3>

    <table style="width:100%; margin-top:15px; border-collapse:collapse; background:white; border-radius:10px; overflow:hidden;">
        <thead style="background:#d4af3733;">
            <tr>
                <th style="padding:12px;">Judul</th>
                <th style="padding:12px;">Seniman</th>
                <th style="padding:12px;">Status</th>
                <th style="padding:12px;">Posisi</th>
                <th style="padding:12px;">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($karyaEvent as $ke)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:12px;">{{ $ke->judul_karya }}</td>
                    <td style="padding:12px;">{{ $ke->nama_seniman }}</td>
                    <td style="padding:12px;">
                        <span style="color:{{ $ke->status_display == 'dipajang' ? '#1a7a1a' : '#555' }};">
                            {{ ucfirst($ke->status_display) }}
                        </span>
                    </td>
                    <td style="padding:12px;">{{ $ke->posisi_pajang ?? '-' }}</td>

                    <td style="padding:12px;">
                        <form action="{{ route('admin.event.artworks.remove', $ke->id_karya_pameran) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus karya ini dari event?');">
                            @csrf
                            @method('DELETE')
                            <button style="padding:6px 12px; background:#c93030; color:#fff; border:none; border-radius:6px;">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="padding:14px; text-align:center;">Belum ada karya.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:25px;">
        <a href="{{ route('admin.event.index') }}"
           style="padding:10px 16px; background:#888; color:#fff; border:none; border-radius:8px; text-decoration:none;">
            Kembali
        </a>
    </div>

</section>
@endsection
