<?php

namespace app\helpers;

use yii\helpers\Html as BaseHelper;

/**
 * HTML хэлпер
 */
class Html extends BaseHelper
{
    /**
     * @param InputConfig $inputConfig
     * @param ButtonConfig $buttonConfig
     * @return string
     *
     * Отрисовка кнопки и поля ввода
     */
    public static function inputGroup(InputConfig $inputConfig, ButtonConfig $buttonConfig): string
    {
        if ($inputConfig->label) {
            $label = self::label($inputConfig->label, null, [
                'class' => 'form-label',
            ]);
        }

        $button = self::button($buttonConfig->text, $buttonConfig->options);

        $input = self::input(
            $inputConfig->type,
            $inputConfig->name,
            $inputConfig->value,
            $inputConfig->options
        );

        $inputGroup = self::tag('div', $input . $button, [
            'class' => 'input-group',
        ]);

        return self::tag('div', $label ? $label . $inputGroup : $inputGroup);
    }
}
