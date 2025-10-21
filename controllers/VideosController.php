<?php

namespace app\controllers;

use yii\db\Query;
use yii\web\Controller;

class VideosController extends Controller
{
    public function actionIndex()
    {
        $queryVideos = new Query();
        $queryVideos->select([
            'v.id AS video_id',
            'v.url AS video_url',
            'v.title AS video_title',
            't.id AS tag_id',
            't.name AS tag_name',
        ])
            ->from(['v' => 'video'])
            ->innerJoin(['tv' => 'tag_video'], 'v.id = tv.video_id')
            ->innerJoin(['t' => 'tag'], 'tv.tag_id = t.id')
            ->orderBy(['v.id' => SORT_ASC, 't.name' => SORT_ASC]);

        $videos = $queryVideos->all();
        $videosToView = [];
        foreach ($videos as $video) {
            $videosToView[$video['video_id']]['url'] = $video['video_url'];
            $videosToView[$video['video_id']]['title'] = $video['video_title'];
            $videosToView[$video['video_id']]['tags'][] = [
                'id' => $video['tag_id'],
                'name' => $video['tag_name'],
            ];
        }

        $models = [];
        foreach ($videosToView as $id => $v) {
            $models[] = array_merge(['id' => $id], $v);
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $models,
            'pagination' => ['pageSize' => 20],
            'sort' => [ 'attributes' => ['id', 'title', 'url'] ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
