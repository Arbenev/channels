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
}
