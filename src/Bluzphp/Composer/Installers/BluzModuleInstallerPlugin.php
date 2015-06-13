<?php
/**
 * @author Pavel Machekhin <pavel.machekhin@gmail.com>
 * @created 2015-03-24 12:39
 */

namespace Bluzphp\Composer\Installers;

use Bluzphp\Composer\Installers\Handlers\AssetsHandler;
use Bluzphp\Composer\Installers\Handlers\SourceHandler;
use Bluzphp\Composer\Installers\Handlers\TestsHandler;
use Composer\Composer;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class BluzModuleInstallerPlugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var BluzModuleInstaller
     */
    protected $installer;

    /**
     * Path to root directory
     *
     * @var string
     */
    protected $rootPath;

    /**
     * @var null|Finder
     */
    protected $finder = null;

    /**
     * {@inheritDoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->installer = new BluzModuleInstaller($io, $composer);
        $composer->getInstallationmanager()->addInstaller($this->installer);
    }

    /**
     * Event registration
     *
     * @return array
     */
    public static function getSubscribedEvents() {
        $result = array(
            ScriptEvents::POST_INSTALL_CMD => array(
                array( 'onPostInstallCmd', 0 )
            ),
            ScriptEvents::POST_UPDATE_CMD  => array(
                array( 'onPostUpdateCmd', 0 )
            ),
            PackageEvents::PRE_PACKAGE_UNINSTALL  => array(
                array( 'onPreUninstallCmd', 0 )
            ),
        );
        return $result;
    }

    /**
     * Event processing onPostInstallCmd
     *
     * @param Event $event
     */
    public function onPostInstallCmd(Event $event)
    {
        $this->moduleInstall();
    }


    /**
     * Event processing onPostUpdateCmd
     *
     * @param Event $event
     */
    public function onPostUpdateCmd(Event $event)
    {
        $this->moduleInstall();
    }

    /**
     * Event processing onPostUpdateCmd
     *
     * @param Event $event
     */
    public function onPreUninstallCmd(Event $event)
    {
        $this->moduleUninstall();
    }

    /**
     * List class names of available handlers
     *
     * @return array
     */
    protected function getHandlers()
    {
        return [SourceHandler::class, TestsHandler::class, AssetsHandler::class];
    }

    /**
     *  Handle all module files and install to the proper folders
     */
    protected function moduleInstall()
    {
        $this->setRootPath(realpath($_SERVER['DOCUMENT_ROOT']));

        $plugin = $this;

        array_map(function($handlerClass) use ($plugin) {
            $handler = (new $handlerClass($plugin));
            $handler->install();
            $handler->cleanup();

        }, $this->getHandlers());
    }

    /**
     *  Handle all installed files and remove them
     */
    protected function moduleUninstall()
    {
        $this->setRootPath(realpath($_SERVER['DOCUMENT_ROOT']));

        $plugin = $this;

        array_map(function($handlerClass) use ($plugin) {
            $handler = (new $handlerClass($plugin));
            $handler->uninstall();

        }, $this->getHandlers());
    }

    /**
     * Set path root directory
     *
     * @param $path
     */
    protected function setRootPath($path)
    {
        $this->rootPath = $path;
    }

    /**
     * Return path root directory
     *
     * @return mixed
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * @return mixed
     */
    public function getModuleName()
    {
        return $this->installer->getSettings('module_name');
    }

    /**
     * @return mixed
     */
    public function getModulesPath()
    {
        return $this->installer->getSettings('modules_path');
    }
}
