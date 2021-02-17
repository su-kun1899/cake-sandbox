<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api;

use App\Model\Table\ArticlesTable;
use Cake\Datasource\ModelAwareTrait;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api\ArticlesController Test Case
 *
 * @property ArticlesTable Articles
 * @uses \App\Controller\Api\ArticlesController
 */
class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;
    use ModelAwareTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Articles',
        'app.Users',
        'app.Tags',
        'app.ArticlesTags',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->Articles = $this->loadModel('Articles');
    }

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex(): void
    {
        // when
        $this->get('/api/articles');

        // then
        $this->assertResponseOk('ステータスコードが成功になっていません');

        // and
        $expected = $this->Articles->find()->contain('Users');
        $this->assertJsonStringEqualsJsonString(
            json_encode(['articles' => $expected], JSON_PRETTY_PRINT),
            (string)$this->_response->getBody(),
            'レスポンスの中身がDBの値と一致しません'
        );
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd(): void
    {
        // given
        $data = [
            'user_id' => 1,
            'title' => "create new post",
        ];

        // when
        $this->post('/api/articles', $data);

        // then
        $this->assertResponseOk('ステータスコードが成功になっていません');

        // and
        $response = json_decode((string)$this->_response->getBody());
        $expected = $this->Articles->get($response->article->id);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['article' => $expected], JSON_PRETTY_PRINT),
            (string)$this->_response->getBody(),
            'レスポンスの中身がDBの値と一致しません'
        );
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
