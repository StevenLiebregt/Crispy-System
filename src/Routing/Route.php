<?php

namespace StevenLiebregt\CrispySystem\Routing;

/**
 * Class Route
 * @package StevenLiebregt\CrispySystem\Routing
 * @author Steven Liebregt <stevenliebregt@outlook.com>
 * @since 1.0.0
 */
class Route
{
    /**
     * @var Router
     */
    protected static $routerInstance;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string|\Closure
     */
    protected $handler;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var string
     */
    protected $regex;

    /**
     * @var array
     */
    protected $parameterNames = [];

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * Route constructor.
     * @param Router $router Router instance
     * @param string $verb Method to match
     * @param string $path Path to match
     * @param string|\Closure $handler Handler to use
     * @since 1.0.0
     */
    public function __construct(Router $router, string $verb, string $path, $handler)
    {
        $this->router = $router;
        $this->path = $this->router->getPathPrefix() . $path;
        if (!is_callable($handler)) {
            $this->handler = $this->router->getHandlerPrefix() . $handler;
        } else {
            $this->handler = $handler;
        }
    }

    /**
     * Add a GET route
     * @param string $path
     * @param $handler
     * @return Route
     * @since 1.0.0
     */
    public static function get(string $path, $handler) : Route
    {
        return static::add('GET', $path, $handler);
    }

    /**
     * Add a POST route
     * @param string $path
     * @param $handler
     * @return Route
     * @since 1.0.0
     */
    public static function post(string $path, $handler) : Route
    {
        return static::add('POST', $path, $handler);
    }

    /**
     * Add a PUT route
     * @param string $path
     * @param $handler
     * @return Route
     * @since 1.0.0
     */
    public static function put(string $path, $handler) : Route
    {
        return static::add('PUT', $path, $handler);
    }

    /**
     * Add a PATCH route
     * @param string $path
     * @param $handler
     * @return Route
     * @since 1.0.0
     */
    public static function patch(string $path, $handler) : Route
    {
        return static::add('PATCH', $path, $handler);
    }

    /**
     * Add a DELETE route
     * @param string $path
     * @param $handler
     * @return Route
     * @since 1.0.0
     */
    public static function delete(string $path, $handler) : Route
    {
        return static::add('DELETE', $path, $handler);
    }

    /**
     * Add a route that matches any HTTP verb
     * @param string $path
     * @param $handler
     * @return array
     * @since 1.4.0
     */
    public static function any(string $path, $handler) : array
    {
        return [
            static::get($path, $handler),
            static::post($path, $handler),
            static::put($path, $handler),
            static::patch($path, $handler),
            static::delete($path, $handler),
        ];
    }

    /**
     * Add a route that matches the given HTTP verbs
     * @param array $verbs
     * @param string $path
     * @param $handler
     * @return array
     * @since 1.4.0
     */
    public static function match(array $verbs, string $path, $handler) : array
    {
        $routes = [];

        foreach ($verbs as $verb) {
            switch (strtolower($verb)) {
                case 'get':
                    $routes[] = static::get($path, $handler);
                    break;
                case 'post':
                    $routes[] = static::post($path, $handler);
                    break;
                case 'put':
                    $routes[] = static::put($path, $handler);
                    break;
                case 'patch':
                    $routes[] = static::patch($path, $handler);
                    break;
                case 'delete':
                    $routes[] = static::delete($path, $handler);
                    break;
            }
        }

        return $routes;
    }

    /**
     * Creates a new route
     * @param string $verb
     * @param string $path
     * @param $handler
     * @return Route
     * @since 1.0.0
     */
    protected static function add(string $verb, string $path, $handler) : Route
    {
        /** @var Router $router */
        $router = static::getRouterInstance();

        $route = new static($router, $verb, $path, $handler);

        Router::addRoute($verb, $route);

        return $route;
    }

    /**
     * @param string $parameter
     * @param string $regex
     * @return Route
     * @since 1.0.0
     */
    public function where(string $parameter, string $regex) : Route
    {
        $this->rules[$parameter] = $regex;

        return $this;
    }

    /**
     * @param string $name
     * @param bool $override
     * @return Route
     * @since 1.0.0
     */
    public function setName(string $name, bool $override = false) : Route
    {
        if ($override) {
            $this->name = $name;
            return $this;
        }
        $this->name = ($this->router->getNamePrefix() === '' ? $name : $this->router->getNamePrefix() . '.' . $name);

        return $this;
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public function getName() : string
    {
        return (is_null($this->name) ? '' : $this->name);
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * @return callable|\Closure|string
     * @since 1.0.0
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function getParameters() : array
    {
        return $this->parameters;
    }

    /**
     * Creates a regular expression to match the route
     * @since 1.0.0
     */
    public function createRegex()
    {
        $parts = explode('/', ltrim($this->path, '/'));
        $regex = '/^'; // Matches the start of the string
        foreach ($parts as $part) {
            $regex .= '\/'; // Matches starting slash
            // Handle non-variable parts
            if (stripos($part, '{') === false &&
                stripos($part, '}') === false) {
                $regex .= $part; // Exactly match this part
                continue;
            }
            // Handle variable parts
            $name = str_ireplace(['{', '}'], '', $part);
            $this->parameterNames[] = $name;
            $this->parameters[$name] = null;
            // Check if a rule exists for this part
            if (isset($this->rules[$name])) {
                $regex .= $this->rules[$name];
                continue;
            }
            // No rule exists, match all except slashes
            $regex .= '([^\/]+)';
        }
        $regex .= '$/'; // Matches end of string
        $this->regex = $regex;
    }

    /**
     * @param string $path
     * @return bool
     * @since 1.0.0
     */
    public function isMatch(string $path) : bool
    {
        if (preg_match_all($this->regex, $path, $matches) !== 0 &&
            !empty($matches) &&
            !empty($matches[0])) {
            array_shift($matches);
            foreach ($matches as $i => $match) {
                $this->parameters[$this->parameterNames[$i]] = $match[0];
            }
            return true;
        }
        return false; // Match not found
    }

    /**
     * @return Router
     * @since 1.0.0
     */
    protected static function getRouterInstance() : Router
    {
        static::$routerInstance = Router::getInstance();

        return static::$routerInstance;
    }
}
