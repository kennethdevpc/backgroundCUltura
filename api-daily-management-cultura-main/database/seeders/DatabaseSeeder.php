<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\StrengtheningSupervisionManagers;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call([
            UserSeeder::class,
            ModuleSeeder::class,
            ModuleItemSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            RoleUserSeeder::class,
            CulturalRightSeeder::class,
            EntityNameSeeder::class,
            ExpertiseSeeder::class,
            NacSeeder::class,
            NeighborhoodSeeder::class,
            OrientationSeeder::class,
            DataTestSeeder::class,
            PermissionRoleSeeder::class,
            ProfileSeeder::class,
            PecSeeder::class

        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }
}
