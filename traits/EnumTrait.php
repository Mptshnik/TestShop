<?php

namespace app\traits;

/**
 * Трейт для перечислений.
 */
trait EnumTrait
{
    /**
     * @return array
     *
     * Значения enum
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array
     *
     * Опции enum
     */
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
