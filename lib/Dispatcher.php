<?php

namespace AppName;

use AppName\ParallelTaskProcessor;

class Dispatcher
{
    public function dispatch($requestUriPath)
    {
        foreach (Route::$routes['GET'] as $route => $action) {
             $json = file_get_contents('php://input');
             $data = json_decode($json);
              if ($route === $requestUriPath) {
                return $this->executeAction($action, $data);
            }
        }
        http_response_code(404);
        echo '404 Not Found';
    }

    public function executeAction($action, $data)
    {
        if (is_callable($action)) {
            return $action();
        }
        list($controller, $method) = explode('@', $action);
        $controller = 'AppName\\'.$controller;
        $obj = new $controller();
        return $obj->$method($data);
    }
}
