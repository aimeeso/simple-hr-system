<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            "name" => "Admin",
            "last_name" => "Admin",
            "first_name" => "Super",
            "password"=> Hash::make("Hradmin"),
            "birthday" => "2024-01-01",
            "geneder" => "M",
            "on_board_date" => "2024-01-01",
            "is_active" => 1,
            "email" => "admin@localhost"
        ];

        foreach ($users as $user) {
            $record = new User();
            $record->name = $user['name'];
            $record->email = $user['email'];
            $record->password = $user['password'];
            $record->birthday = $user['birthday'];
            $record->geneder = $user['geneder'];
            $record->on_board_date = $user['on_board_date'];
            $record->is_active = $user['is_active'];
            $record->email = $user['email'];

            $record->save();
        }
    }
}
