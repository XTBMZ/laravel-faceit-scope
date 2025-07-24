<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceitService;

class PlayerController extends Controller
{
    protected $faceitService;

    public function __construct(FaceitService $faceitService)
    {
        $this->faceitService = $faceitService;
    }

    public function advanced(Request $request)
    {
        $playerId = $request->get('playerId');
        $playerNickname = $request->get('playerNickname');
        
        return view('advanced', compact('playerId', 'playerNickname'));
    }

    public function searchByNickname($nickname)
    {
        try {
            $player = $this->faceitService->getPlayerByNickname($nickname);
            return response()->json($player);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getPlayer($playerId)
    {
        try {
            $player = $this->faceitService->getPlayer($playerId);
            return response()->json($player);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getPlayerStats($playerId)
    {
        try {
            $stats = $this->faceitService->getPlayerStats($playerId);
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


    public function getMatch($matchId)
    {
        try {
            
            $cleanMatchId = $this->faceitService->extractMatchId($matchId);
            $match = $this->faceitService->getMatch($cleanMatchId);
            return response()->json($match);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}