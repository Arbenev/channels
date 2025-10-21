<?php
/* @var $this yii\web\View */
/* @var $tags array */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Теги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tags-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (isset($dataProvider)) : ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => ['linkOptions' => ['style' => 'margin:0 8px;']],
            'columns' => [
                ['attribute' => 'id', 'label' => 'ID'],
                [
                    'attribute' => 'name',
                    'label' => 'Название',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a(Html::encode($model['name']), ['tags/view', 'id' => $model['id']]);
                    }
                ],
                ['attribute' => 'count', 'label' => 'Количество каналов'],
            ],
        ]) ?>
    <?php else: ?>
        <p>Нет данных для отображения.</p>
    <?php endif; ?>
</div>