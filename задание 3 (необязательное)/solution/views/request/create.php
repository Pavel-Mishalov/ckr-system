<?php

/** @var yii\web\View $this */
/** @var app\models\Request $model */
/** @var array $users */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Отправить заявку';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['user/info']);
$this->registerJs(<<<JS
$('#request-user_id').on('change', function () {
    var id = $(this).val();
    if (id) {
        $.get('$url', {id: id}, function (html) {
            $('#user-info').html(html);
        });
    } else {
        $('#user-info').empty();
    }
}).trigger('change');
JS);
?>
<div class="request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <?= $this->render('_form', [
                'model' => $model,
                'users' => $users,
            ]) ?>
        </div>
        <div class="col-md-6" id="user-info"></div>
    </div>

</div>
