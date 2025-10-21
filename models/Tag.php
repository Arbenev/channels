<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property Channel[] $channels
 * @property TagArticle[] $tagArticles
 * @property TagChannel[] $tagChannel
 */
class Tag extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'default', 'value' => null],
            [['name'], 'string', 'max' => 32],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Channel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasMany(Channel::class, ['id' => 'channel_id'])->viaTable('tag_channel', ['tag_id' => 'id']);
    }

    /**
     * Gets query for [[TagArticles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagArticles()
    {
        return $this->hasMany(TagArticle::class, ['tag_id' => 'id']);
    }

    /**
     * Gets query for [[TagChannel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagChannel()
    {
        return $this->hasMany(TagChannel::class, ['tag_id' => 'id']);
    }

}
