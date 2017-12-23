<?php

namespace StevenLiebregt\CrispySystem\Helpers;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class Config
 * @package StevenLiebregt\CrispySystem\Helpers
 * @author Steven Liebregt <stevenliebregt@outlook.com>
 * @since 1.0.0
 */
class Config
{
    /**
     * @var array Config read from cached file
     */
    private static $config = [];

    public static function init()
    {
        $file = ROOT . 'storage/crispysystem.config.php';

        if (!is_readable($file)) {
            showPlainError('The file `crispysystem.config.php` in the `storage` directory is not readable');
        }

        $config = unserialize(file_get_contents($file));

        static::$config = $config;
    }

    public static function cache()
    {
        $cache = [];

        $finder = (new Finder())
            ->files()
            ->name('/.+\.php/')
            ->in(ROOT . 'config');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $category = str_ireplace('.php', '', $file->getFilename());
            $config = require $file->getRealPath();

            $cache[$category] = $config;
        }

        file_put_contents(ROOT . 'storage/crispysystem.config.php', serialize($cache));
    }

    public static function get($key = null)
    {
        if (is_null($key)) {
            return static::$config;
        }
        // Key is formatted like database.default.driver
        $config = static::$config;
        $key = explode('.', $key);
        foreach ($key as $k) {
            if (!isset($config[$k])) {
                return null;
            }
            $config = $config[$k];
        }
        return $config;
    }
}