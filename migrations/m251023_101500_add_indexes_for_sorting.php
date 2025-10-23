<?php

use yii\db\Migration;

/**
 * Class m251023_101500_add_indexes_for_sorting
 */
class m251023_101500_add_indexes_for_sorting extends Migration
{
    public function safeUp()
    {
    $schema = \Yii::$app->db->schema;

        // indexes for tag, article, video (create only if not exists)
    $tagSchema = $schema->getTableSchema('tag');
        if ($tagSchema !== null && !isset($tagSchema->indexes['idx-tag-name'])) {
            $this->createIndex('idx-tag-name', 'tag', 'name');
        }

    $articleSchema = $schema->getTableSchema('article');
        if ($articleSchema !== null && !isset($articleSchema->indexes['idx-article-title'])) {
            $this->createIndex('idx-article-title', 'article', 'title');
        }

    $videoSchema = $schema->getTableSchema('video');
        if ($videoSchema !== null && !isset($videoSchema->indexes['idx-video-title'])) {
            $this->createIndex('idx-video-title', 'video', 'title');
        }

        // composite indexes on pivot tables to speed up GROUP BY/COUNT(DISTINCT ...)
    $tcSchema = $schema->getTableSchema('tag_channel');
        if ($tcSchema !== null && !isset($tcSchema->indexes['idx-tag_channel-tag_id_channel_id'])) {
            $this->createIndex('idx-tag_channel-tag_id_channel_id', 'tag_channel', ['tag_id', 'channel_id']);
        }

    $taSchema = $schema->getTableSchema('tag_article');
        if ($taSchema !== null && !isset($taSchema->indexes['idx-tag_article-tag_id_article_id'])) {
            $this->createIndex('idx-tag_article-tag_id_article_id', 'tag_article', ['tag_id', 'article_id']);
        }

    $tvSchema = $schema->getTableSchema('tag_video');
        if ($tvSchema !== null && !isset($tvSchema->indexes['idx-tag_video-tag_id_video_id'])) {
            $this->createIndex('idx-tag_video-tag_id_video_id', 'tag_video', ['tag_id', 'video_id']);
        }
    }

    public function safeDown()
    {
    $schema = \Yii::$app->db->schema;

        $tvSchema = $schema->getTableSchema('tag_video');
        if ($tvSchema !== null && isset($tvSchema->indexes['idx-tag_video-tag_id_video_id'])) {
            $this->dropIndex('idx-tag_video-tag_id_video_id', 'tag_video');
        }

        $taSchema = $schema->getTableSchema('tag_article');
        if ($taSchema !== null && isset($taSchema->indexes['idx-tag_article-tag_id_article_id'])) {
            $this->dropIndex('idx-tag_article-tag_id_article_id', 'tag_article');
        }

        $tcSchema = $schema->getTableSchema('tag_channel');
        if ($tcSchema !== null && isset($tcSchema->indexes['idx-tag_channel-tag_id_channel_id'])) {
            $this->dropIndex('idx-tag_channel-tag_id_channel_id', 'tag_channel');
        }

        $videoSchema = $schema->getTableSchema('video');
        if ($videoSchema !== null && isset($videoSchema->indexes['idx-video-title'])) {
            $this->dropIndex('idx-video-title', 'video');
        }

        $articleSchema = $schema->getTableSchema('article');
        if ($articleSchema !== null && isset($articleSchema->indexes['idx-article-title'])) {
            $this->dropIndex('idx-article-title', 'article');
        }

        $tagSchema = $schema->getTableSchema('tag');
        if ($tagSchema !== null && isset($tagSchema->indexes['idx-tag-name'])) {
            $this->dropIndex('idx-tag-name', 'tag');
        }
    }
}
