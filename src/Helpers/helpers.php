<?php

/**
 * @author Steven Liebregt <stevenliebregt@outlook.com>
 */

/**
 * Simple wrapper for the `print_r` function that automatically adds `<pre>` tags
 * @param mixed $data Data to print
 * @since 1.0.0
 */
function pr($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

/**
 * Simple wrapper for the `var_dump` function that automatically adds `<pre>` tags
 * @param mixed $data Data to print
 * @since 1.0.0
 */
function vd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

/**
 * Shows an error message and optionally exits
 * @param string $message Error message to show
 * @param bool $exit Exit or not
 * @since 1.0.0
 */
function showPlainError(string $message, bool $exit = true)
{
    echo '<h3 style="color:darkred;">[ ERROR ]</h3>';
    echo $message;
    if ($exit) {
        exit;
    }
}

/**
 * Usefull for api's, sets the header and returns json_encoded data
 * @param mixed $content Content to json_encode
 * @return string Json_encoded content
 * @since 1.1.2
 */
function jsonify($content) : string
{
    header('Content-Type: application/json');

    return json_encode($content);
}