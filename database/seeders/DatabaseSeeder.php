<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\SubSection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $sections = [
            'Introduction' => [
                ['title' => 'Welcome', 'duration' => 5],
                ['title' => 'Setup Environment', 'duration' => 15],
            ],
            'Basic Concepts' => [
                ['title' => 'Component Structure', 'duration' => 20],
                ['title' => 'Data Binding', 'duration' => 25],
                ['title' => 'Event Handling', 'duration' => 15],
            ],
            'Advanced Topics' => [
                ['title' => 'Routing', 'duration' => 45],
                ['title' => 'State Management', 'duration' => 60],
                ['title' => 'API Integration', 'duration' => 90],
            ],
        ];

        $sectionOrder = 1;
        foreach ($sections as $sectionTitle => $videos) {
            $section = Section::create([
                'title' => $sectionTitle,
                'order' => $sectionOrder++
            ]);

            $videoOrder = 1;
            foreach ($videos as $video) {
                SubSection::create([
                    'section_id' => $section->id,
                    'title' => $video['title'],
                    'duration_minutes' => $video['duration'],
                    'order' => $videoOrder++
                ]);
            }
        }
    }
}
