<?php

namespace StevenLiebregt\CrispySystem\Routing;

/**
 * Class Router
 * @package StevenLiebregt\CrispySystem\Routing
 * @author Steven Liebregt <stevenliebregt@outlook.com>
 * @since 1.0.0
 */
class Router
{
    /**
     * @var Router
     */
    protected static $instance;

    /**
     * @var array List of routes
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
     * @since 1.0.0
     */
    public static function group() : Router
    {
        static::$instance = new static();

        return static::$instance;
    }

    /**
     * @return Router
     * @since 1.0.0
     */
    public static function getInstance() : Router
    {
        static::$instance = static::$instance ? static::$instance : new static();

        return static::$instance;
    }

    /**
     * @param string $prefix
     * @return $this
     * @since 1.0.0
     */
    public function setPathPrefix(string $prefix) : Router
    {
        $this->pathPrefix = $prefix;

        return $this;
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public function getPathPrefix() : string
    {
        return $this->pathPrefix;
    }

    /**
     * @param string $prefix
     * @return $this
     * @since 1.0.0
     */
    public function setHandlerPrefix(string $prefix) : Router
    {
        $this->handlerPrefix = $prefix;

        return $this;
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public function getHandlerPrefix() : string
    {
        return $this->handlerPrefix;
    }

    /**
     * @param string $namePrefix
     * @return $this
     * @since 1.0.0
     */
    public function setNamePrefix(string $namePrefix) : Router
    {
        $this->namePrefix = $namePrefix;

        return $this;
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public function getNamePrefix() : string
    {
        return $this->namePrefix;
    }

    /**
     * @param \Closure $closure
     * @since 1.0.0
     */
    public function routes(\Closure $closure)
    {
        call_user_func($closure);
    }

    /**
     * @param string $verb
     * @param Route $route
     * @since 1.0.0
     */
    public static function addRoute(string $verb, Route $route)
    {
        static::$routes[strtoupper($verb)][] = $route;
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public static function getRoutes() : array
    {
        return static::$routes;
    }

    /**
     * @param string $verb
     * @return array
     * @since 1.0.0
     */
    public static function getRoutesByMethod(string $verb) : array
    {
        return static::$routes[strtoupper($verb)];
    }

    /**
     * Tries to fetch a route by name, returns null if it doesn't exist
     * @param string $name
     * @return null|Route
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function getMatch() : Route
    {
        return $this->match;
    }
}