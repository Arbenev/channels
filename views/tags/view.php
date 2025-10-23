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

    <?php if (isset($channelsProvider) && $channelsProvider->getCount()) : ?>
        <h2>Каналы</h2>
        <?= GridView::widget([
            'dataProvider' => $channelsProvider,
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

    <?php if (isset($articlesProvider) && $articlesProvider->getCount() > 0) : ?>
        <h2>Статьи</h2>
        <?= GridView::widget([
            'dataProvider' => $articlesProvider,
            'columns' => [
                ['attribute' => 'id', 'label' => 'ID'],
                [
                    'attribute' => 'url',
                    'format' => 'raw',
                    'label' => 'Ссылка',
                    'value' => function ($model) {
                        return Html::a(Html::encode($model['url']), $model['url'], ['target' => '_blank']);
                    }
                ],
                [
                    'attribute' => 'title',
                    'label' => 'Заголовок',
                ],
            ],
        ]) ?>
    <?php else: ?>
        <p>Нет статей для этого тега.</p>
    <?php endif; ?>

    <?php if (isset($videosProvider) && $videosProvider->getCount() > 0) : ?>
        <h2>Видео</h2>
        <?= GridView::widget([
            'dataProvider' => $videosProvider,
            'columns' => [
                ['attribute' => 'id', 'label' => 'ID'],
                [
                    'attribute' => 'url',
                    'format' => 'raw',
                    'label' => 'Ссылка',
                    'value' => function ($model) {
                        return Html::a(Html::encode($model['url']), $model['url'], ['target' => '_blank']);
                    }
                ],
                [
                    'attribute' => 'title',
                    'label' => 'Заголовок',
                ],
            ],
        ]) ?>
    <?php else: ?>
        <p>Нет видео для этого тега.</p>
    <?php endif; ?>
</div>