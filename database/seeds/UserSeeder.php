<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'NAME' => 'Sarjono Matulesi',
            'EMAIL' => 'sarjono@staff.ac.id',
            'PASSWORD' => Hash::make('sarjono'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '1'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Miryanti Ningsih',
            'EMAIL' => 'ningsih@staff.ac.id',            
            'PASSWORD' => Hash::make('ningsih'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '2'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Zainal Saifudin',
            'EMAIL' => 'zainal@staff.ac.id',
            'PASSWORD' => Hash::make('zainal'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '3'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Roida Rossa',
            'EMAIL' => 'roidaj@staff.ac.id',
            'PASSWORD' => Hash::make('roida'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '4'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Berliana Manurung',
            'EMAIL' => 'berliana@staff.ac.id',
            'PASSWORD' => Hash::make('berliana'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '5'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Sonyawati Tjan',
            'EMAIL' => 'sonyawati@staff.ac.id',
            'PASSWORD' => Hash::make('sonyawati'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '6'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Andrias Law',
            'EMAIL' => 'andrias@staff.ac.id',
            'PASSWORD' => Hash::make('andrias'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '7'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Velove Vexia',
            'EMAIL' => 'velove@staff.ac.id',
            'PASSWORD' => Hash::make('velove'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '8'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Anthony Chin',
            'EMAIL' => 'anthony@staff.ac.id',
            'PASSWORD' => Hash::make('anthony'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '9'
        ]);
        
        DB::table('users')->insert([
            'NAME' => 'Norma Wahyuni',
            'EMAIL' => 'norma@staff.ac.id',
            'PASSWORD' => Hash::make('norma'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '10'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Wiliam Gozali',
            'EMAIL' => 'wiliam@staff.ac.id',
            'PASSWORD' => Hash::make('wiliam'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '11'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Juna Mansur',
            'EMAIL' => 'juna@staff.ac.id',
            'PASSWORD' => Hash::make('juna'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '12'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Renata Moelok',
            'EMAIL' => 'renata@staff.ac.id',
            'PASSWORD' => Hash::make('renata'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '13'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Arnold Almond',
            'EMAIL' => 'arnold@staff.ac.id',
            'PASSWORD' => Hash::make('arnold'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '14'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Richard Jeremy',
            'EMAIL' => 'richard@staff.ac.id',
            'PASSWORD' => Hash::make('richard'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '15'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Novia Nobel',
            'EMAIL' => 'novia@staff.ac.id',
            'PASSWORD' => Hash::make('novia'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '16'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Tiara Nyaolo',
            'EMAIL' => 'tiara@staff.ac.id',
            'PASSWORD' => Hash::make('tiara'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '17'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Nyai Sooman',
            'EMAIL' => 'nyai@staff.ac.id',
            'PASSWORD' => Hash::make('nyai'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '18'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Kadir Park',
            'EMAIL' => 'kadir@staff.ac.id',
            'PASSWORD' => Hash::make('kadir'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '19'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Agung Choi',
            'EMAIL' => 'agung@staff.ac.id',
            'PASSWORD' => Hash::make('agung'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '20'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Eko Lee',
            'EMAIL' => 'eko@staff.ac.id',
            'PASSWORD' => Hash::make('eko'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '21'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Dimas Lee',
            'EMAIL' => 'dimas@staff.ac.id',
            'PASSWORD' => Hash::make('dimas'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '22'
        ]);

        DB::table('users')->insert([
            'NAME' => 'Hotman Cho',
            'EMAIL' => 'hotman@staff.ac.id',
            'PASSWORD' => Hash::make('hotman'),
            'ROLE' => 'STAFF',
            'STAFFS_ID' => '23'
        ]);

        // DB::table('users')->insert([
        //     'NAME' => 'Meliana Yunus',
        //     'EMAIL' => 'mel.yunus@gmail.com',
        //     'PASSWORD' => Hash::make('meliana'),
        //     'ROLE' => 'PARENT',
        //     'GUARDIANS_ID' => '1'
        // ]);
    }
}
