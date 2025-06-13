<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $modules = [
            "Airlines",
            "Airline types",
            "Airports",
            "Booking agent comments",
            "Booking flights",
            "Bookings",
            "Cities",
            "Codes lists",
            "Contacts",
            "Countries",
            "File managments",
            "Files",
            "Link categories",
            "Members",
            "Provinces",
            "Region categories",
            "Region types",
            "Regions",
            "Roles",
            "Sale forms",
            "Sys params",
            "Users",
            "Web pages",
            "System Menus",
            "Application Menus",
            "Lookup Data",
            "Dashboard",
            "Roles & Permissions Menus",
        ];

        $permissions = ['view', 'add', 'edit', 'delete', 'import', 'export'];

        foreach ($modules as $module) {
            $key = str_replace([' ', '-'], '-', strtolower($module)); // Convert to snake_case
            foreach ($permissions as $perm) {
                Permission::firstOrCreate(['name' => "$key.$perm"]);
            }
        }

        // $this->command->info('Permissions seeded successfully! shahroz = 0313-4325652');
    }
}
