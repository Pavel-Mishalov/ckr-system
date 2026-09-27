<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use app\models\User;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Пользователи';
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Отправить заявку', ['request/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (User $user) {
                    return Html::a(Html::encode($user->name), ['user/view', 'alias' => $user->alias]);
                },
            ],
            'email:email',
            'phone',
        ],
    ]) ?>

</div>
