<?php

namespace app\controllers;

use yii\db\Query;
use yii\web\Controller;

class ChannelsController extends Controller
{
    public function actionIndex()
    {
        $queryChannels = new Query();
        $queryChannels->select([
            'c.id AS channel_id',
            'c.link AS channel_link',
            'c.description',
            't.id AS tag_id',
            't.name AS tag_name',
            ])
            ->from(['c' => 'channel'])
            ->innerJoin(['tc' => 'tag_channel'], 'c.id = tc.channel_id')
            ->innerJoin(['t' => 'tag'], 'tc.tag_id = t.id')
            ->orderBy(['c.id' => SORT_ASC, 't.name' => SORT_ASC]);
        $channels = $queryChannels->all();
        $channelsToView = [];
        foreach ($channels as $channel) {
            $channelsToView[$channel['channel_id']]['link'] = $channel['channel_link'];
            $channelsToView[$channel['channel_id']]['description'] = $channel['description'];
            $channelsToView[$channel['channel_id']]['tags'][] = [
                'id' => $channel['tag_id'],
                'name' => $channel['tag_name'],
            ];
        }
        $channels = $channelsToView;

        // Подготовим модели и ArrayDataProvider (сортировка и пагинация)
        $models = [];
        foreach ($channels as $id => $ch) {
            $models[] = array_merge(['id' => $id], $ch);
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $models,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'attributes' => ['id', 'link', 'description'],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
