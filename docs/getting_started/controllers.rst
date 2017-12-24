Controllers
===========

Controllers will be called after matching a URL.

Controllers must return a string.

Example
-------

.. code-block:: php

    <?php

    namespace App\Controllers;

    use StevenLiebregt\CrispySystem\View\SmartyView;

    class ProductsController
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