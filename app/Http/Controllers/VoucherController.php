<?php

namespace App\Http\Controllers;

use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function __construct(public readonly VoucherService $voucherService)
    {
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function generateCode(Request $request): JsonResponse
    {
        $voucher = $this->voucherService->generate($request->input('discount'));

        return response()->json(
            [
                'code' => $voucher->code,
            ]
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function applyVoucher(Request $request): JsonResponse
    {
        return response()->json(
            $this->voucherService->applyVoucher($request->input('items'), $request->input('code'))
        );
    }
}
