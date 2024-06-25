<?php

namespace app\helpers;

/**
 * Конфигурация поля ввода.
 */
class InputConfig
{
    public function __construct(
        public string $type,
        public string $name,
        public ?string $value = null,
        public ?string $label = null,
        public array $options = []
    ) {
    }
}
