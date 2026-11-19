@extends('admin.layouts.app')

@section('title', 'Hasil Import Guru')

@section('content')
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.guru.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Hasil Import Guru</h4>
            <small class="text-muted">Ringkasan penugasan mengajar &amp; wali kelas dari file Excel.</small>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white fw-semibold">
                    Berhasil ({{ count($hasil) }})
                </div>
                <ul class="list-group list-group-flush small">
                    @forelse ($hasil as $h)
                        <li class="list-group-item">{{ $h }}</li>
                    @empty
                        <li class="list-group-item text-muted">Tidak ada.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-danger text-white fw-semibold">
                    Gagal / dilewati ({{ count($errors) }})
                </div>
                <ul class="list-group list-group-flush small">
                    @forelse ($errors as $e)
                        <li class="list-group-item">{{ $e }}</li>
                    @empty
                        <li class="list-group-item text-muted">Tidak ada.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <a href="{{ route('admin.guru.index') }}" class="btn btn-dark">
            <i class="bi bi-people me-1"></i> Lihat guru
        </a>
        <a href="{{ route('admin.guru.import.form') }}" class="btn btn-outline-primary">
            <i class="bi bi-cloud-upload me-1"></i> Import lagi
        </a>
    </div>
@endsection
