<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create a list of permissions
        $permissions = [
            [
                "name" => "User Management",
                "slug" => "user-management"
            ],
            [
                "name" => "Annual Leave Management",
                "slug" => "annual-leave-management"
            ],
            [
                "name" => "Approve Annual Leave Request",
                "slug" => "approve-request"
            ]
        ];

        // insert permissions into the database
        foreach ($permissions as $permission) {
            \App\Models\Permission::create($permission);
        }
    }
}
