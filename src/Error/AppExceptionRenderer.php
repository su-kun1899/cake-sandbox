<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\Error\Debugger;
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
        $exception = $this->error;
        $code = $this->getHttpCode($exception);
        $method = $this->_method($exception);
        $template = $this->_template($exception, $method, $code);
        $this->clearOutput();

        if (method_exists($this, $method)) {
            return $this->_customMethod($method, $exception);
        }

        $message = $this->_message($exception, $code);
        $url = $this->controller->getRequest()->getRequestTarget();
        $response = $this->controller->getResponse();

        if ($exception instanceof CakeException) {
            /** @psalm-suppress DeprecatedMethod */
            foreach ((array)$exception->responseHeader() as $key => $value) {
                $response = $response->withHeader($key, $value);
            }
        }
        if ($exception instanceof HttpException) {
            foreach ($exception->getHeaders() as $name => $value) {
                $response = $response->withHeader($name, $value);
            }
        }
        $response = $response->withStatus($code);

        $viewVars = [
            'message' => $message,
            'url' => h($url),
            'error' => $exception,
            'code' => $code,
        ];
        $serialize = ['message', 'url', 'code'];

        $isDebug = Configure::read('debug');
        if ($isDebug) {
            $trace = (array)Debugger::formatTrace(
                $exception->getTrace(),
                [
                    'format' => 'array',
                    'args' => false,
                ]
            );
            $origin = [
                'file' => $exception->getFile() ?: 'null',
                'line' => $exception->getLine() ?: 'null',
            ];
            // Traces don't include the origin file/line.
            array_unshift($trace, $origin);
            $viewVars['trace'] = $trace;
            $viewVars += $origin;
            $serialize[] = 'file';
            $serialize[] = 'line';
        }
        $this->controller->set($viewVars);
        $this->controller->viewBuilder()->setOption('serialize', $serialize);

        if ($exception instanceof CakeException && $isDebug) {
            $this->controller->set($exception->getAttributes());
        }
        $this->controller->setResponse($response);

        if (str_starts_with($url, '/api')) {
            // api の場合は json で返す
            $this->controller->RequestHandler->renderAs($this->controller, 'json');
        }

        return $this->_outputMessage($template);
    }
}
