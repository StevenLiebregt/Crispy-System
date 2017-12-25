<?php

use StevenLiebregt\CrispySystem\Helpers\Config;

function smarty_function_config(array $params, Smarty_Internal_Template $template)
{
    $key = isset($params['key']) ? $params['key'] : null;

    if (!isset($params['key'])) {
        return false;
    }

    return Config::get($key);
}