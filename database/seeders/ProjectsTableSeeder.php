<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects')->insert([
            ['project_name' => 'Pronto Case Cash', 'directory_path' => '/path/to/pronto'],
            ['project_name' => 'Agent 360', 'directory_path' => '/path/to/agent360'],
            ['project_name' => 'WAA', 'directory_path' => '/path/to/waa'],
        ]);
    }
}
