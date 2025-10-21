<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "channel".
 *
 * @property int $id
 * @property string|null $link
 * @property string|null $description
 *
 * @property TagChannel[] $tagChannel
 * @property Tag[] $tags
 */
class Channel extends \yii\db\ActiveRecord
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
        return 'channel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link', 'description'], 'default', 'value' => null],
            [['link'], 'string', 'max' => 256],
            [['description'], 'string', 'max' => 1000],
            [['link'], 'unique'],
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
            'link' => 'Link',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[TagChannel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagChannel()
    {
        return $this->hasMany(TagChannel::class, ['channel_id' => 'id']);
    }

    /**
     * Gets query for [[Tags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('tag_channel', ['channel_id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->tagIds = array_map(function($tc) { return $tc->tag_id; }, $this->tagChannel);
    }

}
