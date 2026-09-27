<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\Request;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class RequestController extends Controller
{
    public function actionCreate(): Response|string
    {
        $model = new Request();

        if ($model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Заявка отправлена');

            return $this->refresh();
        }

        $users = ArrayHelper::map(User::find()->orderBy('name')->all(), 'id', 'name');

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
        ]);
    }
}
