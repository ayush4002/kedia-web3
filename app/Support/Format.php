<?php

namespace App\Support;

/**
 * Indian-style number formatting (lakh / crore digit grouping).
 */
class Format
{
    /** 3148750 => "31,48,750" */
    public static function inr(int|float|null $amount): string
    {
        $amount = (int) round((float) $amount);
        $sign = $amount < 0 ? '-' : '';
        $digits = (string) abs($amount);

        if (strlen($digits) <= 3) {
            return $sign . $digits;
        }

        $last3 = substr($digits, -3);
        $rest = substr($digits, 0, -3);
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);

        return $sign . $rest . ',' . $last3;
    }

    /** 3148750 => "₹ 31.49 Lakh", 12000000 => "₹ 1.20 Cr" */
    public static function lakhCrore(int|float|null $amount): string
    {
        $amount = (float) $amount;
        if ($amount >= 10000000) {
            return '₹ ' . number_format($amount / 10000000, 2) . ' Cr';
        }
        return '₹ ' . number_format($amount / 100000, 2) . ' Lakh';
    }
}
