<?php

namespace App\Services;

use App\Models\Developer;
use App\Models\Skill;

class MatchmakingService
{
    public function findMatches(array $location, array $skills, float $radius): array
    {
        $developers = Developer::nearby($location['lat'], $location['lng'], $radius)
            ->with('skills')
            ->get();

        return $developers->map(function ($developer) use ($skills) {
            $matchScore = $this->calculateMatchScore($developer, $skills);
            return [
                'developer' => $developer,
                'match_score' => $matchScore,
                'distance' => $developer->distance
            ];
        })->sortByDesc('match_score')->values()->all();
    }

    private function calculateMatchScore($developer, array $requiredSkills): float
    {
        $developerSkills = $developer->skills->pluck('name')->toArray();
        $matchingSkills = array_intersect($requiredSkills, $developerSkills);

        return (count($matchingSkills) / count($requiredSkills)) * 100;
    }
}
