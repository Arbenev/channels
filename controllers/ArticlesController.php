<?php

namespace app\controllers;

use yii\db\Query;
use yii\web\Controller;
use Yii;
use app\models\Article;
use app\models\TagArticle;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ArticlesController extends Controller
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
        $queryArticles = new Query();
        $queryArticles->select([
            'a.id AS article_id',
            'a.url AS article_url',
            'a.title AS article_title',
            't.id AS tag_id',
            't.name AS tag_name',
        ])
            ->from(['a' => 'article'])
            ->innerJoin(['ta' => 'tag_article'], 'a.id = ta.article_id')
            ->innerJoin(['t' => 'tag'], 'ta.tag_id = t.id')
            ->orderBy(['a.id' => SORT_ASC, 't.name' => SORT_ASC]);

        $articles = $queryArticles->all();
        $articlesToView = [];
        foreach ($articles as $article) {
            $articlesToView[$article['article_id']]['url'] = $article['article_url'];
            $articlesToView[$article['article_id']]['title'] = $article['article_title'];
            $articlesToView[$article['article_id']]['tags'][] = [
                'id' => $article['tag_id'],
                'name' => $article['tag_name'],
            ];
        }

        // Подготовим модели и провайдер
        $models = [];
        foreach ($articlesToView as $id => $a) {
            $models[] = array_merge(['id' => $id], $a);
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
        $model = new Article();
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
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested article does not exist.');
    }

    protected function saveTags(Article $model)
    {
        $db = Yii::$app->db;
        $tagIds = is_array($model->tagIds) ? $model->tagIds : [];
        $transaction = $db->beginTransaction();
        try {
            TagArticle::deleteAll(['article_id' => $model->id]);
            foreach ($tagIds as $tagId) {
                $ta = new TagArticle();
                $ta->article_id = $model->id;
                $ta->tag_id = (int)$tagId;
                if (!$ta->save(false)) {
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
