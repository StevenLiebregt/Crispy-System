View
====

For views this framework uses the templating engine *Smarty 3* <https://www.smarty.net>.

Setting template directory
--------------------------

Use this method to set the template directory. From this directory templates will be sought using the *SmartyView::display* method.

The default template directory is: *ROOT/resources/templates*.

Setting the template
--------------------

The method *SmartyView::template* is used to set the template to be displayed.

The method takes one parameter, this needs to be the template filename including the extension.

Displaying
----------

**! Note !** Before you use this method, make sure you have set a template.

To display the template, the value of the *SmartyView::display* method needs to be returned from the controller.

Assigning data to templates
---------------------------

To assign data to a variable inside a template, you can use the *SmartyView::with* method, which takes an array, with *key / value* definitions.

.. code-block:: php

    <?php

    ->with([
        'id' => 1235,
        'foo' => [
            'test',
            'bar',
        ],
    ]);

The above would allow you to access the variables *id* and *foo* inside the template which give you *1235* and the array with values *test* and *bar* respectively.

Example
-------

.. code-block:: php

    <?php

    $view = new SmartyView();
    $view->setTemplateDir('/root/templates');
    $view->template('home.tpl');
    return $view->display();

In the above example, the class looks for the template *home.tpl* in the directory */root/templates*.