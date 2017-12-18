<?php

namespace StevenLiebregt\CrispySystem\Routing;

class Router
{
    /**
     * @var Router
     */
    protected static $instance;

    /**
     * @var array
     */
    protected static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    /**
     * @var string
     */
    protected $pathPrefix = '';

    /**
     * @var string
     */
    protected $handlerPrefix = '';

    /**
     * @var string
     */
    protected $namePrefix = '';

    /**
     * @var Route
     */
    protected $match;

    /**
     * @return Router
     */
    public static function group() : Router
    {
        static::$instance = new static();

        return static::$instance;
    }

    public static function getInstance() : Router
    {
        return static::$instance;
    }

    /**
     * @param string $prefix
     * @return $this
     */
    public function setPathPrefix(string $prefix) : Router
    {
        $this->pathPrefix = $prefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathPrefix() : string
    {
        return $this->pathPrefix;
    }

    /**
     * @param string $prefix
     * @return $this
     */
    public function setHandlerPrefix(string $prefix) : Router
    {
        $this->handlerPrefix = $prefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getHandlerPrefix() : string
    {
        return $this->handlerPrefix;
    }

    /**
     * @param string $namePrefix
     * @return $this
     */
    public function setNamePrefix(string $namePrefix) : Router
    {
        $this->namePrefix = $namePrefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamePrefix() : string
    {
        return $this->namePrefix;
    }

    /**
     * @param \Closure $closure
     */
    public function routes(\Closure $closure)
    {
        call_user_func($closure);
    }

    /**
     * @param string $verb
     * @param Route $route
     */
    public static function addRoute(string $verb, Route $route)
    {
        static::$routes[strtoupper($verb)][] = $route;
    }

    /**
     * @return array
     */
    public static function getRoutes() : array
    {
        return static::$routes;
    }

    /**
     * @param string $verb
     * @return array
     */
    public static function getRoutesByMethod(string $verb) : array
    {
        return static::$routes[strtoupper($verb)];
    }

    /**
     * Tries to fetch a route by name, returns null if it doesn't exist
     * @param string $name
     * @return null|Route
     */
    public static function getRouteByName(string $name) : Route
    {
        foreach (static::$routes as $routes) {
            /** @var Route $route */
            foreach ($routes as $route) {
                if ($route->getName() === $name) {
                    return $route;
                }
            }
        }

        return null;
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public function match(string $path, string $method) : bool
    {
        /** @var Route $route */
        foreach (static::getRoutesByMethod($method) as $route) {
            $route->createRegex();
            if ($route->isMatch($path)) {
                $this->match = $route;
                return true;
            }
        }
        return false;
    }

    /**
     * @return Route
     */
    public function getMatch() : Route
    {
        return $this->match;
    }
}