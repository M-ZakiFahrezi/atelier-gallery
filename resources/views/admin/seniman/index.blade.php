@extends('layouts.admin')

@section('title', 'Kelola Seniman')

@push('page-styles')
<style>
    .seniman-page {
        display: flex;
        justify-content: center;
        padding: 40px 20px;
    }

    .seniman-box {
        width: 100%;
        max-width: 1200px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: 0.3s;
        overflow: hidden;
    }

    body.dark-mode .seniman-box {
        background: rgba(25, 25, 25, 0.8);
        border-color: rgba(255, 215, 0, 0.35);
        color: #f6e7b0;
    }

    .seniman-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .seniman-actions h2 {
        font-family: 'Cinzel', serif;
        font-size: 1.9rem;
        color: #b89e3f;
        margin: 0;
    }

    .seniman-actions a {
        background: linear-gradient(135deg, #d4af37, #b89127);
        color: #fff;
        padding: 10px 18px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .seniman-actions a:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
    }

    /* ✅ Membungkus tabel agar tidak overflow di layar kecil */
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        border-radius: 14px;
    }

    .seniman-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .seniman-table th {
        background: #d4af37;
        color: #fff;
        text-align: center;
        padding: 12px 15px;
        font-weight: 600;
        white-space: nowrap;
    }

    .seniman-table td {
        background: rgba(255, 255, 255, 0.6);
        text-align: center;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        vertical-align: middle;
        white-space: nowrap;
    }

    body.dark-mode .seniman-table td {
        background: rgba(40, 40, 40, 0.6);
        color: #f6e7b0;
        border-color: rgba(255, 215, 0, 0.15);
    }

    .seniman-table tr:hover td {
        background: rgba(212, 175, 55, 0.1);
    }

    .seniman-table img {
        width: 85px;
        height: 85px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid rgba(212, 175, 55, 0.4);
        background: #fff;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .action-btn {
        display: inline-block;
        padding: 7px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .action-btn.edit {
        background: #2980b9;
        color: #fff;
    }

    .action-btn.edit:hover {
        background: #3498db;
    }

    .action-btn.delete {
        background: #c0392b;
        color: #fff;
        border: none;
        cursor: pointer;
    }

    .action-btn.delete:hover {
        background: #e74c3c;
    }

    .empty-row {
        text-align: center;
        font-style: italic;
        color: #555;
        padding: 20px 0;
    }

    body.dark-mode .empty-row {
        color: #f5e9c3;
    }

    @media(max-width: 900px) {
        .seniman-actions {
            flex-direction: column;
            text-align: center;
        }

        .seniman-actions h2 {
            font-size: 1.6rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 6px;
        }

        .action-btn {
            width: 100%;
        }

        .seniman-table img {
            width: 65px;
            height: 65px;
        }
    }
</style>
@endpush

@section('content')
<div class="seniman-page">
    <div class="seniman-box">
        <div class="seniman-actions">
            <h2>🎨 Daftar Seniman</h2>
            <a href="{{ route('admin.seniman.create') }}">➕ Tambah Seniman</a>
        </div>

        <div class="table-wrapper">
            <table class="seniman-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Asal</th>
                        <th>Tanggal Lahir</th>
                        <th>Bio</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($seniman as $index => $s)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $s->nama_seniman }}</td>
                            <td>{{ $s->asal }}</td>
                            <td>{{ $s->tanggal_lahir }}</td>
                            <td>{{ Str::limit($s->bio, 50) }}</td>
                            <td>
                                @if ($s->gambar && file_exists(public_path('images/asset/seniman/' . $s->gambar)))
                                    <img src="{{ asset('images/asset/seniman/' . $s->gambar) }}" alt="Foto Seniman">
                                @else
                                    <em>Tidak ada</em>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.seniman.edit', $s->id_seniman) }}" class="action-btn edit">✏️ Edit</a>
                                    <form action="{{ route('admin.seniman.destroy', $s->id_seniman) }}" method="POST" onsubmit="return confirm('Yakin hapus seniman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete">🗑 Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-row">Belum ada data seniman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
