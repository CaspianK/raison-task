<?php

namespace App\Http\Controllers;

use App\Http\Requests\Currencies\ExchangeRequest;
use App\Models\Currency;
use App\Services\RequestService;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CurrencyController extends Controller
{

    public function index(): JsonResponse
    {
        $currencies = Currency::all();

        return response()->json($currencies);
    }
    public function exchange(ExchangeRequest $request): JsonResponse
    {
        $baseCurrency = $request->get('currency') ?? 'USD';
        $exchangeRates = Cache::get('exchange_rates_' . $baseCurrency);

        if (!$exchangeRates) {
            try {
                $response = app(RequestService::class)->request(
                    "GET",
                    "https://api.currencyapi.com/v3/latest?apikey=cur_live_YtKVw5ZIBsYYGezo7albQRSLusvsQgubHlbXsRnx&currencies=USD%2CEUR%2CRUB&base_currency=$baseCurrency"
                );
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }

            $exchangeRates = $response['data'];

            Cache::put('exchange_rates_' . $baseCurrency, $exchangeRates, 3600);
        }

        return response()->json($exchangeRates);
    }
}
