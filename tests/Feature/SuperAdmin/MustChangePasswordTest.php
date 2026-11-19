<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MustChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): User
    {
        return User::create([
            'name' => 'Admin Password',
            'email' => 'admin-password@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'is_super_admin' => true,
        ]);
    }

    public function test_guru_yang_dibuat_super_admin_wajib_ganti_password(): void
    {
        $this->actingAs($this->superAdmin(), 'lms');

        $this->post(route('admin.guru.store'), [
            'name' => 'Guru Uji Coba',
            'email' => 'guru-uji@example.com',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'guru-uji@example.com',
            'must_change_password' => 1,
        ]);
    }

    public function test_reset_password_mengaktifkan_kembali_flag_wajib_ganti(): void
    {
        $this->actingAs($this->superAdmin(), 'lms');

        $guru = User::create([
            'name' => 'Guru Reset',
            'email' => 'guru-reset@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'is_super_admin' => false,
            'must_change_password' => false,
        ]);

        $this->patch(route('admin.guru.reset-password', $guru))->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $guru->id,
            'must_change_password' => 1,
        ]);
    }
}
