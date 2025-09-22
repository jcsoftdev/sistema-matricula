<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => "Administrador",
                'password' => '$2y$10$79lFQuJfbngvyHBnNGszVeS1TDJq/RqgM2Y0wFvax/i113v2aBsAu', // admin
                'rol' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['username' => 'secretario'],
            [
                'name' => "Secretaría",
                'password' => '$2y$10$v/ij8e6874W/0BrSGszW6O754Ea4dGkFYxs0d2xz4jLRR/6GRaOGa', // password
                'rol' => 'secretario'
            ]
        );

        User::updateOrCreate(
            ['username' => 'apoderado'],
            [
                'name' => "Apoderado",
                'password' => '$2y$10$v/ij8e6874W/0BrSGszW6O754Ea4dGkFYxs0d2xz4jLRR/6GRaOGa', // password
                'rol' => 'padre'
            ]
        );
        // Crear 10 estudiantes y 5 apoderados aletorios
        // \App\Models\Estudiante::factory(10)->create();
        // \App\Models\Apoderado::factory(5)->create();

    }
}
