<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(): JsonResponse
    {
        $stores = Store::all();

        return response()->json($stores);
    }
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');

        $stores = Store::where('name', 'like', "%$query%")->get();

        return response()->json($stores);
    }
}
