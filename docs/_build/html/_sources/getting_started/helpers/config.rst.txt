Config
======

All config files that reside in *ROOT/config/* will be read and cached into one array, the first-level keys in the array are the names of the config files. So if you have a file called *database.php* and *system.php* in your config directory, the configuration array will look like this:

.. code-block:: php

    [
        'database' => [
            'content from database.php'
        ],
        'system' => [
            'content from system.php'
        ],
    ]

The configuration files must return an array.

Reading the configuration
-------------------------

To read the configuration you can use the *Config::get* method.

If you leave the parameter empty, it will return an array containing all the configuration, if you give a key you want, you'll only receive the value of that key. Multidimensional keys are formatted like this: *database.driver*, this would map to *$config['database']['driver']*.