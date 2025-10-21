<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Тег: ' . ($tag['name'] ?? '');
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $tag['name'] ?? '';
?>
<div class="tags-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Назад к тегам', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?php if (isset($dataProvider)) : ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['attribute' => 'id', 'label' => 'ID'],
                [
                    'attribute' => 'link',
                    'format' => 'raw',
                    'label' => 'Ссылка',
                    'value' => function ($model) {
                        return Html::a(Html::encode($model['link']), $model['link'], ['target' => '_blank']);
                    }
                ],
                'description:text:Описание',
            ],
        ]) ?>
    <?php else: ?>
        <p>Нет каналов для этого тега.</p>
    <?php endif; ?>
</div>