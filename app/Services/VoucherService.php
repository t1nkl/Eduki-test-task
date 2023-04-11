<?php

namespace App\Services;

use App\Models\Voucher;
use App\Repositories\VoucherRepository;

class VoucherService
{
    public function __construct(public VoucherRepository $voucherRepository)
    {
    }

    /**
     * @param  int  $discount
     * @return Voucher
     */
    public function generate(int $discount): Voucher
    {
        return $this->voucherRepository->generate($discount);
    }

    /**
     * @param  array  $items
     * @param  string  $code
     * @return array
     */
    public function applyVoucher(array $items, string $code): array
    {
        $voucher = $this->voucherRepository->findByCode($code);

        $totalPrice = array_reduce($items, fn ($carry, $item) => $carry + $item['price'], 0);

        if ($totalPrice === 0) {
            return [
                'items' => $items,
                'code' => $voucher->code,
                'discounted_price' => 0,
            ];
        }

        $discountedPrice = 0;
        $remainingDiscount = $voucher->discount;
        $itemsWithDiscount = [];
        $fraction = 0;

        foreach ($items as $item) {
            $relativePrice = $item['price'] / $totalPrice;
            $itemDiscount = $relativePrice * $voucher->discount;
            $itemDiscount = min($itemDiscount, $item['price'], $remainingDiscount);

            $itemWithDiscount = $item;
            $itemWithDiscount['price_with_discount'] = $item['price'] - $itemDiscount;

            $fraction += $itemWithDiscount['price_with_discount'] - (int) $itemWithDiscount['price_with_discount'];
            $itemWithDiscount['price_with_discount'] = (int) $itemWithDiscount['price_with_discount'];

            if ($fraction >= 1) {
                $itemWithDiscount['price_with_discount'] += (int) $fraction;
                $fraction -= (int) $fraction;
            }

            $discountedPrice += $itemWithDiscount['price_with_discount'];
            $itemsWithDiscount[] = $itemWithDiscount;
            $remainingDiscount -= $itemDiscount;

            if ($remainingDiscount <= 0) {
                break;
            }
        }

        $discountedPrice = min($totalPrice, $discountedPrice);
        $discountedPrice = max($discountedPrice, 0);

        return [
            'items' => $itemsWithDiscount,
            'code' => $voucher->code,
            'discounted_price' => $discountedPrice,
        ];
    }
}
