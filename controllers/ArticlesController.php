<?php

namespace app\controllers;

use yii\db\Query;
use yii\web\Controller;

class ArticlesController extends Controller
{
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
}
