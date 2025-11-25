@extends('layouts.admin')

@section('title', 'Tambah Karya Seni')

@push('page-styles')
    <style>
        .karya-page {
            display: flex;
            justify-content: center;
            padding: 40px 20px;
        }

        .karya-box {
            width: 100%;
            max-width: 700px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 175, 55, 0.25);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        body.dark-mode .karya-box {
            background: rgba(0, 0, 0, 0.8);
            border-color: rgba(255, 215, 0, 0.3);
            color: #f5e9c3;
        }

        .karya-box h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.8rem;
            color: #b89e3f;
            margin-bottom: 20px;
            text-align: center;
        }

        .karya-form label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .karya-form input[type="text"],
        .karya-form input[type="number"],
        .karya-form select,
        .karya-form textarea,
        .karya-form input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 1rem;
            transition: border 0.3s ease;
        }

        .karya-form input:focus,
        .karya-form textarea:focus,
        .karya-form select:focus {
            border-color: #d4af37;
            outline: none;
        }

        .karya-form button {
            background: linear-gradient(135deg, #d4af37, #b89127);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: block;
            width: 100%;
        }

        .karya-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }

        body.dark-mode .karya-form input,
        body.dark-mode .karya-form select,
        body.dark-mode .karya-form textarea {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 215, 0, 0.3);
            color: #f5e9c3;
        }

        .error-box {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.3);
            color: #b00000;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="karya-page">
        <div class="karya-box">
            <h2>Tambah Karya Seni</h2>

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.karya.store') }}" method="POST" enctype="multipart/form-data" class="karya-form">
                @csrf

                <label>Judul Karya:</label>
                <input type="text" name="judul_karya" value="{{ old('judul_karya') }}" required>

                <label>Tahun Pembuatan:</label>
                <input type="number" name="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}" required>

                <label>Deskripsi:</label>
                <textarea name="deskripsi" rows="4">{{ old('deskripsi') }}</textarea>

                <label>Harga:</label>
                <input type="number" step="0.01" name="harga" value="{{ old('harga') }}" required>

                <label>Status:</label>
                <select name="status_karya" required>
                    <option value="tersedia">Tersedia</option>
                    <option value="terjual">Terjual</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="disimpan">Disimpan</option>
                </select>

                <label>Seniman:</label>
                <select name="id_seniman" required>
                    @foreach ($seniman as $s)
                        <option value="{{ $s->id_seniman }}">{{ $s->nama_seniman }}</option>
                    @endforeach
                </select>

                <label for="gambar">Upload Gambar Karya</label>
                <input type="file" name="gambar" id="gambar" class="form-control">


                <button type="submit">💾 Simpan Karya Seni</button>
            </form>
        </div>
    </div>
@endsection
