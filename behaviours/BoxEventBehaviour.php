<?php

namespace app\behaviours;

use app\enums\BoxStatusEnum;
use app\models\Box;
use app\models\Event as EventModel;
use yii\base\Event;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * Поведение коробки.
 */
class BoxEventBehaviour extends AttributeBehavior
{
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * @return string[]
     *
     * Список событий. Отслеживается событие обновления коробки
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
        ];
    }

    /**
     * @param Event $event
     * @throws \yii\db\Exception
     *
     * Обработчик события AfterSaveEvent
     */
    public function afterUpdate(Event $event): void
    {
        /** @var Box $box */
        $box = $event->sender;

        $eventModel = EventModel::create(
            $box->status === BoxStatusEnum::PREPARED->value ? 0 : $box->weight,
            $box->getTotalProductQuantity(),
            $box->isQuantityMatch(),
            $box->id
        );

        $eventModel->save();
    }
}
