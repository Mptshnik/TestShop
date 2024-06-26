<?php

namespace app\traits;

trait EnumTrait
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return array_combine(
            self::values(),
            self::callable(fn (self $case) => $case->label()),
        );
    }

    private static function callable(callable $callable): array
    {
        return array_map($callable, self::cases());
    }
}