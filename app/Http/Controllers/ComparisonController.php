<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ComparisonController extends Controller
{
    public function index(Request $request)
    {
        $player1 = $request->get('player1');
        $player2 = $request->get('player2');
        
        return view('comparison', [
            'player1' => $player1,
            'player2' => $player2
        ]);
    }
}