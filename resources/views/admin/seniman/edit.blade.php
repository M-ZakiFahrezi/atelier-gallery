@extends('layouts.admin')

@section('title', 'Edit Seniman')

@push('page-styles')
    <style>
        .edit-page {
            display: flex;
            justify-content: center;
            padding: 40px 20px;
        }

        .edit-box {
            width: 100%;
            max-width: 700px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: 0.3s ease;
        }

        body.dark-mode .edit-box {
            background: rgba(25, 25, 25, 0.8);
            border-color: rgba(255, 215, 0, 0.35);
            color: #f6e7b0;
        }

        .edit-box h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.8rem;
            color: #b89e3f;
            text-align: center;
            margin-bottom: 25px;
        }

        .edit-form label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .edit-form input[type="text"],
        .edit-form input[type="date"],
        .edit-form textarea,
        .edit-form input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 1rem;
            transition: 0.3s ease;
            background: rgba(255, 255, 255, 0.7);
        }

        .edit-form input:focus,
        .edit-form textarea:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            outline: none;
        }

        .edit-form img {
            display: block;
            margin: 12px 0 16px;
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid rgba(212, 175, 55, 0.4);
            background: #fff;
        }

        .edit-form button {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #d4af37, #b89127);
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .edit-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(212, 175, 55, 0.4);
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #b89e3f;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .back-link:hover {
            text-decoration: underline;
            color: #a88e2d;
        }

        body.dark-mode .edit-form input,
        body.dark-mode .edit-form textarea {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 215, 0, 0.3);
            color: #f5e9c3;
        }

        @media(max-width: 600px) {
            .edit-box {
                padding: 25px;
            }

            .edit-box h2 {
                font-size: 1.6rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="edit-page">
        <div class="edit-box">
            <h2>✏️ Edit Seniman</h2>

            <form action="{{ route('admin.seniman.update', $seniman->id_seniman) }}" method="POST" enctype="multipart/form-data"
                class="edit-form">
                @csrf
                @method('PUT')

                <label>Nama Seniman:</label>
                <input type="text" name="nama_seniman" value="{{ old('nama_seniman', $seniman->nama_seniman) }}" required>

                <label>Asal:</label>
                <input type="text" name="asal" value="{{ old('asal', $seniman->asal) }}">

                <label>Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $seniman->tanggal_lahir) }}">

                <label>Bio:</label>
                <textarea name="bio" rows="4">{{ old('bio', $seniman->bio) }}</textarea>

                <label>Foto Saat Ini:</label>
                @if ($seniman->gambar && file_exists(public_path('images/asset/seniman/' . $seniman->gambar)))
                    <img src="{{ asset('images/asset/seniman/' . $seniman->gambar) }}" alt="Foto Seniman">
                @else
                    <em>Tidak ada foto</em><br>
                @endif

                <label>Ganti Foto:</label>
                <input type="file" name="gambar" accept="image/*">

                <button type="submit">💾 Simpan Perubahan</button>
            </form>

            <a href="{{ route('admin.seniman.index') }}" class="back-link">← Kembali ke daftar seniman</a>
        </div>
    </div>
@endsection
