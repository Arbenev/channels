<?php

namespace app\controllers;

use yii\db\Query;
use yii\web\Controller;
use Yii;
use app\models\Channel;
use app\models\TagChannel;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ChannelsController extends Controller
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

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new Channel();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->saveTags($model);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->saveTags($model);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Channel::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested channel does not exist.');
    }

    protected function saveTags(Channel $model)
    {
        $db = Yii::$app->db;
        $tagIds = is_array($model->tagIds) ? $model->tagIds : [];
        $transaction = $db->beginTransaction();
        try {
            TagChannel::deleteAll(['channel_id' => $model->id]);
            foreach ($tagIds as $tagId) {
                $tc = new TagChannel();
                $tc->channel_id = $model->id;
                $tc->tag_id = (int)$tagId;
                if (!$tc->save(false)) {
                    throw new \RuntimeException('Failed to save tag relation');
                }
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
