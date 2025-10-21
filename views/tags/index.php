<?php
/* @var $this yii\web\View */
/* @var $tags array */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Теги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tags-index">
    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a('Создать тег', ['create'], ['class' => 'btn btn-success', 'style' => 'margin-left:12px;']) ?>
    </h1>

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
                [
                    'class' => 'yii\\grid\\ActionColumn',
                    'header' => 'Действия',
                    'template' => '{update} {delete}',
                    'urlCreator' => function($action, $model, $key, $index) {
                        return \yii\helpers\Url::to([$action, 'id' => isset($model['id']) ? $model['id'] : $key]);
                    },
                    'buttons' => [
                        'update' => function($url, $model, $key) {
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">'
                                  . '<path d="M12.146.146a.5.5 0 0 1 .708 0l2.0 2.0a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2L3 10.207V13h2.793L14 4.793 11.207 2z"/>'
                                  . '</svg>';
                            $link = \yii\helpers\Html::a($icon, $url, ['class' => 'btn btn-sm btn-outline-secondary', 'title' => 'Редактировать', 'data-pjax' => '0']);
                            return \yii\helpers\Html::tag('div', $link, ['class' => 'btn-group']);
                        },
                        'delete' => function($url, $model, $key) {
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">'
                                  . '<path d="M5.5 5.5A.5.5 0 0 1 6 5h4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5H6a.5.5 0 0 1-.5-.5v-7z"/>'
                                  . '<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 1 1 0-2h3l1-1h4l1 1h3a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118z"/>'
                                  . '</svg>';
                            $link = \yii\helpers\Html::a($icon, $url, [
                                'class' => 'btn btn-sm btn-outline-danger',
                                'title' => 'Удалить',
                                'data-confirm' => 'Вы уверены, что хотите удалить этот тег?',
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                            return \yii\helpers\Html::tag('div', $link, ['class' => 'btn-group']);
                        },
                    ],
                ],
            ],
        ]) ?>
    <?php else: ?>
        <p>Нет данных для отображения.</p>
    <?php endif; ?>
</div>