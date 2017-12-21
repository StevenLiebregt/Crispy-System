Getting started
===============

To get started with CrispySystem quicly, read the following. If you want to know more about the individual components, follow the links at the bottom of this page.

Quick start
-----------

**Directory structure**

Create the following directory structure::

    project_root/
        app/
        config/
        public/
            index.php
        storage/
        vendor/
        composer.json

**Index**

To create a CrispySystem application, create an `index.php` in the `public` folder and put the following content in it:

.. code-block:: php

    <?php

        define('DEVELOPMENT', true);
        define('ROOT', '/../');

**URL rewriting**

In order for the framework to function correctly, all requests will need to be rewritten to the `index.php`.

Components
----------

.. toctree::
    :maxdepth: 1

    routing
    controllers
    models