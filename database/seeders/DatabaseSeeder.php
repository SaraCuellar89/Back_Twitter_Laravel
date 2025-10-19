<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            TipoDocumentoSeeder::class
        ]);

        User::factory()->create([
            'name' => 'Andres Echeverria',
            'age' => 22,
            'tipo_documento' => 3,
            'documento' => '5314051',
            'email' => 'andres@gmail.com',
            'password' => Hash::make('123456')
        ]);

        $this->call([
            PostSeeder::class,
        ]);
    }
}
