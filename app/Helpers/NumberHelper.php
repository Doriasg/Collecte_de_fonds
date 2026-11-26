<?php

namespace App\Helpers;

class NumberHelper
{
    private static array $units = [
        '', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf',
        'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize',
        'dix-sept', 'dix-huit', 'dix-neuf',
    ];

    private static array $tens = [
        '', '', 'vingt', 'trente', 'quarante', 'cinquante',
        'soixante', 'soixante', 'quatre-vingt', 'quatre-vingt',
    ];

    public static function toWords(int $n): string
    {
        if ($n === 0) return 'zéro';
        if ($n < 0)  return 'moins ' . self::toWords(-$n);

        $result = '';

        if ($n >= 1000000) {
            $m = intdiv($n, 1000000);
            $result .= ($m === 1 ? 'un million' : self::toWords($m) . ' millions');
            $n %= 1000000;
            if ($n > 0) $result .= ' ';
        }

        if ($n >= 1000) {
            $k = intdiv($n, 1000);
            $result .= ($k === 1 ? 'mille' : self::toWords($k) . ' mille');
            $n %= 1000;
            if ($n > 0) $result .= ' ';
        }

        if ($n >= 100) {
            $h = intdiv($n, 100);
            $result .= ($h === 1 ? 'cent' : self::$units[$h] . ' cent') . ($n % 100 === 0 && $h > 1 ? 's' : '');
            $n %= 100;
            if ($n > 0) $result .= ' ';
        }

        if ($n > 0) {
            if ($n < 20) {
                $result .= self::$units[$n];
            } else {
                $t = intdiv($n, 10);
                $u = $n % 10;

                if ($t === 7 || $t === 9) {
                    // 70-79 : soixante-dix... / 90-99 : quatre-vingt-dix...
                    $result .= self::$tens[$t] . '-' . self::$units[10 + $u];
                } elseif ($t === 8) {
                    $result .= 'quatre-vingts' . ($u > 0 ? '-' . self::$units[$u] : '');
                } else {
                    $result .= self::$tens[$t] . ($u === 1 ? '-et-un' : ($u > 0 ? '-' . self::$units[$u] : ''));
                }
            }
        }

        return ucfirst(trim($result));
    }
}
