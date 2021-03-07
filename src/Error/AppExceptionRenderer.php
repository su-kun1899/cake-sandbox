<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Error\ExceptionRenderer;
use Cake\Http\Exception\HttpException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AppExceptionRenderer
 *
 * @package App\Error
 */
class AppExceptionRenderer extends ExceptionRenderer
{
    /**
     * @inheritDoc
     */
    public function render(): ResponseInterface
    {
        $url = $this->controller->getRequest()->getRequestTarget();
        if (str_starts_with($url, '/api')) {
            return $this->renderForApi();
        }

        return parent::render();
    }

    /**
     * Renders the response for the exception especially web api
     *
     * @return \Cake\Http\Response
     */
    private function renderForApi()
    {
        // api の場合は json で返す
        $this->controller->RequestHandler->renderAs($this->controller, 'json');

        $exception = $this->error;
        $code = $this->getHttpCode($exception);
        $method = $this->_method($exception);
        $template = $this->_template($exception, $method, $code);
        $this->clearOutput();

        if (method_exists($this, $method)) {
            return $this->_customMethod($method, $exception);
        }

        $response = $this->controller->getResponse();
        if ($exception instanceof HttpException) {
            foreach ($exception->getHeaders() as $name => $value) {
                $response = $response->withHeader($name, $value);
            }
        }
        $response = $response->withStatus($code);

        $url = $this->controller->getRequest()->getRequestTarget();

        $viewVars = [
            'message' => $response->getReasonPhrase(),
            'url' => h($url),
            'code' => $code,
        ];

        $this->controller->set($viewVars);
        $this->controller->viewBuilder()->setOption('serialize', array_keys($viewVars));

        $this->controller->setResponse($response);

        return $this->_outputMessage($template);
    }
}
