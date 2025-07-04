<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TurnoPanol;
use Illuminate\Http\Request;

class TurnoPanolController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('all')) {
            return TurnoPanol::all();
        }
        return TurnoPanol::paginate(10);
    }
}
