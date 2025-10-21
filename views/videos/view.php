<?php
/* @var $this yii\web\View */
/* @var $model app\models\Video */

use yii\helpers\Html;

$this->title = $model->title ?: 'Видео #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Видео', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videos-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить это видео?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <table class="table table-striped table-bordered">
        <tr><th>ID</th><td><?= Html::encode($model->id) ?></td></tr>
        <tr><th>Url</th><td><?= Html::a(Html::encode($model->url), $model->url, ['target' => '_blank']) ?></td></tr>
        <tr><th>Title</th><td><?= Html::encode($model->title) ?></td></tr>
    </table>
</div>
