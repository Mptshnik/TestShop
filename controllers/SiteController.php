<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * Корневой контроллер
 */
class SiteController extends Controller
{
    /**
     * @return string
     *
     * Получить главную страницу
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
