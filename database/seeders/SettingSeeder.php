<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            'color.primary-color-50' => '#e4f1ff',
            'color.primary-color-100' => '#bfdcff',
            'color.primary-color-200' => '#95c7ff',
            'color.primary-color-300' => '#6bb1ff',
            'color.primary-color-400' => '#519fff',
            'color.primary-color-500' => '#458eff',
            'color.primary-color-600' => '#487fff',
            'color.primary-color-700' => '#486cea',
            'color.primary-color-800' => '#4759d6',
            'color.primary-color-900' => '#4536b6',
        ];

        foreach ($colors as $key => $value) {
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
