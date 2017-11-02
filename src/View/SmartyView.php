<?php

namespace StevenLiebregt\CrispySystem\View;

use StevenLiebregt\CrispySystem\CrispySystem;
use StevenLiebregt\CrispySystem\Helpers\Config;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class SmartyView implements IView
{
    /**
     * @var \Smarty
     */
    protected $smarty;

    /**
     * @var
     */
    protected $template;

    /**
     * SmartyView constructor.
     */
    public function __construct()
    {
        $this->smarty = new \Smarty();

        /**
         * Set Smarty template options
         */
        $this->smarty->setTemplateDir(ROOT);

        /**
         * Set Smarty compile options
         */
        $this->smarty->setCompileDir(ROOT . 'storage/smarty/compile');
        if (DEVELOPMENT) {
            $this->smarty->setForceCompile(true); // Always re-compile on development environments
        }

        /**
         * Set Smarty caching options
         */
        $this->smarty->setCacheDir(ROOT . 'storage/smarty/cache');
        if (DEVELOPMENT) {
            $this->smarty->setCaching(false);
        } else {
            $this->smarty->setCaching(true);
        }

        /**
         * Smarty load plugins
         */
        $finder = (new Finder())
            ->files()
            ->name('/.+\..+\.php/')
            ->in(__DIR__ . '/smarty-plugins');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $parts = explode('.', $file->getFilename());
            require_once $file->getFileInfo();
            $this->smarty->registerPlugin($parts[0], $parts[1], 'smarty_' . $parts[0] . '_' . $parts[1]);
        }

        /**
         * Smarty assign config
         */
        $this->smarty->assign('config', Config::get());
        $this->smarty->assign('crispysystem', [
            'version' => CrispySystem::VERSION,
        ]);
    }

    /**
     * @param string $dir
     */
    public function setTemplateDir(string $dir) : void
    {
        $this->smarty->setTemplateDir($dir);
    }

    /**
     * Sets the template
     * @param string $file
     * @return SmartyView
     */
    public function template(string $file) : SmartyView // TODO-PR1 : SL : Add checking if template exists
    {
        $this->template = $file;

        return $this;
    }

    /**
     * @param array $data
     * @return SmartyView
     */
    public function with(array $data) : SmartyView
    {
        foreach ($data as $k => $v) {
            $this->smarty->assign($k, $v);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function display() : string
    {
        return $this->smarty->fetch($this->template);
    }
}