Getting started
===============

To get started with CrispySystem quicly, read the following. If you want to know more about the individual components, follow the links at the bottom of this page.

Quick start
-----------

**Recomended directory structure**

Create the following directory structure::

    project_root/
        app/
        config/
        public/
            index.php
        storage/
        vendor/
        composer.json

**! Note !** The `config` and `storage` directories need to be readable and writable.

**Index**

To create a CrispySystem application, create an `index.php` in the `public` folder and put the following content in it:

.. code-block:: php

    <?php

    use StevenLiebregt\CrispySystem\CrispySystem;
    use StevenLiebregt\CrispySystem\Routing\Router;
    use StevenLiebregt\CrispySystem\Routing\Route;

    define('DEVELOPMENT', true);
    define('ROOT', './../');

    require ROOT . 'vendor/autoload.php';

    $crispySystem = new CrispySystem();

    Router::group()->routes(function() {

        Route::get('/', function() {
            return 'Hello World';
        });

    });

    $crispySystem->run();

**URL rewriting**

In order for the framework to function correctly, all requests will need to be rewritten to the `index.php`.

Components
----------

.. toctree::
    :maxdepth: 1

    routing
    view
    controllers
    models
    dependency_injection