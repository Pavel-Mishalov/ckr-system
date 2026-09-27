<?php

/** @var yii\web\View $this */
/** @var app\models\User $user */

use yii\widgets\DetailView;

?>
<?= DetailView::widget([
    'model' => $user,
    'id' => 'user-detail', // без явного id в ответе аякса будет второй w0, как у формы
    'attributes' => [
        'name',
        'email:email',
        'phone',
        'about:ntext',
    ],
]) ?>
