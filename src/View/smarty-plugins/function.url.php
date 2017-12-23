<?php

/**
 * Returns the url to a route, by name. If given parameters, those will be filled
 * @param array $params
 * @param Smarty_Internal_Template $template
 * @return mixed|string
 * @since 1.0.0
 */
function smarty_function_url(array $params, Smarty_Internal_Template $template)
{
    if (isset($params['name'])) { // It's a route name
        $route = \StevenLiebregt\CrispySystem\Routing\Router::getRouteByName($params['name']);
        $path = $route->getPath();
        if (isset($params['parameters'])) {
            foreach ($params['parameters'] as $key => $value) {
                $path = str_ireplace('{' . $key . '}', $value, $path);
            }
        }

        return $path;
    }
}