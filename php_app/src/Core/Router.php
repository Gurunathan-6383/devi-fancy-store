<?php
namespace App\Core;

class Router
{
    private $routes = [];
    private $middleware = [];
    private $groupMiddleware = [];
    private $groupPrefix = '';
    private $notFoundHandler;

    public function add($method, $path, $handler, $middleware = [])
    {
        $path = $this->groupPrefix . $path;
        $allMiddleware = array_merge($this->groupMiddleware, $middleware);
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $allMiddleware,
        ];
    }

    public function get($path, $handler, $middleware = [])
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    public function post($path, $handler, $middleware = [])
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    public function put($path, $handler, $middleware = [])
    {
        $this->add('PUT', $path, $handler, $middleware);
    }

    public function patch($path, $handler, $middleware = [])
    {
        $this->add('PATCH', $path, $handler, $middleware);
    }

    public function delete($path, $handler, $middleware = [])
    {
        $this->add('DELETE', $path, $handler, $middleware);
    }

    public function group($prefix, $middleware, $callback)
    {
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;
        $this->groupPrefix = $previousPrefix . $prefix;
        $this->groupMiddleware = array_merge($previousMiddleware, $middleware);
        $callback($this);
        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    public function setNotFoundHandler($handler)
    {
        $this->notFoundHandler = $handler;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/') {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . trim($uri, '/');
        if ($uri === '') $uri = '/';

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                $request = new Request();
                $request->setParams($matches);

                foreach ($route['middleware'] as $mw) {
                    $instance = new $mw();
                    $result = $instance->handle($request);
                    if ($result === false) return;
                }

                $handler = $route['handler'];
                if (is_string($handler)) {
                    list($class, $method) = explode('@', $handler);
                    $class = "App\\Controllers\\{$class}";
                    $controller = new $class();
                    $controller->$method($request);
                } elseif (is_callable($handler)) {
                    $handler($request);
                }
                return;
            }
        }

        if ($this->notFoundHandler) {
            call_user_func($this->notFoundHandler);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Not Found']);
        }
    }
}
