<?php

use App\Controllers\AbstractHttpException;
use App\Helpers\RoutingHelper;
use App\Libs\AuthenticationMiddleware;
use Phalcon\Http\Request;

try {
    /* ------------------------------ Project setup -------------------------------------------- */
    date_default_timezone_set('UTC'); // Setting the default time for the project to UTC
    $config = require __DIR__ . '/../app/config/config.php'; // Loading Configs
    require __DIR__ . '/../app/config/loader.php'; // Autoloading classes
    $di = require __DIR__ . '/../app/config/di.php'; // Initializing DI container
    $app = new \Phalcon\Mvc\Micro();
    $app->setDI($di); // Setting DI container
    require __DIR__ . '/../app/config/routes.php'; // Setting up routing
    date_default_timezone_set('UTC'); // Setting the default time for the project to UTC
    /* ----------------------------------------------------------------------------------------- */

    $app->before(
        function () use ($app) {
            $request   = new Request();
            $httpQuery = $request->getQuery();
            $url       = $httpQuery['_url'];

            if (in_array($url, RoutingHelper::AUTHENTICATION_MIDDLEWARE_EXCEPTIONS) ||
                RoutingHelper::checkUrlToExceptionContains($url)) {
                return true;
            }

            if (empty($hash)) {
                // throw new Exception('Invalid request!');
            } else {
                // $auth   = new AuthenticationMiddleware($app);
                // $result = $auth->isUserAuthenticated();

            }

            $result = true; //temporary

            if (!$result) {
                $app->response->setContent(json_encode('Invalid token!'))->send();
                $app->response->send();
                throw new Exception('Bad Response');
            }
            return true;
        }
    );

    $app->after(
        function () use ($app) {

            $return = $app->getReturnedValue();

            if (is_array($return)) {
                $app->response->setContent(json_encode(array(
                    'status'  => 'success',
                    'code'    => 200,
                    'data'    => $return['data'],
                    'message' => $return['message']
                )));
            } elseif (!strlen($return)) {
                $app->response->setStatusCode('204', 'No Content');
            } else {
                throw new Exception('Bad Response');
            }
            $app->response->send();
        }
    );

    $app->handle();
} catch (AbstractHttpException $e) {

    $response = $app->response;
    $response->setStatusCode($e->getCode(), $e->getMessage());

    $result = [
        AbstractHttpException::KEY_CODE    => $e->getCode(),
        AbstractHttpException::KEY_MESSAGE => $e->getMessage()
    ];

    $response->setJsonContent($e->getAppError());

    $response->send();
} catch (\Phalcon\Http\Request\Exception $e) {
    $app->response->setStatusCode(400, 'Bad request')
        ->setJsonContent([
            AbstractHttpException::KEY_CODE    => 400,
            AbstractHttpException::KEY_MESSAGE => 'Bad request'
        ])
        ->send();
} catch (\Exception $e) {
    $result = [
        AbstractHttpException::KEY_CODE    => 500,
        AbstractHttpException::KEY_MESSAGE => $e->getMessage()
    ];

    $app->response->setStatusCode(500, 'Internal Server Error')
        ->setJsonContent($result)
        ->send();
}
