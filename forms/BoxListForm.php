<?php

namespace app\forms;

use app\enums\BoxStatusEnum;
use app\models\Box;
use yii\base\Model;

/**
 * Форма редактирования списка коробок в таблице.
 */
class BoxListForm extends Model
{
    public const string FORM_NAME = 'BoxListForm';

    public $weight;
    public $status;

    public function __construct(public ?Box $box = null, array $config = [])
    {
        if ($box) {
            $this->weight = $box->weight;
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
            ['weight', 'number', 'numberPattern' => '/^\d+(\.\d{1,2})?$/', 'min' => 0],
            [
                'status', 'in', 'range' => [BoxStatusEnum::EXPECTED->value, BoxStatusEnum::AT_WAREHOUSE->value],
            ],
            [
                'status', 'validateStatus',
            ],
        ];
    }

    /**
     * @return string
     *
     * Наменование формы
     */
    public function formName(): string
    {
        return self::FORM_NAME;
    }

    /**
     * @param $attribute
     */
    public function validateStatus($attribute): void
    {
        if ($this->$attribute != BoxStatusEnum::AT_WAREHOUSE->value) {
            return;
        }

        if (empty($this->weight)) {
            $this->addError($attribute, 'Weight cannot be blank.');
        }

        if (!$this->box->isQuantityMatch()) {
            $this->addError($attribute, 'Received quantity and shipped quantity must match');
        }
    }
}
