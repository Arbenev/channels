<?php
/* @var $this yii\web\View */
/* @var $model app\models\Channel */

$this->title = 'Создать канал';
$this->params['breadcrumbs'][] = ['label' => 'Каналы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channels-create">

    <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model]) ?>

</div>
