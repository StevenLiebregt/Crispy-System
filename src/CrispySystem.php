<?php

namespace StevenLiebregt\CrispySystem;

use StevenLiebregt\CrispySystem\Container\Container;
use StevenLiebregt\CrispySystem\Helpers\Config;
use StevenLiebregt\CrispySystem\Routing\Route;
use StevenLiebregt\CrispySystem\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CrispySystem
 * @package StevenLiebregt\CrispySystem
 * @author Steven Liebregt <stevenliebregt@outlook.com>
 * @since 1.0.0
 */
class CrispySystem extends Container
{
    const VERSION = '1.1.4';

    /**
     * @var Response $response
     */
    private $response;

    /**
     * CrispySystem constructor.
     * @since 1.0.0
     */
    public function __construct()
    {
        require __DIR__ . '/Helpers/helpers.php'; // Load basic helpers

        if (!file_exists(ROOT . 'storage') || !is_dir(ROOT . 'storage')) {
            showPlainError('Storage directory does not exist, please create one in your root');
        }

        if (!is_writable(ROOT . 'storage')) {
            showPlainError('Storage directory is not writable, try running `chmod -R 0777 storage` in your root');
        }

        if (!file_exists(ROOT . 'config') || !is_dir(ROOT . 'config')) {
            showPlainError('Config directory does not exist, please create one in your root');
        }

        // Pre-load the cached configuration
        if (!file_exists(ROOT . 'storage/crispysystem.config.php') || DEVELOPMENT) {
            Config::cache();
        }
        Config::init();
    }

    /**
     * Sends the response to the user
     * @param Request|null $request
     * @return string
     * @since 1.0.0
     */
    public function run(Request $request = null)
    {
        $request = (is_null($request) ? Request::createFromGlobals() : $request);

        $this->response = $this->handle($request);

        $this->response->send();
    }

    /**
     * Turn a request into a response
     * @param Request $request
     * @return Response
     * @since 1.0.0
     */
    private function handle(Request $request) : Response
    {
        $router = $this->getInstance(Router::class);

        if ($router->match($request->getPathInfo(), $request->getMethod())) { // A match has been found
            /** @var Route $match */
            $match = $router->getMatch();

            // Check if the handler is a closure or not
            $handler = $match->getHandler();

            if (is_object($handler) && $handler instanceof \Closure) {
                try {
                    $content = $this->resolveFunction($handler);
                } catch (\Exception $e) {
                    if (DEVELOPMENT) {
                        showPlainError('Something went wrong while resolving a closure, details below:', false);
                        pr($e);
                        exit;
                    }
                    return $this->respond(500);
                }
            } else {
                $controller = substr($handler, 0, stripos($handler, '.'));
                $method = substr($handler, (stripos($handler, '.') + 1));

                try {
                    $instance = $this->getInstance($controller);
                    $content = $this->resolveMethod($instance, $method, $match->getParameters());
                } catch (\Exception $e) {
                    if (DEVELOPMENT) {
                        showPlainError('Something went wrong while resolving a controller, details below:', false);
                        pr($e);
                        exit;
                    }
                    return $this->respond(500);
                }
            }

            return $this->respond(200, $content);
        }

        // No match found
        return $this->respond(404);
    }

    /**
     * @param int $code
     * @param string $content
     * @return Response
     * @since 1.0.0
     */
    private function respond(int $code, string $content = '') : Response
    {
        switch ($code) {
            case 404:
                $content = '<h1>404, <small>Not Found</small></h1>';
                break;
            case 500:
                $content = '<h1>500, <small>Internal Server Error</small></h1>';
                break;
        }

        $response = new Response(
            $content,
            $code
        );

        return $response;
    }
}
