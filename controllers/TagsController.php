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

        // ActiveDataProvider for channels
        $channelsQuery = \app\models\Channel::find()
            ->alias('c')
            ->select(['c.id', 'c.link', 'c.description'])
            ->innerJoin(['tc' => 'tag_channel'], 'c.id = tc.channel_id')
            ->where(['tc.tag_id' => $id])
            ->orderBy(['c.id' => SORT_ASC]);

        $channelsProvider = new \yii\data\ActiveDataProvider([
            'query' => $channelsQuery,
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

        // ActiveDataProvider for articles
        $articlesQuery = \app\models\Article::find()
            ->alias('a')
            ->select(['a.id', 'a.title', 'a.url'])
            ->innerJoin(['ta' => 'tag_article'], 'a.id = ta.article_id')
            ->where(['ta.tag_id' => $id])
            ->orderBy(['a.id' => SORT_ASC]);

        $articlesProvider = new \yii\data\ActiveDataProvider([
            'query' => $articlesQuery,
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

        // ActiveDataProvider for videos
        $videosQuery = \app\models\Video::find()
            ->alias('v')
            ->select(['v.id', 'v.title', 'v.url'])
            ->innerJoin(['tv' => 'tag_video'], 'v.id = tv.video_id')
            ->where(['tv.tag_id' => $id])
            ->orderBy(['v.id' => SORT_ASC]);

        $videosProvider = new \yii\data\ActiveDataProvider([
            'query' => $videosQuery,
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
