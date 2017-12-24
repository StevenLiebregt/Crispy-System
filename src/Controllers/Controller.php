<?php

namespace StevenLiebregt\CrispySystem\Controllers;

use StevenLiebregt\CrispySystem\CrispySystem;

abstract class Controller
{
    /**
     * @var CrispySystem CrispySystem instance
     */
    protected $crispySystem;

    public function setCrispySystem(CrispySystem $crispySystem)
    {
        $this->crispySystem = $crispySystem;
    }
}