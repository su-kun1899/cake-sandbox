<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Articles seed.
 */
class ArticlesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'user_id' => 1,
            'title' => 'First Post',
            'slug' => 'first-post',
            'body' => 'This is the first post.',
            'published' => 1,
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        ];

        $table = $this->table('articles');
        $table->insert($data)->save();
    }
}
