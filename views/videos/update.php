<?php
$phpDoc = true; // placeholder to keep header
/* @var $this yii\web\View */
/* @var $model app\models\Video */
use yii\helpers\Html;

$this->title = 'Редактировать видео: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Видео', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videos-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model]) ?>
</div>
