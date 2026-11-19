<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    /** Daftar semua pengguna + badge role di tiap modul. */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $pengguna = User::with('roles.module')
            ->when($q, fn($query) => $query->where(fn($sub) => $sub
                ->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pengguna.index', compact('pengguna', 'q'));
    }

    public function create()
    {
        return view('admin.pengguna.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_super_admin' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
            'is_super_admin' => $request->boolean('is_super_admin'),
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('admin.pengguna.akses', $user)
            ->with('status', 'Pengguna ' . $user->name . ' berhasil dibuat. Atur role modulnya di bawah.');
    }

    /** Form kelola akses (module+role) untuk 1 user. */
    public function akses(User $user)
    {
        $modules = Module::with('roles')->orderBy('nama')->get();
        $roleIdsUser = $user->roles()->pluck('roles.id')->toArray();
        $sisaSuperAdmin = User::where('is_super_admin', true)->where('is_active', true)->count();

        return view('admin.pengguna.akses', compact('user', 'modules', 'roleIdsUser', 'sisaSuperAdmin'));
    }

    /** Simpan perubahan akses — sync total (checklist yang tercentang = akses akhir). */
    public function updateAkses(Request $request, User $user)
    {
        $superAdmin = $request->attributes->get('superAdminUser');

        $data = $request->validate([
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
            'is_super_admin' => ['nullable', 'boolean'],
        ]);

        $roleIds = $data['role_ids'] ?? [];

        // Jangan biarkan super admin aktif terakhir dicabut — kalau tidak,
        // panel bisa terkunci definitif (termasuk oleh admin lain).
        $sisaSuperAdmin = User::where('is_super_admin', true)->where('is_active', true)->count();
        if ($user->is_super_admin && $user->is_active && ! $request->boolean('is_super_admin') && $sisaSuperAdmin <= 1) {
            return back()->withErrors([
                'is_super_admin' => 'Tidak bisa mencabut status Super Admin: ini satu-satunya Super Admin yang aktif.',
            ]);
        }

        // sync() otomatis nambah role baru yang dicentang & hapus yang
        // di-uncheck, sekalian isi kolom pivot assigned_by/assigned_at.
        $syncData = collect($roleIds)->mapWithKeys(fn($roleId) => [
            $roleId => [
                'assigned_by' => $superAdmin->id,
                'assigned_at' => now(),
            ],
        ])->toArray();

        $user->roles()->sync($syncData);

        // Super admin nggak boleh cabut status super admin dirinya
        // sendiri lewat form ini (biar gak ke-lock out dari panel).
        if ($user->id !== $superAdmin->id) {
            $user->update(['is_super_admin' => $request->boolean('is_super_admin')]);
        }

        return redirect()
            ->route('admin.pengguna.akses', $user)
            ->with('status', 'Akses ' . $user->name . ' berhasil diperbarui.');
    }
}
