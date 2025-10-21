<?php
/* @var $this yii\web\View */
/* @var $model app\models\Article */

use yii\helpers\Html;

$this->title = 'Статья: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="articles-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту статью?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <dl>
        <dt>ID</dt><dd><?= Html::encode($model->id) ?></dd>
        <dt>Title</dt><dd><?= Html::encode($model->title) ?></dd>
        <dt>Url</dt><dd><?= Html::encode($model->url) ?></dd>
        <dt>Tags</dt><dd><?php if (!empty($model->tagArticle)) { echo implode(', ', array_map(function($t){ return Html::a(Html::encode($t->tag->name), ['tags/view', 'id' => $t->tag->id]); }, $model->tagArticle)); } ?></dd>
    </dl>

</div>
