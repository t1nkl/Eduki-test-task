<?php

namespace App\Repositories;

use App\Models\Voucher;

class VoucherRepository
{
    /**
     * @param  int  $discount
     * @return Voucher
     */
    public function generate(int $discount): Voucher
    {
        return Voucher::create([
            'discount' => $discount,
        ]);
    }

    /**
     * @param  string  $code
     * @return Voucher
     */
    public function findByCode(string $code): Voucher
    {
        return Voucher::where('code', $code)->firstOrFail();
    }
}
