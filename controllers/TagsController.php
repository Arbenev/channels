<?php

namespace app\controllers;

use yii\db\Query;
use yii\web\Controller;
use Yii;
use app\models\Tag;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class TagsController extends Controller
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
        $queryTags = new Query();
        // Select tag and counts from related pivot tables
        $queryTags->select([
            't.id',
            't.name',
            // subqueries for counts with aliases
                'channels_count' => new \yii\db\Expression('COUNT(DISTINCT tc.channel_id)'),
                'articles_count' => new \yii\db\Expression('COUNT(DISTINCT ta.article_id)'),
                'videos_count' => new \yii\db\Expression('COUNT(DISTINCT tv.video_id)'),
        ])
            ->from(['t' => 'tag'])
                ->leftJoin(['tc' => 'tag_channel'], 'tc.tag_id = t.id')
                ->leftJoin(['ta' => 'tag_article'], 'ta.tag_id = t.id')
                ->leftJoin(['tv' => 'tag_video'], 'tv.tag_id = t.id')
                ->groupBy(['t.id', 't.name']);
        $tags = $queryTags->all();

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $tags,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'attributes' => ['id', 'name', 'channels_count', 'articles_count', 'videos_count'],
                'defaultOrder' => ['name' => SORT_ASC],
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
        $channelModels = [];
        foreach ($channels as $ch) {
            $channelModels[] = [
                'id' => $ch['channel_id'],
                'link' => $ch['channel_link'],
                'description' => $ch['description'],
            ];
        }

        $channelsProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $channelModels,
            'pagination' => ['pageSize' => 20],
            'sort' => ['attributes' => ['id', 'link', 'description']],
        ]);

        // Получаем статьи, связанные с тегом
        $queryArticles = new Query();
        $queryArticles->select([
            'a.id AS article_id',
            'a.title AS article_title',
            'a.url AS article_url',
        ])
            ->from(['a' => 'article'])
            ->innerJoin(['ta' => 'tag_article'], 'a.id = ta.article_id')
            ->where(['ta.tag_id' => $id])
            ->orderBy(['a.id' => SORT_ASC]);

        $articles = $queryArticles->all();
        $articleModels = [];
        foreach ($articles as $ar) {
            $articleModels[] = [
                'id' => $ar['article_id'],
                'title' => $ar['article_title'],
                'url' => $ar['article_url'],
            ];
        }

        $articlesProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $articleModels,
            'pagination' => ['pageSize' => 20],
            'sort' => ['attributes' => ['id', 'title', 'url']],
        ]);

        // Получаем видео, связанные с тегом
        $queryVideos = new Query();
        $queryVideos->select([
            'v.id AS video_id',
            'v.title AS video_title',
            'v.url AS video_url',
        ])
            ->from(['v' => 'video'])
            ->innerJoin(['tv' => 'tag_video'], 'v.id = tv.video_id')
            ->where(['tv.tag_id' => $id])
            ->orderBy(['v.id' => SORT_ASC]);

        $videos = $queryVideos->all();
        $videoModels = [];
        foreach ($videos as $v) {
            $videoModels[] = [
                'id' => $v['video_id'],
                'title' => $v['video_title'],
                'url' => $v['video_url'],
            ];
        }

        $videosProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $videoModels,
            'pagination' => ['pageSize' => 20],
            'sort' => ['attributes' => ['id', 'title', 'url']],
        ]);

        return $this->render('view', [
            'tag' => $tag,
            'channelsProvider' => $channelsProvider,
            'articlesProvider' => $articlesProvider,
            'videosProvider' => $videosProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Tag();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested tag does not exist.');
    }
}
