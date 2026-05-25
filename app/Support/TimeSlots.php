<?php

declare(strict_types=1);

namespace App\Support;

final class TimeSlots
{
    public const SLOTS = [
        '09:00:00' => '9.00—11.00',
        '11:00:00' => '11.00—13.00',
        '13:00:00' => '13.00—15.00',
        '15:00:00' => '15.00—17.00',
    ];

    public static function normalize(string $timeRaw): string
    {
        $timeRaw = trim($timeRaw);
        $parts = explode(':', $timeRaw);
        if (count($parts) >= 2) {
            return sprintf('%02d:%02d:00', (int) $parts[0], (int) $parts[1]);
        }

        return $timeRaw;
    }

    public static function label(string $time): string
    {
        $normalized = self::normalize($time);

        return self::SLOTS[$normalized] ?? substr($normalized, 0, 5);
    }

    public static function isValid(string $time): bool
    {
        return array_key_exists(self::normalize($time), self::SLOTS);
    }
}
