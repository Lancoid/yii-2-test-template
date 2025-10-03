<?php

declare(strict_types=1);

namespace app\modules\track\components\errorHandler;

use Yii;
use yii\base\UserException;
use yii\db\Exception;
use yii\web\ErrorHandler;
use yii\web\Response;
use yii\web\View;

class ApiErrorHandler extends ErrorHandler
{
    protected function renderException($exception): void
    {
        if (Yii::$app->has('response')) {
            /** @var Response $response */
            $response = Yii::$app->getResponse();
            $response->isSent = false;
            $response->stream = [];
            $response->data = null;
            $response->content = null;
        } else {
            $response = new Response();
        }

        $response->setStatusCode($exception->getCode());

        $useErrorView = Response::FORMAT_HTML === $response->format && (!YII_DEBUG || $exception instanceof UserException);

        if ($useErrorView && null !== $this->errorAction) {
            /** @var View $view */
            $view = Yii::$app->view;
            $view->clear();
            $result = Yii::$app->runAction($this->errorAction);
            if ($result instanceof Response) {
                $response = $result;
            } else {
                $response->data = $result;
            }
        } elseif (Response::FORMAT_HTML === $response->format) {
            if ($this->shouldRenderSimpleHtml()) {
                // AJAX request
                $response->data = '<pre>' . $this->htmlEncode(static::convertExceptionToString($exception)) . '</pre>';
            } else {
                // if there is an error during error rendering it's useful to
                // display PHP error in debug mode instead of a blank screen
                if (YII_DEBUG) {
                    ini_set('display_errors', 1);
                }
                $file = $useErrorView ? $this->errorView : $this->exceptionView;
                $response->data = $this->renderFile($file, [
                    'exception' => $exception,
                ]);
            }
        } elseif (Response::FORMAT_RAW === $response->format) {
            $response->data = static::convertExceptionToString($exception);
        } else {
            $response->data = $this->convertExceptionToArray($exception);
        }

        $response->send();
    }

    protected function convertExceptionToArray($exception): array
    {
        $array = [
            'error' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];

        if (YII_DEBUG) {
            $array['type'] = get_class($exception);

            if (!$exception instanceof UserException) {
                $array['file'] = $exception->getFile();
                $array['line'] = $exception->getLine();
                $array['stack-trace'] = explode("\n", $exception->getTraceAsString());
                if ($exception instanceof Exception) {
                    $array['error-info'] = $exception->errorInfo;
                }
            }
            if (($prev = $exception->getPrevious()) !== null) {
                $array['previous'] = $this->convertExceptionToArray($prev);
            }
        }

        return $array;
    }
}
