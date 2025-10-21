<?php
/* @var $this yii\web\View */
/* @var $model app\models\Tag */

$this->title = 'Изменить тег: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="tags-update">

    <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model]) ?>

</div>
