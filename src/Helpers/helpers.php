<?php

/**
 * Simple wrapper for the `print_r` function that automatically adds `<pre>` tags
 * @param $data
 */
function pr($data) : void
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

/**
 * Simple wrapper for the `var_dump` function that automatically adds `<pre>` tags
 * @param $data
 */
function vd($data) : void
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

/**
 * @param string $message
 * @param bool $exit
 */
function showPlainError(string $message, bool $exit = true)
{
    echo '<h3 style="color:darkred;">[ ERROR ]</h3>';
    echo $message;
    if ($exit) {
        exit;
    }
}