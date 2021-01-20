<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateArticleTags extends AbstractMigration
{
    public $autoId = false;

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('article_tags', ['id' => false]);
        $table->addColumn('article_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('tag_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addPrimaryKey(['article_id', 'tag_id']);
        $table->addForeignKeyWithName('tag_key', 'tag_id', 'tags', 'id');
        $table->addForeignKeyWithName('article_key', 'article_id', 'articles', 'id');
        $table->create();
    }
}
