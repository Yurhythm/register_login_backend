<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    public function index(Request $request)
    {
        $customers = collect([
            [
                'customer_code' => 'CUST001',
                'customer_name' => 'Andi Pratama',
                'email' => 'andi@example.com',
                'phone' => '08123456789',
                'address' => 'Jl. Merdeka No. 10, Surabaya',
                'membership' => 'Gold',
            ],
            [
                'customer_code' => 'CUST002',
                'customer_name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '08129876543',
                'address' => 'Jl. Raya Darmo No. 20, Malang',
                'membership' => 'Silver',
            ],
            [
                'customer_code' => 'CUST003',
                'customer_name' => 'Citra Lestari',
                'email' => 'citra@example.com',
                'phone' => '081212341234',
                'address' => 'Jl. Pahlawan No. 5, Jakarta',
                'membership' => 'Platinum',
            ],
        ]);

        if ($search = $request->query('search')) {
            $customers = $customers->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['customer_name']), strtolower($search)) ||
                    str_contains(strtolower($item['customer_code']), strtolower($search));
            })->values();
        }

        return response()->json($customers);
    }
}
