<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $title
 *
 * @property TagArticle[] $tagArticle
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * Virtual attribute for tag ids (used in forms)
     * @var array
     */
    public $tagIds = [];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'title'], 'default', 'value' => null],
            [['url'], 'string', 'max' => 256],
            [['title'], 'string', 'max' => 100],
            [['tagIds'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[TagArticle]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagArticle()
    {
        return $this->hasMany(TagArticle::class, ['article_id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->tagIds = array_map(function($ta) { return $ta->tag_id; }, $this->tagArticle);
    }

}
