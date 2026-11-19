@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.pengguna.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Tambah Pengguna</h4>
            <small class="text-muted">Buat akun baru dan tentukan apakah ia bisa mengakses Panel Super Admin.</small>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.pengguna.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label small fw-semibold">Nama</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" autocomplete="name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" autocomplete="email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label small fw-semibold">Password</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimal 8 karakter. User akan diminta menggantinya saat login pertama.</div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label small fw-semibold">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" autocomplete="new-password" required>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_super_admin"
                                name="is_super_admin" value="1" @checked(old('is_super_admin'))>
                            <label class="form-check-label" for="is_super_admin">
                                Beri akses Super Admin
                            </label>
                            <div class="form-text">Super Admin bisa login dari PKL/SPMB/LMS lalu masuk ke panel <code>/admin</code>.</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i> Simpan
                            </button>
                            <a href="{{ route('admin.pengguna.index') }}" class="btn btn-outline-secondary px-4">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mt-4 mt-lg-0">
            <div class="card border-0 bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-lightbulb-fill text-warning me-1"></i> Panduan
                    </h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li class="mb-2">Setelah disimpan, halaman Kelola Akses akan terbuka untuk memilih role PKL/SPMB/LMS.</li>
                        <li class="mb-2">Email harus unik — dipakai sebagai identitas login di ketiga modul.</li>
                        <li class="mb-2">Status Super Admin bisa dicabut lalu dari halaman Kelola Akses.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
