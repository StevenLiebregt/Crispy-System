<?php

namespace StevenLiebregt\CrispySystem\View;

use StevenLiebregt\CrispySystem\CrispySystem;
use StevenLiebregt\CrispySystem\Helpers\Config;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class SmartyView
 * @package StevenLiebregt\CrispySystem\View
 * @author Steven Liebregt <stevenliebregt@outlook.com>
 * @since 1.0.0
 */
class SmartyView
{
    /**
     * @var \Smarty Smarty instance
     */
    protected $smarty;

    /**
     * @var string Filename/location
     */
    protected $template;

    /**
     * SmartyView constructor.
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->smarty = new \Smarty();

        // Set Smarty template dir
        $this->smarty->setTemplateDir(ROOT);

        // Set Smarty compile options
        $this->smarty->setCompileDir(ROOT . 'storage/smarty/compile');
        if (DEVELOPMENT) {
            $this->smarty->setForceCompile(true); // Always re-compile on development environments
        }

        // Set Smarty caching options
        $this->smarty->setCacheDir(ROOT . 'storage/smarty/cache');
        if (DEVELOPMENT) {
            $this->smarty->setCaching(false);
        } else {
            $this->smarty->setCaching(true);
        }

        // Load Smarty plugins
        $finder = (new Finder())
            ->files()
            ->name('/.+\..+\.php/')
            ->in(__DIR__ . '/smarty-plugins');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $parts = explode('.', $file->getFilename());
            require_once $file->getFileInfo();
            try {
                $this->smarty->registerPlugin($parts[0], $parts[1], 'smarty_' . $parts[0] . '_' . $parts[1]);
            } catch (\SmartyException $e) {
                showPlainError($e->getMessage());
            }
        }

        // Assign Smarty configuration
        $this->smarty->assign('crispysystem', [
            'version' => CrispySystem::VERSION,
            'config' => Config::get(),
        ]);

        // Set default template dir
        $this->setTemplateDir(ROOT . 'resources/templates');
    }

    /**
     * Set the template dir
     * @param string $dir
     * @since 1.0.0
     */
    public function setTemplateDir(string $dir)
    {
        $this->smarty->setTemplateDir($dir);
    }

    /**
     * Sets the template
     * @param string $file
     * @return SmartyView
     * @since 1.0.0
     */
    public function template(string $file) : SmartyView // TODO : Add checking if template exists
    {
        $this->template = $file;

        return $this;
    }

    /**
     * Assigns data to Smarty
     * @param array $data
     * @return SmartyView
     * @since 1.0.0
     */
    public function with(array $data) : SmartyView
    {
        foreach ($data as $k => $v) {
            $this->smarty->assign($k, $v);
        }

        return $this;
    }

    /**
     * Returns compiled template
     * @return string Compiled template
     * @throws \Exception
     * @throws \SmartyException
     */
    public function display() : string
    {
        return $this->smarty->fetch($this->template);
    }
}