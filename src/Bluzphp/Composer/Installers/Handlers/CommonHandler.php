<?php
/**
 * Created by PhpStorm.
 * User: bshmch
 * Date: 12.06.15
 * Time: 22:47
 */

namespace Bluzphp\Composer\Installers\Handlers;


use Bluzphp\Composer\Installers\BluzModuleInstallerPlugin;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class CommonHandler
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var BluzModuleInstallerPlugin
     */
    protected $plugin;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $patterns = [];

    public function __construct(BluzModuleInstallerPlugin $plugin)
    {
        $this->setPlugin($plugin);
        $this->setFs(new Filesystem());
        $this->setFinder(new Finder());

        $this->setup();
    }

    /**
     * Clean for common
     */
    protected function setup()
    {

    }

    /**
     * @return array
     */
    public function getPatterns()
    {
        return $this->patterns;
    }

    /**
     * @param $basename
     * @return bool
     */
    protected function hasPattern($basename)
    {
        return array_key_exists($basename, $this->patterns);
    }

    /**
     * @param $basename
     * @return bool
     */
    protected function getPattern($basename)
    {
        return sprintf($this->patterns[$basename], $basename);
    }

    /**
     * @param array $basenames
     * @param string $pattern
     */
    protected function setPattern($basename, $pattern)
    {
        $this->patterns[$basename] = $pattern;
    }

    /**
     * @param array $basenames
     * @param string $pattern
     */
    protected function setPatterns(array $basenames, $pattern)
    {
        foreach ($basenames as $basename) {
            $this->setPattern($basename, $pattern);
        }
    }

    public function install()
    {
        $this->getFinder()->directories()->in(
            $this->getPlugin()->getModulesPath() . DIRECTORY_SEPARATOR . $this->getPlugin()->getModuleName()
        );
        $this->getFinder()->path($this->getPath())->ignoreUnreadableDirs();

        foreach ($this->getFinder() as $file) {
            $target = null;

            if (!$this->hasPattern($file->getBasename())) {
                continue;
            }

            $this->getFs()->rename(
                $file->getRealPath(),
                $this->getPattern($file->getBasename())
            );
        }
    }

    public function uninstall()
    {
        foreach ($this->getPatterns() as $basename => $pattern) {
            $this->getFs()->remove($this->getPattern($basename));
        }
    }

    public function cleanup()
    {
        if ($this->getFinder()->files()->count() < 1) {
            $this->getFs()->remove(
                $this->getPlugin()->getRootPath() . DIRECTORY_SEPARATOR
                . $this->getPlugin()->getModulesPath() . DIRECTORY_SEPARATOR
                . $this->getPlugin()->getModuleName() . DIRECTORY_SEPARATOR
                . $this->getPath()
            );
        }
    }

    /**
     * @param Filesystem $fs
     */
    public function setFs($fs)
    {
        $this->fs = $fs;
    }

    /**
     * @return Filesystem
     */
    protected function getFs()
    {
        return $this->fs;
    }

    /**
     * @return Finder
     */
    public function getFinder()
    {
        return $this->finder;
    }

    /**
     * @param Finder $finder
     */
    public function setFinder($finder)
    {
        $this->finder = $finder;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return BluzModuleInstallerPlugin
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * @param BluzModuleInstallerPlugin $plugin
     */
    public function setPlugin($plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Return path public directory
     *
     * @return mixed
     */
    protected function getPublicPath()
    {
        return $this->getPlugin()->getRootPath() . DIRECTORY_SEPARATOR . 'public';
    }
}