<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileOriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('file_origins')->insert([
            [
                'name' => 'local',
                'path' => 'http://127.0.0.1:80/storage',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 's3',
                'path' => 'http://127.0.0.1:9000/raison',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
