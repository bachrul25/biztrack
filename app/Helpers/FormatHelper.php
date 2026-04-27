<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTimeInterface;

class FormatHelper
{
    private const MONTHS_ID = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public static function rupiah(float|int|string|null $amount, bool $withSymbol = true): string
    {
        $amount = (float) ($amount ?? 0);
        $formatted = number_format($amount, 0, ',', '.');

        return $withSymbol ? 'Rp '.$formatted : $formatted;
    }

    public static function number(float|int|string|null $number, int $decimals = 0): string
    {
        return number_format((float) ($number ?? 0), $decimals, ',', '.');
    }

    public static function tanggal(DateTimeInterface|string|null $date): string
    {
        if ($date === null || $date === '') {
            return '-';
        }
        $c = $date instanceof DateTimeInterface ? Carbon::instance($date) : Carbon::parse($date);

        return $c->day.' '.self::MONTHS_ID[$c->month].' '.$c->year;
    }

    public static function tanggalJam(DateTimeInterface|string|null $date): string
    {
        if ($date === null || $date === '') {
            return '-';
        }
        $c = $date instanceof DateTimeInterface ? Carbon::instance($date) : Carbon::parse($date);

        return self::tanggal($c).' '.$c->format('H:i');
    }

    public static function invoiceNumber(int $sequence, ?DateTimeInterface $date = null): string
    {
        $date = $date ? Carbon::instance($date) : Carbon::now();

        return 'INV-'.$date->format('Ymd').'-'.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    public static function monthName(int $month): string
    {
        return self::MONTHS_ID[$month] ?? '';
    }
}
