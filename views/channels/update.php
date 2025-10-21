<?php
/* @var $this yii\web\View */
/* @var $model app\models\Channel */

$this->title = 'Изменить канал: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Каналы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="channels-update">

    <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model]) ?>

</div>
