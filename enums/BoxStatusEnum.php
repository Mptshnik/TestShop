<?php

namespace app\enums;

use app\traits\EnumTrait;

/**
 * Статусы коробок.
 */
enum BoxStatusEnum: int
{
    use EnumTrait;

    case EXPECTED = 0;
    case AT_WAREHOUSE = 1;
    case PREPARED = 2;
    case SHIPPED = 3;

    /**
     * @return string
     *
     * Заголовок статуса
     */
    public function label(): string
    {
        return match ($this) {
            BoxStatusEnum::EXPECTED => 'Expected',
            BoxStatusEnum::AT_WAREHOUSE => 'At warehouse',
            BoxStatusEnum::PREPARED => 'Prepared',
            BoxStatusEnum::SHIPPED => 'Shipped',
        };
    }
}
