<?php

use yii\db\Migration;

/**
 * Handles the creation of tables for tags, channels, articles and pivot tables.
 */
class m20251021_120000_create_initial_tables extends Migration
{
    public function safeUp()
    {
        // tag
        $this->createTable('tag', [
            'id' => $this->primaryKey(),
            'name' => $this->string(32),
        ]);

        // channel
        $this->createTable('channel', [
            'id' => $this->primaryKey(),
            'link' => $this->string(256),
            'description' => $this->string(1000),
        ]);
        $this->createIndex('idx-channel-link', 'channel', 'link', true);

        // article
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'url' => $this->string(256),
            'title' => $this->string(100),
        ]);

        // tag_channel pivot
        $this->createTable('tag_channel', [
            'channel_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('pk-tag_channel', 'tag_channel', ['channel_id', 'tag_id']);
        $this->createIndex('idx-tag_channel-channel_id', 'tag_channel', 'channel_id');
        $this->createIndex('idx-tag_channel-tag_id', 'tag_channel', 'tag_id');
        $this->addForeignKey('fk-tag_channel-channel', 'tag_channel', 'channel_id', 'channel', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-tag_channel-tag', 'tag_channel', 'tag_id', 'tag', 'id', 'CASCADE', 'CASCADE');

        // tag_article pivot
        $this->createTable('tag_article', [
            'article_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer(),
        ]);
        $this->addPrimaryKey('pk-tag_article', 'tag_article', ['article_id', 'tag_id']);
        $this->createIndex('idx-tag_article-article_id', 'tag_article', 'article_id');
        $this->createIndex('idx-tag_article-tag_id', 'tag_article', 'tag_id');
        $this->addForeignKey('fk-tag_article-article', 'tag_article', 'article_id', 'article', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-tag_article-tag', 'tag_article', 'tag_id', 'tag', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        // drop fks and tables in reverse order
        $this->dropForeignKey('fk-tag_article-tag', 'tag_article');
        $this->dropForeignKey('fk-tag_article-article', 'tag_article');
        $this->dropIndex('idx-tag_article-tag_id', 'tag_article');
        $this->dropIndex('idx-tag_article-article_id', 'tag_article');
        $this->dropPrimaryKey('pk-tag_article', 'tag_article');
        $this->dropTable('tag_article');

        $this->dropForeignKey('fk-tag_channel-tag', 'tag_channel');
        $this->dropForeignKey('fk-tag_channel-channel', 'tag_channel');
        $this->dropIndex('idx-tag_channel-tag_id', 'tag_channel');
        $this->dropIndex('idx-tag_channel-channel_id', 'tag_channel');
        $this->dropPrimaryKey('pk-tag_channel', 'tag_channel');
        $this->dropTable('tag_channel');

        $this->dropTable('article');
        $this->dropIndex('idx-channel-link', 'channel');
        $this->dropTable('channel');
        $this->dropTable('tag');
    }
}
