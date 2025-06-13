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
                'image' => 'https://down-id.img.susercontent.com/file/6639c3e16e12392af7c0a87448c7390f'
            ],
            [
                'product_code' => 'PRD002',
                'product_name' => 'Kopi ABC Susu',
                'price' => 2500,
                'discount' => 10,
                'discount_type' => 'percent',
                'discount_price' => 2250,
                'image' => 'https://down-id.img.susercontent.com/file/id-11134207-7qula-lh2unj8i2a2a1d'
            ],
            [
                'product_code' => 'PRD003',
                'product_name' => 'Susu Ultra Milk Coklat 250ml',
                'price' => 6000,
                'discount' => 500,
                'discount_type' => 'fixed',
                'discount_price' => 5500,
                'image' => 'https://down-id.img.susercontent.com/file/6dd095a73524af106db989c727d6ad86'
            ],
            [
                'product_code' => 'PRD004',
                'product_name' => 'Aqua Botol 600ml',
                'price' => 4000,
                'discount' => 0,
                'discount_type' => 'none',
                'discount_price' => 4000,
                'image' => 'https://down-id.img.susercontent.com/file/9772c3b4ee30c1c5110eb1ad0ee401af'
            ],
            [
                'product_code' => 'PRD005',
                'product_name' => 'SilverQueen Chunky Bar',
                'price' => 12500,
                'discount' => 5,
                'discount_type' => 'percent',
                'discount_price' => 11875,
                'image' => 'https://down-id.img.susercontent.com/file/id-11134207-7r98o-lm08keh2mg81cb'
            ],
            [
                'product_code' => 'PRD006',
                'product_name' => 'Teh Pucuk Harum',
                'price' => 5000,
                'discount' => 1000,
                'discount_type' => 'fixed',
                'discount_price' => 4000,
                'image' => 'https://down-id.img.susercontent.com/file/4fb9f965231c5c6f4bd8516eb9470b92'
            ],
            [
                'product_code' => 'PRD007',
                'product_name' => 'Chitato Sapi Panggang',
                'price' => 8500,
                'discount' => 0,
                'discount_type' => 'none',
                'discount_price' => 8500,
                'image' => 'https://down-id.img.susercontent.com/file/13c32f2ce2f54fd918c094a99b3e2fb9'
            ],
            [
                'product_code' => 'PRD008',
                'product_name' => 'Roti Sari Roti Tawar',
                'price' => 12000,
                'discount' => 2000,
                'discount_type' => 'fixed',
                'discount_price' => 10000,
                'image' => 'https://down-id.img.susercontent.com/file/id-11134201-23030-i5uhewikpfov26'
            ],
            [
                'product_code' => 'PRD009',
                'product_name' => 'Minyak Goreng Bimoli 1L',
                'price' => 18500,
                'discount' => 1500,
                'discount_type' => 'fixed',
                'discount_price' => 17000,
                'image' => 'https://down-id.img.susercontent.com/file/b5bb900865b9b9fb8fbc08a8eaa0e3aa'
            ],
            [
                'product_code' => 'PRD010',
                'product_name' => 'Sabun Lifebuoy Merah',
                'price' => 3500,
                'discount' => 10,
                'discount_type' => 'percent',
                'discount_price' => 3150,
                'image' => 'https://down-id.img.susercontent.com/file/226cbac6cc04d90b876d8000a8cafe39'
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
