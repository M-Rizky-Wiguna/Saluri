<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PenggunaCreateTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): User
    {
        $admin = User::where('email', 'admin@smk.sch.id')->firstOrFail();
        $admin->update(['is_active' => true, 'is_super_admin' => true]);

        return $admin;
    }

    public function test_super_admin_bisa_membuat_pengguna_super_admin_baru(): void
    {
        $this->actingAs($this->superAdmin(), 'lms');

        $this->get(route('admin.pengguna.create'))->assertOk();

        $response = $this->post(route('admin.pengguna.store'), [
            'name' => 'luthfy',
            'email' => 'luthfy@smk.sch.id',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_super_admin' => 1,
        ]);

        $response->assertSessionHasNoErrors();

        $luthfy = User::where('email', 'luthfy@smk.sch.id')->firstOrFail();

        $this->assertDatabaseHas('users', [
            'id' => $luthfy->id,
            'name' => 'luthfy',
            'is_active' => 1,
            'is_super_admin' => 1,
            'must_change_password' => 1,
        ]);
        $this->assertTrue(Hash::check('password', $luthfy->password));
        $response->assertRedirect(route('admin.pengguna.akses', $luthfy));
    }

    public function test_email_duplikat_ditolak(): void
    {
        $this->actingAs($this->superAdmin(), 'lms');

        $this->post(route('admin.pengguna.store'), [
            'name' => 'Duplikat',
            'email' => 'admin@smk.sch.id',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertSame(1, User::where('email', 'admin@smk.sch.id')->count());
    }

    public function test_user_biasa_tidak_bisa_membuka_form_tambah_pengguna(): void
    {
        $biasa = User::create([
            'name' => 'User Biasa',
            'email' => 'biasa@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'is_super_admin' => false,
        ]);

        $this->actingAs($biasa, 'lms')
            ->get(route('admin.pengguna.create'))
            ->assertForbidden();
    }
}
