@extends('admin.layouts.app')

@section('title', 'Kelola Jurusan')

@section('content')
    <div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Kelola Jurusan</h4>
            <p class="text-muted small mb-0">Total {{ $jurusan->total() }} jurusan terdaftar</p>
        </div>
        <a href="{{ route('admin.jurusan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jurusan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.jurusan.index') }}" class="d-flex gap-2">
                <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm"
                    placeholder="Cari kode atau nama jurusan…">
                <button type="submit" class="btn btn-sm btn-outline-secondary">Cari</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Jurusan</th>
                        <th>Kepala Jurusan</th>
                        <th class="text-center">Kuota</th>
                        <th class="text-center">Kelas</th>
                        <th class="text-center">Pendaftar</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jurusan as $j)
                        <tr>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $j->kode }}</span></td>
                            <td class="fw-semibold">{{ $j->nama }}</td>
                            <td class="small text-muted">{{ $j->kepalaJurusan?->name ?? '—' }}</td>
                            <td class="text-center">{{ $j->kuota }}</td>
                            <td class="text-center">{{ $j->kelas_count }}</td>
                            <td class="text-center">{{ $j->pendaftar_count }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('admin.jurusan.edit', $j) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.jurusan.destroy', $j) }}"
                                        onsubmit="return confirm('Hapus jurusan {{ $j->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada jurusan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($jurusan->hasPages())
            <div class="p-3 border-top">{{ $jurusan->links() }}</div>
        @endif
    </div>
@endsection
