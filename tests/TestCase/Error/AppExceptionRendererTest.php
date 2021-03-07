<?php
declare(strict_types=1);

namespace App\Test\TestCase\Error;

use App\Error\AppExceptionRenderer;
use Cake\Http\Exception\MissingControllerException;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;

/**
 * Class AppExceptionRendererTest
 *
 * @package App\Test\TestCase\Error
 */
class AppExceptionRendererTest extends TestCase
{
    /**
     * @var bool
     */
    protected $_restoreError = false;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * tearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->clearPlugins();
        if ($this->_restoreError) {
            restore_error_handler();
        }
    }

    /**
     * test renderForApi
     */
    public function testRenderForApi()
    {
        Router::reload();

        $url = 'api/posts/1000';
        $request = new ServerRequest(['url' => $url]);
        Router::setRequest($request);

        $exception = new MissingControllerException('PostsController');
        $renderer = new AppExceptionRenderer($exception);

        $response = $renderer->render();
        $result = (string)$response->getBody();

        $this->assertSame(404, $response->getStatusCode());

        $expected = [
            'message' => 'Not Found',
            'url' => "/${url}",
            'code' => 404,
        ];
        $this->assertJsonStringEqualsJsonString(
            json_encode($expected),
            $result
        );
    }
}
