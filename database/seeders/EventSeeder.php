<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Support\Str;
use App\Models\CategoryEvent;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Seminar & Workshop',
                'slug' => Str::slug('Seminar & Workshop')
            ],
            [
                'name' => 'Kompetisi',
                'slug' => Str::slug('Kompetisi')
            ],
            [
                'name' => 'Pelatihan',
                'slug' => Str::slug('Pelatihan')
            ]
        ];

        foreach ($categories as $categoryData) {
            CategoryEvent::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        Event::create([
            'title' => 'Webinar Nasional Teknologi Terkini 2023',
            'slug' => 'webinar-nasional-teknologi-2023',
            'description' => 'Webinar nasional menghadirkan pakar teknologi membahas perkembangan terkini di bidang AI, IoT, dan Cloud Computing',
            'start_date' => Carbon::now()->addDays(7)->setTime(13, 0),
            'end_date' => Carbon::now()->addDays(7)->setTime(16, 0),
            'location' => 'Zoom Meeting & Aula FT UHO',
            'status' => 'published',
            'max_participants' => 200,
            'is_free' => true,
            'price' => null,
            'registration_link' => 'https://bit.ly/webinar-bemft2023',
            'kabinet_id' => 1,
            'category_event_id' => 1
        ]);

        // Event 2: Lomba Coding
        Event::create([
            'title' => 'FT UHO Coding Competition 2023',
            'slug' => 'coding-competition-2023',
            'description' => 'Kompetisi coding tahunan untuk mahasiswa FT UHO dengan tema "Solusi Digital untuk Masalah Lokal"',
            'start_date' => Carbon::now()->addDays(14)->setTime(8, 0),
            'end_date' => Carbon::now()->addDays(14)->setTime(17, 0),
            'location' => 'Laboratorium Komputer FT UHO',
            'status' => 'published',
            'max_participants' => 50,
            'is_free' => false,
            'price' => 25000,
            'registration_link' => 'https://bit.ly/codingcomp-bemft',
            'kabinet_id' => 2,
            'category_event_id' => 1
        ]);

        // Event 3: Workshop UI/UX Design
        Event::create([
            'title' => 'Workshop Dasar UI/UX Design',
            'slug' => 'workshop-uiux-design',
            'description' => 'Pelatihan intensif selama 2 hari tentang prinsip dasar desain antarmuka pengguna dan pengalaman pengguna',
            'start_date' => Carbon::now()->addDays(21)->setTime(9, 0),
            'end_date' => Carbon::now()->addDays(22)->setTime(16, 0),
            'location' => 'Ruang Multimedia FT UHO',
            'status' => 'draft',
            'max_participants' => 30,
            'is_free' => false,
            'price' => 50000,
            'registration_link' => 'https://bit.ly/uiux-workshop',
            'kabinet_id' => 1,
            'category_event_id' => 2
        ]);
    }
}
