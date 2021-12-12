<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\Domain\Symbols\Models\Symbol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SymbolsController extends Controller
{
    public function index(Request $request)
    {
        $symbols = $request->get('data');
        if (!$symbols) {
            return [];
        }

        return array_map(function ($symbol) {
            $term = '{' . $symbol . '}';
            $search = Symbol::where('symbol', '=', $term)->first();

            return [
                'svg'        => $search->svgPath ? Storage::url($search->svgPath) : '',
                'symbolText' => $term,
            ];
        }, $symbols);
    }
}
