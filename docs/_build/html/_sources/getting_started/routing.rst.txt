Routing
=======

Routing is an essential part of the framework, it allows you to map an action to a URL.

The namespace for this component is *StevenLiebregt\\CrispySystem\\Routing*. In this namespace are the two following classes: *Router* and *Route*.

Methods
-------

Each of the following HTTP methods have a static *Route* method with the same name.

 * GET (*Route::get*)
 * POST (*Route::post*)
 * PUT (*Route::put*)
 * PATCH (*Route::patch*)
 * DELETE (*Route::delete*)

Each of these methods take 2 arguments:

 * The URI path to match, this needs to be a string
 * The handler, this can be a closure which returns a string, or a (string) path to a *Controller* class and method.

Handler
--------

**Closure**

.. code-block:: php

    Route::get('/home', function() {
        return 'Welcome home!';
    });

**Controller**

.. code-block:: php

    Route::get('/home', 'HomeController.welcome');

This route's action will return the *welcome* method from the *HomeController*.

Naming
------

Routes can be given names, you can use these names to retrieve a route. For example, you can use this in a template / view to fill a `href` element with a named url. This way you can change the url without editing all templates.

The *Route::setName* method is chained off the HTTP method method.

.. code-block:: php

    Route::get('/foo', function() {
        return 'foo';
    })->setName('bar');

You can use *Router::getRouteByName('bar')* to retrieve the above route definition.

Parameters
----------

To add a variable part in your route, you need to add a section wrapped in curly braces. Then you need to chain the *Route::where* method off the HTTP method method. This *Route::where* method takes the following 2 parameters:

 * The name of the variable part, this needs to be the same as what is between the curly braces.
 * A regular expression, to which the part will need to match. If the regular expression has a capturing group, the value will be auto-wired into the handler.

.. code-block:: php

    Route::get('/products/{id}', function($id) {
        return 'I want product id: ' . $id;
    })->where('id', '(\\d+)');

This route would match */products/193* and would return '*I want product id: 193*', but it wouldn't match */products/bar* since the parameter part does not match the regular expression.

Grouping
--------

TODO