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
        return $this->render('index', [
            'channels' => $channels,
        ]);
    }
    public function actionTags()
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
            ->orderBy(['count' => SORT_DESC]);
        $tags = $queryTags->all();
        return $this->render('index', [
            'tags' => $tags,
        ]);
    }
}
