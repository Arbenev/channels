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

    <?php if (isset($channelsProvider)) : ?>
        <h2>Каналы (<?= $channelsProvider->getTotalCount() ?>)</h2>
        <?php if ($channelsProvider->getTotalCount() > 0): ?>
            <?= GridView::widget([
                'dataProvider' => $channelsProvider,
                'pager' => [ 'linkOptions' => ['style' => 'margin:0 8px;'] ],
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
    <?php endif; ?>

    <?php if (isset($articlesProvider)) : ?>
        <h2>Статьи (<?= $articlesProvider->getTotalCount() ?>)</h2>
        <?php if ($articlesProvider->getTotalCount() > 0): ?>
            <?= GridView::widget([
                'dataProvider' => $articlesProvider,
                'pager' => [ 'linkOptions' => ['style' => 'margin:0 8px;'] ],
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
    <?php endif; ?>

    <?php if (isset($videosProvider)) : ?>
        <h2>Видео (<?= $videosProvider->getTotalCount() ?>)</h2>
        <?php if ($videosProvider->getTotalCount() > 0): ?>
            <?= GridView::widget([
                'dataProvider' => $videosProvider,
                'pager' => [ 'linkOptions' => ['style' => 'margin:0 8px;'] ],
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
    <?php endif; ?>
</div>