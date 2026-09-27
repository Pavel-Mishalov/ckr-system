<?php

/** @var yii\web\View $this */
/** @var app\models\Request $model */
/** @var array|null $users */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

?>
<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if (isset($users)): ?>
        <?= $form->field($model, 'user_id')->dropDownList($users, ['prompt' => 'Выберите получателя']) ?>
    <?php endif ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
