<?php

declare(strict_types=1);

namespace App\Support;

use Carbon\Carbon;

final class DateFormatter
{
    private const RU_MONTHS = [
        1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
        5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
        9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря',
    ];

    public static function formatRu(Carbon|string $date): string
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        $month = (int) $carbon->format('n');

        return $carbon->format('j').' '
            .(self::RU_MONTHS[$month] ?? $carbon->format('m'))
            .' '.$carbon->format('Y');
    }
}
