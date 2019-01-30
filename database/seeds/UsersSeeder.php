<?php

use App\Users\Models\User;

final class UsersSeeder extends Seeder {

    /**
     * @return void
     * @throws Throwable
     */
    public function run(): void {
        User::create([
            'login' => 'admin',
            'name' => 'Administrator',
            'password' => bcrypt('admin'),
        ]);
    }
}
