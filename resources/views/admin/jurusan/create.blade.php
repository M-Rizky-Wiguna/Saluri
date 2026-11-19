@extends('admin.layouts.app')

@section('title', 'Tambah Jurusan')

@section('content')
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.jurusan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Tambah Jurusan</h4>
            <small class="text-muted">Data jurusan dipakai Siswa, Kelas, Guru, dan SPMB.</small>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.jurusan.store') }}">
                        @csrf
                        @include('admin.jurusan._form')

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i> Simpan
                            </button>
                            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-outline-secondary px-4">
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
                        <li class="mb-2">Kode jurusan harus unik — dipakai sebagai identitas di modul SPMB.</li>
                        <li class="mb-2">Jurusan yang masih dipakai kelas atau pendaftar tidak bisa dihapus.</li>
                        <li class="mb-2">Kepala jurusan bisa diubah lalu dari menu Kelola Guru.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
