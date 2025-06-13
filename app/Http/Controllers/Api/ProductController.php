<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends BaseController
{
    public function index(Request $request)
    {
        $products = collect([
            [
                'product_code' => 'PRD001',
                'product_name' => 'Indomie Goreng Original',
                'price' => 3500,
                'discount' => 0,
                'discount_type' => 'none',
                'discount_price' => 3500,
            ],
            [
                'product_code' => 'PRD002',
                'product_name' => 'Kopi ABC Susu 31g',
                'price' => 2500,
                'discount' => 10,
                'discount_type' => 'percent',
                'discount_price' => 2250,
            ],
            [
                'product_code' => 'PRD003',
                'product_name' => 'Susu Ultra Milk Coklat 250ml',
                'price' => 6000,
                'discount' => 500,
                'discount_type' => 'fixed',
                'discount_price' => 5500,
            ],
            [
                'product_code' => 'PRD004',
                'product_name' => 'Aqua Botol 600ml',
                'price' => 4000,
                'discount' => 0,
                'discount_type' => 'none',
                'discount_price' => 4000,
            ],
            [
                'product_code' => 'PRD005',
                'product_name' => 'SilverQueen Chunky Bar 62g',
                'price' => 12500,
                'discount' => 5,
                'discount_type' => 'percent',
                'discount_price' => 11875,
            ],
            [
                'product_code' => 'PRD006',
                'product_name' => 'Teh Pucuk Harum 350ml',
                'price' => 5000,
                'discount' => 1000,
                'discount_type' => 'fixed',
                'discount_price' => 4000,
            ],
            [
                'product_code' => 'PRD007',
                'product_name' => 'Chitato Sapi Panggang 68g',
                'price' => 8500,
                'discount' => 0,
                'discount_type' => 'none',
                'discount_price' => 8500,
            ],
            [
                'product_code' => 'PRD008',
                'product_name' => 'Roti Sari Roti Tawar 200g',
                'price' => 12000,
                'discount' => 2000,
                'discount_type' => 'fixed',
                'discount_price' => 10000,
            ],
            [
                'product_code' => 'PRD009',
                'product_name' => 'Minyak Goreng Bimoli 1L',
                'price' => 18500,
                'discount' => 1500,
                'discount_type' => 'fixed',
                'discount_price' => 17000,
            ],
            [
                'product_code' => 'PRD010',
                'product_name' => 'Sabun Lifebuoy Merah 85g',
                'price' => 3500,
                'discount' => 10,
                'discount_type' => 'percent',
                'discount_price' => 3150,
            ],
        ]);

        if ($search = $request->query('search')) {
            $products = $products->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['product_name']), strtolower($search)) ||
                    str_contains(strtolower($item['product_code']), strtolower($search));
            })->values();
        }

        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $offset = ($page - 1) * $perPage;

        $paginated = new LengthAwarePaginator(
            $products->slice($offset, $perPage)->values(),
            $products->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return response()->json($paginated);
    }
}
