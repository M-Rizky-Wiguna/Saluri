<div class="mb-3">
    <label class="form-label fw-semibold">Kode <span class="text-danger">*</span></label>
    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror"
        value="{{ old('kode', $jurusan->kode ?? '') }}" placeholder="Contoh: PPLG" maxlength="10">
    @error('kode')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">Kode unik, maksimal 10 karakter.</div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Nama Jurusan <span class="text-danger">*</span></label>
    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
        value="{{ old('nama', $jurusan->nama ?? '') }}" placeholder="Contoh: Pengembangan Perangkat Lunak dan Gim">
    @error('nama')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Kepala Jurusan</label>
    <select name="kepala_jurusan_id" class="form-select @error('kepala_jurusan_id') is-invalid @enderror">
        <option value="">— Belum ditentukan —</option>
        @foreach ($kepalaList as $user)
            <option value="{{ $user->id }}"
                @selected(old('kepala_jurusan_id', $jurusan->kepala_jurusan_id ?? null) == $user->id)>
                {{ $user->name }} ({{ $user->email }})
            </option>
        @endforeach
    </select>
    @error('kepala_jurusan_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Kuota Pendaftar</label>
    <input type="number" name="kuota" min="0" class="form-control @error('kuota') is-invalid @enderror"
        value="{{ old('kuota', $jurusan->kuota ?? 0) }}">
    @error('kuota')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">Isi 0 kalau kuota belum dibatasi. Dipakai modul SPMB.</div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">Deskripsi</label>
    <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror"
        placeholder="Deskripsi singkat jurusan…">{{ old('deskripsi', $jurusan->deskripsi ?? '') }}</textarea>
    @error('deskripsi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
