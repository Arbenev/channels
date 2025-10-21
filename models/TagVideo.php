<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tag_video".
 *
 * @property int $video_id
 * @property int $tag_id
 *
 * @property Video $video
 * @property Tag $tag
 */
class TagVideo extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tag_video';
    }

    public function rules()
    {
        return [
            [['video_id', 'tag_id'], 'required'],
            [['video_id', 'tag_id'], 'integer'],
            [['video_id', 'tag_id'], 'unique', 'targetAttribute' => ['video_id', 'tag_id']],
            [['video_id'], 'exist', 'skipOnError' => true, 'targetClass' => Video::class, 'targetAttribute' => ['video_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::class, 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'video_id' => 'Video ID',
            'tag_id' => 'Tag ID',
        ];
    }

    public function getVideo()
    {
        return $this->hasOne(Video::class, ['id' => 'video_id']);
    }

    public function getTag()
    {
        return $this->hasOne(Tag::class, ['id' => 'tag_id']);
    }
}
