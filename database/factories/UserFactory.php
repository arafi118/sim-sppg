<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'level_id' => rand(1, 6),
            'nik' => fake('id_ID')->unique()->numerify('################'),
            'nama' => fake('id_ID')->name(),
            'tanggal_lahir' => fake('id_ID')->date('Y-m-d', '-20 years'),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'alamat' => fake('id_ID')->address(),
            'telpon' => fake('id_ID')->phoneNumber(),
            'id_sidik_jari' => Str::random(20),
            'tanggal_masuk' => fake()->date('Y-m-d'),
            'gaji' => '100000',
            'status' => 'aktif',
            'username' => fake('id_ID')->userName(),
            'password' => Hash::make('password'),
        ];
    }
}
