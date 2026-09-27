<?php

/** @var yii\web\View $this */
/** @var app\models\User $user */
/** @var app\models\Request $model */

use yii\helpers\Html;

$this->title = $user->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <?= $this->render('_info', ['user' => $user]) ?>
        </div>
        <div class="col-md-6">
            <h2 class="h4">Отправить заявку</h2>

            <?= $this->render('/request/_form', ['model' => $model]) ?>
        </div>
    </div>

</div>
