<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminProtectionTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $email, bool $superAdmin = false): User
    {
        return User::create([
            'name' => $email,
            'email' => $email,
            'password' => bcrypt('password'),
            'is_active' => true,
            'is_super_admin' => $superAdmin,
        ]);
    }

    private function targetSuperAdmin(): User
    {
        $admin = $this->user('admin-utama@example.com', true);

        User::where('email', 'admin@smk.sch.id')->update(['is_super_admin' => false]);

        return $admin;
    }

    public function test_super_admin_terakhir_tidak_bisa_dicabut(): void
    {
        $target = $this->targetSuperAdmin();
        $pengawas = $this->user('pengawas@example.com', true);
        $this->actingAs($pengawas, 'lms');

        User::where('id', $pengawas->id)->update(['is_super_admin' => false]);

        $this->put(route('admin.pengguna.akses.update', $target), ['role_ids' => []])
            ->assertSessionHasErrors('is_super_admin');

        $this->assertDatabaseHas('users', ['id' => $target->id, 'is_super_admin' => 1]);
        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_super_admin_bisa_dicabut_bila_masih_ada_super_admin_lain(): void
    {
        $target = $this->targetSuperAdmin();
        $pengawas = $this->user('pengawas@example.com', true);
        $this->actingAs($pengawas, 'lms');

        $cadangan = $this->user('admin-cadangan@example.com');

        $this->put(route('admin.pengguna.akses.update', $cadangan), [
            'role_ids' => [],
            'is_super_admin' => 1,
        ])->assertSessionHasNoErrors();

        $this->put(route('admin.pengguna.akses.update', $target), ['role_ids' => []])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['id' => $target->id, 'is_super_admin' => 0]);
        $this->assertDatabaseHas('users', ['id' => $cadangan->id, 'is_super_admin' => 1]);
    }
}
