<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoComprobante;
use Illuminate\Http\Request;

class TipoComprobanteController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('all')) {
            return TipoComprobante::all();
        }
        return TipoComprobante::paginate(10);
    }
}
