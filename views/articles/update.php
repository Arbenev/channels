<?php
/* @var $this yii\web\View */
/* @var $model app\models\Article */

$this->title = 'Изменить статью: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="articles-update">

    <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model]) ?>

</div>
