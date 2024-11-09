<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;
use DB;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('tblUser')->insert([
            [
                'fldUserFirstname' => $faker->firstName,
                'fldUserLastname' => $faker->lastName,
                'fldUserEmail' => 'writer@user.com',
                'fldUserPassword' => Hash::make('Article_writer_123'),
                'fldUserType' => 0,
            ],
            [
                'fldUserFirstname' => $faker->firstName,
                'fldUserLastname' => $faker->lastName,
                'fldUserEmail' => 'editor@user.com',
                'fldUserPassword' => Hash::make('Article_editor_123'),
                'fldUserType' => 1,
            ]
        ]);
    }
}