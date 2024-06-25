<?php

namespace app\forms;

use app\enums\BoxStatusEnum;
use app\models\Box;
use yii\base\Model;

/**
 * Форма добавления/редактирования коробки.
 */
class BoxForm extends Model
{
    public $weight = null;
    public $height = null;
    public $length = null;
    public $width = null;
    public $reference = null;
    public $status = null;

    public function __construct(public ?Box $box = null, array $config = [])
    {
        if ($box) {
            $this->weight = $box->weight;
            $this->height = $box->height;
            $this->length = $box->length;
            $this->width = $box->width;
            $this->reference = $box->reference;
            $this->status = $box->status;
        }

        parent::__construct($config);
    }

    /**
     * @return array
     *
     * Список правил
     */
    public function rules(): array
    {
        return [
            [
                'reference',
                'required',
            ],
            [
                ['weight', 'height', 'length', 'width'], 'default', 'value' => null,
            ],
            [
                ['weight', 'height', 'length', 'width'], 'number', 'numberPattern' => '/^\d+(\.\d{1,2})?$/',
            ],
            [
                'status', 'required', 'when' => function (self $form) {
                    return $form->box !== null;
                },
            ],
            [
                'status',
                'in',
                'range' => [BoxStatusEnum::PREPARED->value, BoxStatusEnum::SHIPPED->value],
            ],
        ];
    }
}
