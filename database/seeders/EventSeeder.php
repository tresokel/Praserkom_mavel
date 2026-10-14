<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            ['title' => 'Konser My Chemical Romance',
                'category' => 'Musik',
                'ticket_price' => 5000000,
                'description' => 'Konser My Chemical Romance terakhir.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ['title' => 'Konser Radiohead',
                'category' => 'Musik',
                'ticket_price' => 5000000,
                'description' => 'Konser Radiohead terakhir.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ['title' => 'Konser The Smashing Pumpkins',
                'category' => 'Musik',
                'ticket_price' => 1150000,
                'description' => 'Konser The Smashing Pumpkins terakhir.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ['title' => 'Seminar DreamybullXXX',
                'category' => 'Musik',
                'ticket_price' => 500000,
                'description' => 'Seminar DreamybullXXX.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ['title' => 'FX festival 2023',
                'category' => 'Musik',
                'ticket_price' => 5000000,
                'description' => 'FX festival 2023.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
