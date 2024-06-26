<?php

namespace app\forms;

use app\models\Box;
use yii\base\Model;

class BoxForm extends Model
{
    public $weight;
    public $height;
    public $length;
    public $width;
    public $reference;
    public $status;

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

    public function rules(): array
    {
        return [
            [
                'reference',
                'required',
            ],
            [
                ['weight', 'height', 'length', 'width'], 'required',
            ],
            [
                ['weight', 'height', 'length', 'width'], 'number', 'numberPattern' => '/^\d+(\.\d{1,2})?$/'
            ],
            [
                'status', 'required', 'when' => function (self $form) {
                    return $form->box !== null;
                },
            ],
        ];
    }
}