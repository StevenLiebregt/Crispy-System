<?php

/**
 * Assigns the variable `title` to the template, it's composed of a given pagename followed by a pipe and the site-title
 * @param array $params
 * @param Smarty_Internal_Template $template
 * @return bool
 * @example Pagename | Sitename
 * @since 1.0.0
 */
function smarty_function_title(array $params, Smarty_Internal_Template $template)
{
    if (!isset($params['page'])) {
        return false;
    } else {
        $page = $params['page'];
    }
    $title = $page . ' | ' . \StevenLiebregt\CrispySystem\Helpers\Config::get('system.title');

    $template->assign('title', $title);
}