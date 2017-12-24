Controllers
===========

Controllers will be called after matching a URL.

The framework includes a base *Controller* class which can be extended.

The base controller has a property *crispySystem* which contains a copy of the *Container* class for Dependency Injection.

Controllers must return a string.

Example
-------

.. code-block:: php

    <?php

    namespace App\Controllers;

    use StevenLiebregt\CrispySystem\Controllers\Controller;
    use StevenLiebregt\CrispySystem\View\SmartyView;

    class ProductsController extends Controller
    {
        private $view;

        public __construct(SmartyView $view)
        {
            $this->view = $view;
        }

        public function index()
        {
            return $this->view
                ->template('index.tpl')
                ->display();
        }

        public function item($id)
        {
            return $this->view
                ->template('item.tpl')
                ->with([
                    'id' => $id,
                ])
                ->display();
        }
    }