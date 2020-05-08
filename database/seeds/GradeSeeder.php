<?php

use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grades')->insert([
            'NAME' => '10-MM',
            'DESCRIPTION' => 'Tingkat Pertama',
            'GRADE' => '10',
            'PROGRAMS_ID' => '1',
            'STAFFS_ID' => '1'
        ]);
        DB::table('grades')->insert([
            'NAME' => '10-TAV',
            'DESCRIPTION' => 'Tingkat Pertama',
            'GRADE' => '10',
            'PROGRAMS_ID' => '2',
            'STAFFS_ID' => '3'
        ]);
        DB::table('grades')->insert([
            'NAME' => '10-TKJ',
            'DESCRIPTION' => 'Tingkat Pertama',
            'GRADE' => '10',
            'PROGRAMS_ID' => '3',
            'STAFFS_ID' => '1'
        ]);
        DB::table('grades')->insert([
            'NAME' => '10-TKR',
            'DESCRIPTION' => 'Tingkat Pertama',
            'GRADE' => '10',
            'PROGRAMS_ID' => '4',
            'STAFFS_ID' => '3'
        ]);
        DB::table('grades')->insert([
            'NAME' => '10-TPm',
            'DESCRIPTION' => 'Tingkat Pertama',
            'GRADE' => '10',
            'PROGRAMS_ID' => '5',
            'STAFFS_ID' => '1'
        ]);
        DB::table('grades')->insert([
            'NAME' => '10-TSM',
            'DESCRIPTION' => 'Tingkat Pertama',
            'GRADE' => '10',
            'PROGRAMS_ID' => '6',
            'STAFFS_ID' => '3'
        ]);

        DB::table('grades')->insert([
            'NAME' => '11-MM',
            'DESCRIPTION' => 'Tingkat Kedua',
            'GRADE' => '11',
            'PROGRAMS_ID' => '1',
            'STAFFS_ID' => '1'
        ]);
        DB::table('grades')->insert([
            'NAME' => '11-TAV',
            'DESCRIPTION' => 'Tingkat Kedua',
            'GRADE' => '11',
            'PROGRAMS_ID' => '2',
            'STAFFS_ID' => '3'
        ]);
        DB::table('grades')->insert([
            'NAME' => '11-TKJ',
            'DESCRIPTION' => 'Tingkat Kedua',
            'GRADE' => '11',
            'PROGRAMS_ID' => '3',
            'STAFFS_ID' => '1'
        ]);
        DB::table('grades')->insert([
            'NAME' => '11-TKR',
            'DESCRIPTION' => 'Tingkat Kedua',
            'GRADE' => '11',
            'PROGRAMS_ID' => '4',
            'STAFFS_ID' => '3'
        ]);
        DB::table('grades')->insert([
            'NAME' => '11-TPm',
            'DESCRIPTION' => 'Tingkat Kedua',
            'GRADE' => '11',
            'PROGRAMS_ID' => '5',
            'STAFFS_ID' => '1'
        ]);
        DB::table('grades')->insert([
            'NAME' => '11-TSM',
            'DESCRIPTION' => 'Tingkat Kedua',
            'GRADE' => '11',
            'PROGRAMS_ID' => '6',
            'STAFFS_ID' => '4'
        ]);

        DB::table('grades')->insert([
            'NAME' => '12-MM',
            'DESCRIPTION' => 'Tingkat Ketiga',
            'GRADE' => '12',
            'PROGRAMS_ID' => '1',
            'STAFFS_ID' => '2'
        ]);
        DB::table('grades')->insert([
            'NAME' => '12-TAV',
            'DESCRIPTION' => 'Tingkat Ketiga',
            'GRADE' => '12',
            'PROGRAMS_ID' => '2',
            'STAFFS_ID' => '3'
        ]);
        DB::table('grades')->insert([
            'NAME' => '12-TKJ',
            'DESCRIPTION' => 'Tingkat Ketiga',
            'GRADE' => '12',
            'PROGRAMS_ID' => '3',
            'STAFFS_ID' => '1'
        ]);
        DB::table('grades')->insert([
            'NAME' => '12-TKR',
            'DESCRIPTION' => 'Tingkat Ketiga',
            'GRADE' => '12',
            'PROGRAMS_ID' => '4',
            'STAFFS_ID' => '3'
        ]);
        DB::table('grades')->insert([
            'NAME' => '12-TPm',
            'DESCRIPTION' => 'Tingkat Ketiga',
            'GRADE' => '12',
            'PROGRAMS_ID' => '5',
            'STAFFS_ID' => '1'
        ]);
        DB::table('grades')->insert([
            'NAME' => '12-TSM',
            'DESCRIPTION' => 'Tingkat Ketiga',
            'GRADE' => '12',
            'PROGRAMS_ID' => '6',
            'STAFFS_ID' => '3'
        ]);
    }
}
