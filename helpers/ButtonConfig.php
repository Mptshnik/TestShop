<?php

namespace app\helpers;

/**
 * Конфигурация кнопки.
 */
class ButtonConfig
{
    public function __construct(public string $text, public array $options)
    {
    }
}
