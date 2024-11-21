<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create skills
        $skills = [
            'Laravel' => Skill::create(['name' => 'Laravel']),
            'PHP' => Skill::create(['name' => 'PHP']),
            'MySQL' => Skill::create(['name' => 'MySQL']),
            'JavaScript' => Skill::create(['name' => 'JavaScript']),
            'Vue.js' => Skill::create(['name' => 'Vue.js']),
            'React' => Skill::create(['name' => 'React']),
            'Docker' => Skill::create(['name' => 'Docker']),
            'AWS' => Skill::create(['name' => 'AWS']),
            'Git' => Skill::create(['name' => 'Git']),
            'Redis' => Skill::create(['name' => 'Redis']),
            'Node.js' => Skill::create(['name' => 'Node.js']),
            'TypeScript' => Skill::create(['name' => 'TypeScript']),
        ];

        // Create developers in New York area
        $developers = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'latitude' => 40.7128,
                'longitude' => -74.0060,  // Manhattan
                'years_experience' => 8,
                'skills' => ['Laravel', 'PHP', 'MySQL', 'JavaScript', 'Vue.js']
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael@example.com',
                'latitude' => 40.7282,
                'longitude' => -73.7949,  // Queens
                'years_experience' => 6,
                'skills' => ['Laravel', 'PHP', 'Docker', 'AWS', 'Redis']
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily@example.com',
                'latitude' => 40.6782,
                'longitude' => -73.9442,  // Brooklyn
                'years_experience' => 7,
                'skills' => ['Laravel', 'PHP', 'React', 'TypeScript', 'Git']
            ],
            [
                'name' => 'David Kim',
                'email' => 'david@example.com',
                'latitude' => 40.7831,
                'longitude' => -73.9712,  // Upper East Side
                'years_experience' => 9,
                'skills' => ['Laravel', 'PHP', 'Node.js', 'AWS', 'Docker']
            ],
            [
                'name' => 'Lisa Patel',
                'email' => 'lisa@example.com',
                'latitude' => 40.7589,
                'longitude' => -73.9851,  // Midtown
                'years_experience' => 6,
                'skills' => ['Laravel', 'PHP', 'MySQL', 'Redis', 'Git']
            ],
            [
                'name' => 'James Wilson',
                'email' => 'james@example.com',
                'latitude' => 40.7505,
                'longitude' => -73.9934,  // Penn Station area
                'years_experience' => 10,
                'skills' => ['Laravel', 'PHP', 'Vue.js', 'Docker', 'AWS']
            ],
            [
                'name' => 'Anna Martinez',
                'email' => 'anna@example.com',
                'latitude' => 40.7484,
                'longitude' => -73.9857,  // Herald Square
                'years_experience' => 7,
                'skills' => ['Laravel', 'PHP', 'JavaScript', 'TypeScript', 'React']
            ],
            [
                'name' => 'Tom Jackson',
                'email' => 'tom@example.com',
                'latitude' => 40.7527,
                'longitude' => -73.9772,  // Murray Hill
                'years_experience' => 8,
                'skills' => ['Laravel', 'PHP', 'MySQL', 'Redis', 'Node.js']
            ]
        ];

        // Create developers and assign skills
        foreach ($developers as $devData) {
            $developer = Developer::create([
                'name' => $devData['name'],
                'email' => $devData['email'],
                'latitude' => $devData['latitude'],
                'longitude' => $devData['longitude'],
                'years_experience' => $devData['years_experience']
            ]);

            // Attach skills
            foreach ($devData['skills'] as $skillName) {
                $developer->skills()->attach($skills[$skillName]);
            }
        }
    }
}
