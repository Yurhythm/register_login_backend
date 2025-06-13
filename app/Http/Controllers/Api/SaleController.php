<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends BaseController
{
    public function index()
    {
        $sales = Sale::withCount('items')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return response()->json($sales);
    }

    public function show($id)
    {
        try {
            $sale = Sale::with('items')->findOrFail($id);
            return response()->json(
                [
                    'success' => true,
                    'data' => $sale,
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'customer_code' => 'required|string',
                'customer_name' => 'required|string',
                'payment_method' => 'required|string',
                'total' => 'required|numeric',
                'discount' => 'required|numeric',
                'final_total' => 'required|numeric',
                'items' => 'required|array|min:1',
                'items.*.product_code' => 'required|string',
                'items.*.product_name' => 'required|string',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.price' => 'required|numeric',
                'items.*.discount_price' => 'required|numeric',
                'items.*.subtotal' => 'required|numeric',
            ]);

            DB::transaction(function () use ($request, &$newCode) {
                $datePrefix = now()->format('Ymd');
                $lastCode = Sale::whereDate('created_at', now())
                    ->orderBy('created_at', 'desc')
                    ->value('sale_code');

                if ($lastCode) {
                    $lastNumber = (int) substr($lastCode, -4);
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }
                $newCode = 'SL' . $datePrefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

                $sale = Sale::create([
                    'id' => (string) Str::uuid(),
                    'sale_code' => $newCode,
                    'date' => $request->date,
                    'customer_code' => $request->customer_code,
                    'customer_name' => $request->customer_name,
                    'payment_method' => $request->payment_method,
                    'total' => $request->total,
                    'discount' => $request->discount,
                    'final_total' => $request->final_total,
                ]);

                foreach ($request->items as $item) {
                    $sale->items()->create([
                        'product_code' => $item['product_code'],
                        'product_name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'discount_price' => $item['discount_price'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'data' => [
                    'sale_code' => $newCode,
                    'date' => $request->date,
                    'customer_code' => $request->customer_code,
                    'customer_name' => $request->customer_name,
                    'payment_method' => $request->payment_method,
                    'total' => $request->total,
                    'discount' => $request->discount,
                    'final_total' => $request->final_total,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi',
                'errors' => $e->getMessage(),
            ], 422);
        }
    }
}
