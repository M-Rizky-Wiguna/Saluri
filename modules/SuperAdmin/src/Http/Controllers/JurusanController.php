<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /** Daftar jurusan, bisa difilter lewat query string q. */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $jurusan = Jurusan::with('kepalaJurusan')
            ->withCount(['kelas', 'pendaftar'])
            ->when($q, fn ($query) => $query->where(fn ($sub) => $sub
                ->where('kode', 'like', "%{$q}%")
                ->orWhere('nama', 'like', "%{$q}%")))
            ->orderBy('kode')
            ->paginate(15)
            ->withQueryString();

        return view('admin.jurusan.index', compact('jurusan', 'q'));
    }

    public function create()
    {
        $kepalaList = $this->kepalaList();

        return view('admin.jurusan.create', compact('kepalaList'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Jurusan::create($data);

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan "' . $data['nama'] . '" berhasil ditambahkan.');
    }

    public function edit(Jurusan $jurusan)
    {
        $kepalaList = $this->kepalaList();

        return view('admin.jurusan.edit', compact('jurusan', 'kepalaList'));
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $data = $this->validateData($request, $jurusan->id);

        $jurusan->update($data);

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan "' . $data['nama'] . '" berhasil diperbarui.');
    }

    public function destroy(Jurusan $jurusan)
    {
        if ($jurusan->kelas()->exists()) {
            return back()->with('error', 'Jurusan "' . $jurusan->nama . '" masih punya kelas. Pindahkan kelasnya dulu.');
        }

        if ($jurusan->pendaftar()->exists()) {
            return back()->with('error', 'Jurusan "' . $jurusan->nama . '" masih punya pendaftar SPMB.');
        }

        $nama = $jurusan->nama;
        $jurusan->delete();

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan "' . $nama . '" berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:10', 'unique:jurusan,kode' . ($ignoreId ? ',' . $ignoreId : '')],
            'nama' => ['required', 'string', 'max:255'],
            'kepala_jurusan_id' => ['nullable', 'exists:users,id'],
            'deskripsi' => ['nullable', 'string'],
            'kuota' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['kuota'] = (int) ($data['kuota'] ?? 0);

        return $data;
    }

    private function kepalaList()
    {
        return User::where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
