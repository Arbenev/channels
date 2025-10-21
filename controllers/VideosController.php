<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Video;
use app\models\TagVideo;

class VideosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

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

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new Video();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // save tag relations
            $this->saveTags($model);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // update tag relations
            $this->saveTags($model);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', ['model' => $model]);
    }

    /**
     * Sync tag relations for a given video model
     * @param Video $model
     * @return void
     */
    protected function saveTags(Video $model)
    {
        $db = Yii::$app->db;
        $tagIds = is_array($model->tagIds) ? $model->tagIds : [];
        $transaction = $db->beginTransaction();
        try {
            // clear existing
            TagVideo::deleteAll(['video_id' => $model->id]);
            foreach ($tagIds as $tagId) {
                $tv = new TagVideo();
                $tv->video_id = $model->id;
                $tv->tag_id = (int)$tagId;
                if (!$tv->save(false)) {
                    throw new \RuntimeException('Failed to save tag relation');
                }
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            // rethrow to let caller handle the error (and not silently fail)
            throw $e;
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Video::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested video does not exist.');
    }
}
