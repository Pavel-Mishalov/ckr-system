<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\Request;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends Controller
{
    public function actionView(string $alias): Response|string
    {
        $user = $this->findModel(['alias' => $alias]);
        $model = new Request();

        if ($model->load($this->request->post())) {
            // получатель - всегда хозяин страницы, user_id из формы не берем
            $model->user_id = $user->id;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Заявка отправлена');

                return $this->refresh();
            }
        }

        return $this->render('view', [
            'user' => $user,
            'model' => $model,
        ]);
    }

    public function actionInfo(int $id): string
    {
        return $this->renderPartial('_info', ['user' => $this->findModel($id)]);
    }

    protected function findModel($condition): User
    {
        if (($model = User::findOne($condition)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Пользователь не найден.');
    }
}
