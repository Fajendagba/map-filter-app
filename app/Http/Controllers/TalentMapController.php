<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use App\Services\MatchmakingService;
use Illuminate\Http\Request;

class TalentMapController extends Controller
{
    protected $matchmaker;

    public function __construct(MatchmakingService $matchmaker)
    {
        $this->matchmaker = $matchmaker;
    }

    public function index()
    {
        $developers = Developer::with('skills')->get();
        return view('talent.map', compact('developers'));
    }

    public function match(Request $request)
    {
        $validated = $request->validate([
            'skills' => 'required|array',
            'location' => 'required|array',
            'location.lat' => 'required|numeric',
            'location.lng' => 'required|numeric',
            'radius' => 'required|numeric|min:1|max:500'
        ]);

        $matches = $this->matchmaker->findMatches(
            $validated['location'],
            $validated['skills'],
            $validated['radius']
        );

        return response()->json($matches);
    }

    public function getNearbyTalent(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $radius = $request->get('radius', 50);

        $nearbyTalent = Developer::nearby($lat, $lng, $radius)->with('skills')->get();
        return response()->json($nearbyTalent);
    }
}
