<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Modules\SuperAdmin\Http\Controllers\JurusanController;
use Tests\TestCase;

class JurusanCrudTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): User
    {
        return User::create([
            'name' => 'Admin Jurusan',
            'email' => 'admin-jurusan@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'is_super_admin' => true,
        ]);
    }

    public function test_super_admin_bisa_melihat_dan_menambah_jurusan(): void
    {
        $this->actingAs($this->superAdmin(), 'lms');

        $this->get(route('admin.jurusan.index'))->assertOk();

        $this->post(route('admin.jurusan.store'), [
            'kode' => 'TEST',
            'nama' => 'Jurusan Uji Coba',
            'kuota' => 20,
        ])->assertRedirect(route('admin.jurusan.index'));

        $this->assertDatabaseHas('jurusan', [
            'kode' => 'TEST',
            'nama' => 'Jurusan Uji Coba',
            'kuota' => 20,
        ]);
    }

    public function test_jurusan_bisa_diedit_dan_dihapus(): void
    {
        $this->actingAs($this->superAdmin(), 'lms');

        $this->post(route('admin.jurusan.store'), [
            'kode' => 'EDIT',
            'nama' => 'Jurusan Lama',
        ]);

        $jurusan = Jurusan::where('kode', 'EDIT')->firstOrFail();

        $this->put(route('admin.jurusan.update', $jurusan), [
            'kode' => 'EDIT',
            'nama' => 'Jurusan Baru',
        ])->assertRedirect(route('admin.jurusan.index'));

        $this->assertDatabaseHas('jurusan', ['kode' => 'EDIT', 'nama' => 'Jurusan Baru']);

        $this->delete(route('admin.jurusan.destroy', $jurusan))
            ->assertRedirect(route('admin.jurusan.index'));

        $this->assertDatabaseMissing('jurusan', ['kode' => 'EDIT']);
    }

    public function test_route_jurusan_memakai_controller_modul_super_admin(): void
    {
        $route = Route::getRoutes()->getByName('admin.jurusan.index');

        $this->assertNotNull($route);
        $this->assertSame(JurusanController::class, $route->getControllerClass());
    }
}
