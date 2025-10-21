<?php

namespace app\controllers;

use yii\db\Query;
use yii\web\Controller;

class TagsController extends Controller
{
    public function actionIndex()
    {
        $queryTags = new Query();
        $queryTags->select([
            't.id',
            't.name',
            'COUNT(t.id) AS count',
            ])
            ->from(['t' => 'tag'])
            ->innerJoin(['tc' => 'tag_channel'], 'tc.tag_id = t.id')
            ->innerJoin(['c' => 'channel'], 'c.id = tc.channel_id')
            ->groupBy(['t.id', 't.name'])
            ->orderBy(['id' => SORT_ASC]);
        $tags = $queryTags->all();

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $tags,
            'pagination' => ['pageSize' => 50],
            'sort' => [
                'attributes' => ['id', 'name', 'count'],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        // Получаем информацию о теге
        $queryTag = new Query();
        $tag = $queryTag->select(['t.id', 't.name'])
            ->from(['t' => 'tag'])
            ->where(['t.id' => $id])
            ->one();

        if (!$tag) {
            throw new \yii\web\NotFoundHttpException('Тег не найден.');
        }

        // Получаем каналы, связанные с тегом
        $queryChannels = new Query();
        $queryChannels->select([
            'c.id AS channel_id',
            'c.link AS channel_link',
            'c.description',
        ])
            ->from(['c' => 'channel'])
            ->innerJoin(['tc' => 'tag_channel'], 'c.id = tc.channel_id')
            ->where(['tc.tag_id' => $id])
            ->orderBy(['c.id' => SORT_ASC]);

        $channels = $queryChannels->all();

        // Преобразуем для GridView: сделаем плоские модели с id, link, description
        $models = [];
        foreach ($channels as $ch) {
            $models[] = [
                'id' => $ch['channel_id'],
                'link' => $ch['channel_link'],
                'description' => $ch['description'],
            ];
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $models,
            'pagination' => ['pageSize' => 20],
            'sort' => [ 'attributes' => ['id', 'link', 'description'] ],
        ]);

        return $this->render('view', [
            'tag' => $tag,
            'dataProvider' => $dataProvider,
        ]);
    }
}
