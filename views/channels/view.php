<?php
/* @var $this yii\web\View */
/* @var $model app\models\Channel */

use yii\helpers\Html;

$this->title = 'Канал: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Каналы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channels-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот канал?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <dl>
        <dt>ID</dt><dd><?= Html::encode($model->id) ?></dd>
        <dt>Link</dt><dd><?= Html::encode($model->link) ?></dd>
        <dt>Description</dt><dd><?= Html::encode($model->description) ?></dd>
        <dt>Tags</dt><dd><?php if (!empty($model->tags)) { echo implode(', ', array_map(function($t){ return Html::a(Html::encode($t->name), ['tags/view', 'id' => $t->id]); }, $model->tags)); } ?></dd>
    </dl>

</div>
