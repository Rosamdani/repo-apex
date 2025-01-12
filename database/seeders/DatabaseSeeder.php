<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BatchTryouts;
use App\Models\KompetensiTryouts;
use App\Models\BidangTryouts;
use App\Models\SoalTryout;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = Faker::create('id_ID'); // Inisialisasi faker
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat admin user
        User::firstOrCreate([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@apexmedika.com',
            'password' => bcrypt('Ap3xM3dik@4dMi11'),
        ]);
        $admin = User::where('username', 'admin')->first();
        $role = Role::firstOrCreate(['name' => 'admin']);
        $admin->assignRole('admin');
        $role = Role::firstOrCreate(['name' => 'user']);
    }
}
