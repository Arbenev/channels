<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Видео';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videos-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (isset($dataProvider)) : ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['attribute' => 'id', 'label' => 'ID'],
                [
                    'attribute' => 'title',
                    'label' => 'Заголовок',
                    'format' => 'raw',
                    'value' => function($model) {
                        $title = Html::encode($model['title']);
                        if (!empty($model['url'])) {
                            return Html::a($title, $model['url'], ['target' => '_blank']);
                        }
                        return $title;
                    }
                ],
                [
                    'attribute' => 'tags',
                    'label' => 'Теги',
                    'format' => 'raw',
                    'value' => function($model) {
                        if (empty($model['tags'])) return '';
                        $names = array_map(function($t) { return Html::a(Html::encode($t['name']), ['tags/view', 'id' => $t['id']]); }, $model['tags']);
                        return implode(', ', $names);
                    }
                ],
            ],
        ]) ?>
    <?php else: ?>
        <p>Нет данных для отображения.</p>
    <?php endif; ?>
</div>
