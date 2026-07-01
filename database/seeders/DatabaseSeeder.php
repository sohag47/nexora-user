<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAdditionalInfo;
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




        // all seed
        Role::factory(5)->create();
        Client::factory(5)->create();

        // 1. Create the pools of data first
        $branches = Branch::factory(10)->create();
        $designations =  Designation::factory(10)->create();
        $departments = Department::factory(10)->recycle($branches)->create();


        // 2. Create your specific Test User using the recycle pools
        User::factory()
            ->recycle($branches)
            ->recycle($departments)
            ->recycle($designations)
            ->has(UserAdditionalInfo::factory(), 'additionalInfo')
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 123456
            ]);

        // 3. Create the 50 random dummy users using the same recycle pools
        User::factory(50)
            ->recycle($branches)
            ->recycle($departments)
            ->recycle($designations)
            ->has(UserAdditionalInfo::factory(), 'additionalInfo')
            ->create();

        $this->call([
            UniversitySeeder::class, // Must run before UserSeeder
        ]);
    }
}
