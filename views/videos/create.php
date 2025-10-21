<?php
/* @var $this yii\web\View */
/* @var $model app\models\Video */

$this->title = 'Создать видео';
$this->params['breadcrumbs'][] = ['label' => 'Видео', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videos-create">
    <h1><?= $this->title ?></h1>

    <?= $this->render('_form', ['model' => $model]) ?>
</div>
