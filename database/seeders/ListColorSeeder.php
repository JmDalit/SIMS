<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            // ['bg-gray-100', 'text-gray-500'],
            // ['bg-gray-100', 'text-gray-700'],
            // ['bg-stone-100', 'text-stone-500'],
            // ['bg-stone-100', 'text-stone-700'],
            // ['bg-slate-100', 'text-slate-500'],
            // ['bg-slate-100', 'text-slate-700'],
            // ['bg-zinc-100', 'text-zinc-500'],
            // ['bg-zinc-100', 'text-zinc-700'],
            ['bg-blue-50', 'text-blue-500'],
            ['bg-blue-50', 'text-blue-700'],
            ['bg-blue-100', 'text-blue-500'],
            ['bg-blue-100', 'text-blue-700'],

            // Green shades
            ['bg-green-50', 'text-green-500'],
            ['bg-green-50', 'text-green-700'],
            ['bg-green-100', 'text-green-500'],
            ['bg-green-100', 'text-green-700'],

            // Red shades
            ['bg-red-50', 'text-red-500'],
            ['bg-red-50', 'text-red-700'],
            ['bg-red-100', 'text-red-500'],
            ['bg-red-100', 'text-red-700'],

            // Yellow shades
            ['bg-yellow-50', 'text-yellow-500'],
            ['bg-yellow-50', 'text-yellow-700'],
            ['bg-yellow-100', 'text-yellow-500'],
            ['bg-yellow-100', 'text-yellow-700'],

            // Purple shades
            ['bg-purple-50', 'text-purple-500'],
            ['bg-purple-50', 'text-purple-700'],
            ['bg-purple-100', 'text-purple-500'],
            ['bg-purple-100', 'text-purple-700'],

            // Pink shades
            ['bg-pink-50', 'text-pink-500'],
            ['bg-pink-50', 'text-pink-700'],
            ['bg-pink-100', 'text-pink-500'],
            ['bg-pink-100', 'text-pink-700'],

            // Teal shades
            ['bg-teal-50', 'text-teal-500'],
            ['bg-teal-50', 'text-teal-700'],
            ['bg-teal-100', 'text-teal-500'],
            ['bg-teal-100', 'text-teal-700'],

            // Cyan shades
            ['bg-cyan-50', 'text-cyan-500'],
            ['bg-cyan-50', 'text-cyan-700'],
            ['bg-cyan-100', 'text-cyan-500'],
            ['bg-cyan-100', 'text-cyan-700'],

            // Orange shades
            ['bg-orange-50', 'text-orange-500'],
            ['bg-orange-50', 'text-orange-700'],
            ['bg-orange-100', 'text-orange-500'],
            ['bg-orange-100', 'text-orange-700'],

            // Indigo shades
            ['bg-indigo-50', 'text-indigo-500'],
            ['bg-indigo-50', 'text-indigo-700'],
            ['bg-indigo-100', 'text-indigo-500'],
            ['bg-indigo-100', 'text-indigo-700'],

            // Neutral shades
            ['bg-gray-50', 'text-gray-500'],
            ['bg-gray-50', 'text-gray-700'],
            ['bg-gray-100', 'text-gray-500'],
            ['bg-gray-100', 'text-gray-700'],
            ['bg-stone-50', 'text-stone-500'],
            ['bg-stone-50', 'text-stone-700'],
            ['bg-stone-100', 'text-stone-500'],
            ['bg-stone-100', 'text-stone-700'],
            ['bg-slate-50', 'text-slate-500'],
            ['bg-slate-50', 'text-slate-700'],
            ['bg-slate-100', 'text-slate-500'],
            ['bg-slate-100', 'text-slate-700'],
            ['bg-zinc-50', 'text-zinc-500'],
            ['bg-zinc-50', 'text-zinc-700'],
            ['bg-zinc-100', 'text-zinc-500'],
            ['bg-zinc-100', 'text-zinc-700'],
            // ['bg-blue-100', 'text-blue-500'],
            // ['bg-blue-100', 'text-blue-700'],
            // ['bg-blue-100', 'text-indigo-500'],
            // ['bg-blue-100', 'text-indigo-700'],
            // ['bg-green-100', 'text-green-500'],
            // ['bg-green-100', 'text-green-700'],
            // ['bg-green-100', 'text-teal-500'],
            // ['bg-green-100', 'text-teal-700'],
            // ['bg-red-100', 'text-red-500'],
            // ['bg-red-100', 'text-red-700'],
            // ['bg-red-100', 'text-pink-500'],
            // ['bg-red-100', 'text-pink-700'],
            // ['bg-yellow-100', 'text-yellow-500'],
            // ['bg-yellow-100', 'text-yellow-700'],
            // ['bg-yellow-100', 'text-orange-500'],
            // ['bg-yellow-100', 'text-orange-700'],
            // ['bg-purple-100', 'text-purple-500'],
            // ['bg-purple-100', 'text-purple-700'],
            // ['bg-purple-100', 'text-pink-500'],
            // ['bg-purple-100', 'text-pink-700'],
            // ['bg-pink-100', 'text-pink-500'],
            // ['bg-pink-100', 'text-pink-700'],
            // ['bg-pink-100', 'text-red-500'],
            // ['bg-pink-100', 'text-red-700'],
            // ['bg-teal-100', 'text-teal-500'],
            // ['bg-teal-100', 'text-teal-700'],
            // ['bg-teal-100', 'text-indigo-500'],
            // ['bg-teal-100', 'text-indigo-700'],
            // ['bg-cyan-100', 'text-cyan-500'],
            // ['bg-cyan-100', 'text-cyan-700'],
            // ['bg-cyan-100', 'text-blue-500'],
            // ['bg-cyan-100', 'text-blue-700'],
            // ['bg-orange-100', 'text-orange-500'],
            // ['bg-orange-100', 'text-orange-700'],
            // ['bg-orange-100', 'text-red-500'],
            // ['bg-orange-100', 'text-red-700'],
            // ['bg-indigo-100', 'text-indigo-500'],
            // ['bg-indigo-100', 'text-indigo-700'],
            // ['bg-indigo-100', 'text-purple-500'],
            // ['bg-indigo-100', 'text-purple-700'],
            // ['bg-lime-100', 'text-lime-500'],
            // ['bg-lime-100', 'text-lime-700'],
            // ['bg-emerald-100', 'text-emerald-500'],
            // ['bg-emerald-100', 'text-emerald-700'],
            // ['bg-fuchsia-100', 'text-fuchsia-500'],
            // ['bg-fuchsia-100', 'text-fuchsia-700'],
            // ['bg-rose-100', 'text-rose-500'],
            // ['bg-rose-100', 'text-rose-700'],
            // ['bg-violet-100', 'text-violet-500'],
            // ['bg-violet-100', 'text-violet-700'],
        ];

        foreach ($colors as $color) {
            DB::table('list_colors')->insert([
                'background_color' => $color[0],
                'text_color' => $color[1],
                'is_active' => true,
                'is_delete' => false,
                'created_by' => 'Seeder',
                'updated_by' => 'Seeder',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
