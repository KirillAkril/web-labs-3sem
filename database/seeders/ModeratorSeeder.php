<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class ModeratorSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('name', 'moderator')->firstOrFail();

        $user = User::orderBy('id')->first();

        if ($user === null) {
            $this->command?->warn(
                'Пользователей нет. Сначала зарегистрируйте пользователя'
            );

            return;
        }

        $user->role()->associate($role);
        $user->save();

        $this->command?->info(
            "Пользователь {$user->email} назначен модератором"
        );
    }
}
