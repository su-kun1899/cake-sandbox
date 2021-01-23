<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\ArticlesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ArticlesController Test Case
 *
 * @uses \App\Controller\ArticlesController
 */
class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Articles',
        'app.Users',
        'app.ArticleTags',
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
