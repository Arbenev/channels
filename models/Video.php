<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $title
 *
 * @property TagVideo[] $tagVideo
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * Virtual attribute for tag ids (used in forms)
     * @var array
     */
    public $tagIds = [];
    public static function tableName()
    {
        return 'video';
    }

    public function rules()
    {
        return [
            [['url', 'title'], 'default', 'value' => null],
            [['url'], 'string', 'max' => 256],
            [['title'], 'string', 'max' => 100],
            [['tagIds'], 'each', 'rule' => ['integer']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'title' => 'Title',
        ];
    }

    public function getTagVideo()
    {
        return $this->hasMany(TagVideo::class, ['video_id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        // populate tagIds from relation
        $this->tagIds = array_map(function($tv) { return $tv->tag_id; }, $this->tagVideo);
    }
}
