<?php

namespace App\Http\Controllers;

use App\Http\Requests\Purchases\IndexRequest;
use App\Http\Requests\Purchases\StoreRequest;
use App\Http\Requests\Purchases\UpdateRequest;
use App\Models\FileOrigin;
use App\Models\Purchase;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;

class PurchaseController extends Controller
{
    public function index(IndexRequest $request): JsonResponse
    {
        $store_id = $request->get('store_id');
        $purchases = $request->user()->purchases();

        if ($store_id) {
            $purchases = $purchases->where('store_id', $store_id);
        }

        $purchases = $purchases->get();

        return response()->json($purchases);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $purchase = $request->user()->purchases()->create($request->validated());

        try {
            $document = app(FileUploadService::class)->upload($request->file('document'));
        } catch (\Exception $e) {
            $purchase->delete();
            return response()->json(['message' => $e->getMessage()], 500);
        }

        $purchase->document()->save($document);


        return response()->json($purchase, 201);
    }

    public function update(Purchase $purchase, UpdateRequest $request): JsonResponse
    {
        $purchase->update($request->validated());

        $file = $request->file('document');
        if ($file) {
            try {
                $document = app(FileUploadService::class)->upload($request->file('document'));
            } catch (\Exception $e) {
                $purchase->delete();
                return response()->json(['message' => $e->getMessage()], 500);
            }

            $purchase->document->delete();
            $purchase->document()->save($document);
            $purchase->load('document');
        }

        return response()->json($purchase);
    }

    public function destroy(Purchase $purchase): JsonResponse
    {
        $purchase->delete();

        return response()->json(['message' => 'Purchase deleted']);
    }
}
