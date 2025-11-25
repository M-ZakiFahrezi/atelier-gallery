@extends('layouts.admin')

@section('title', 'Kelola Karya Seni')

@push('page-styles')
    <style>
        .karya-page {
            display: flex;
            justify-content: center;
            padding: 40px 20px;
        }

        .karya-box {
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

        body.dark-mode .karya-box {
            background: rgba(25, 25, 25, 0.8);
            border-color: rgba(255, 215, 0, 0.35);
            color: #f6e7b0;
        }

        .karya-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .karya-actions h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.9rem;
            color: #b89e3f;
            margin: 0;
        }

        .karya-actions a {
            background: linear-gradient(135deg, #d4af37, #b89127);
            color: #fff;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .karya-actions a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
        }

        /* ✅ Bungkus tabel */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            border-radius: 14px;
        }

        .karya-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        .karya-table th {
            background: #d4af37;
            color: #fff;
            text-align: center;
            padding: 12px 15px;
            font-weight: 600;
            white-space: nowrap;
        }

        .karya-table td {
            background: rgba(255, 255, 255, 0.6);
            text-align: center;
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            vertical-align: middle;
            white-space: nowrap;
        }

        body.dark-mode .karya-table td {
            background: rgba(40, 40, 40, 0.6);
            color: #f6e7b0;
            border-color: rgba(255, 215, 0, 0.15);
        }

        .karya-table tr:hover td {
            background: rgba(212, 175, 55, 0.1);
        }

        .karya-table img {
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
            .karya-actions {
                flex-direction: column;
                text-align: center;
            }

            .karya-actions h2 {
                font-size: 1.6rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 6px;
            }

            .action-btn {
                width: 100%;
            }

            .karya-table img {
                width: 65px;
                height: 65px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="karya-page">
        <div class="karya-box">
            <div class="karya-actions">
                <h2>🎨 Daftar Karya Seni</h2>
                <a href="{{ route('admin.karya.create') }}">➕ Tambah Karya</a>
            </div>

            <div class="table-wrapper">
                <table class="karya-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Tahun</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($karyaSeni as $index => $karya)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $karya->judul_karya }}</td>
                                <td>{{ $karya->tahun_pembuatan }}</td>
                                <td>Rp {{ number_format($karya->harga, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($karya->status_karya) }}</td>
                                <td>
                                    @if ($karya->gambar && file_exists(public_path('images/asset/karyaSeni/' . $karya->gambar)))
                                        <img src="{{ asset('images/asset/karyaSeni/' . $karya->gambar) }}"
                                            alt="{{ $karya->judul_karya }}" width="120" height="auto">
                                    @else
                                        <em>Tidak ada</em>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.karya.edit', $karya->id_karya) }}"
                                            class="action-btn edit">✏️ Edit</a>
                                        <form action="{{ route('admin.karya.destroy', $karya->id_karya) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus karya ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete">🗑 Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-row">Belum ada data karya seni</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
