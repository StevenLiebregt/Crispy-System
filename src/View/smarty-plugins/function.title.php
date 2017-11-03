<?php

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