<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

$this->title = 'Каналы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channels-index">
    <?php

    ?>
    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a('Теги', ['tags/index'], ['class' => 'btn btn-default', 'style' => 'margin-left:12px;']) ?>
    </h1>

    <?php if (isset($dataProvider)) : ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [ 'linkOptions' => ['style' => 'margin:0 8px;'] ],
            'columns' => [
                ['attribute' => 'id', 'label' => 'ID'],
                [
                    'attribute' => 'link',
                    'format' => 'raw',
                    'label' => 'Ссылка',
                    'value' => function($model) {
                        return Html::a(Html::encode($model['link']), $model['link'], ['target' => '_blank']);
                    }
                ],
                'description:text:Описание',
                [
                    'attribute' => 'tags',
                    'label' => 'Теги',
                    'format' => 'raw',
                    'value' => function($model) {
                        if (empty($model['tags'])) {
                            return '';
                        }
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