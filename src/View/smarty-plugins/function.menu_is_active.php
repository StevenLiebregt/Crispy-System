<?php

/**
 * Returns a class if the given route(s) matches the current path
 * @param array $params
 * @param Smarty_Internal_Template $template
 * @return mixed|string
 * @since 1.0.0
 */
function smarty_function_menu_is_active(array $params, Smarty_Internal_Template $template)
{
    $class = 'active';
    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $path = $request->getPathInfo();
    $active = false;

    if (isset($params['class'])) {
        $class = $params['class'];
    }

    if (isset($params['name'])) { // It's a route name
        if (is_array($params['name'])) {
            foreach ($params['name'] as $routeName) {
                $route = \StevenLiebregt\CrispySystem\Routing\Router::getRouteByName($routeName);
                if ($route->isMatch($path)) {
                    $active = true;
                }
            }
        } else {
            $route = \StevenLiebregt\CrispySystem\Routing\Router::getRouteByName($params['name']);
            if ($route->isMatch($path)) {
                $active = true;
            }
        }

        if ($active) {
            return $class;
        } else {
            return '';
        }
    }
}