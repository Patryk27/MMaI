<?php

use App\Models\User;

final class UsersSeeder
    extends Seeder {

    /**
     * @throws Throwable
     * @return void
     */
    public function run(): void {
        User::create([
            'login' => 'admin',
            'name' => 'Administrator',
            'password' => bcrypt('admin'),
        ]);
    }

}