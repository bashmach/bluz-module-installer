<?php
/**
 * @author Pavel Machekhin <pavel.machekhin@gmail.com>
 * @created 2015-03-24 11:15
 */

namespace Bashmach\Composer\Installers;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;


class BluzModuleInstaller extends LibraryInstaller
{

    /**
     * @var string
     */
    protected $settings;

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $extra     = $package->getExtra();
        $rootExtra = $this->composer->getPackage()->getExtra();
        $this->settings  = array_merge($rootExtra['bluz'], $extra['bluz']);
        if (empty($this->settings['modules_path'])) {
            throw new \Exception('modules_path is not defined');
        }
        if (empty($this->settings['module_name'])) {
            throw new \Exception('module_name is not defined');
        }
        $path = $this->settings['modules_path'] . '/' . $this->settings['module_name'];

        return $path;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return $packageType === 'bluz-module';
    }

    protected function uninstallModule()
    {

    }

    /**
     * Return settings
     *
     * @param null $key
     * @return mixed
     */
    public function getSettings($key = null)
    {
        if (isset($this->settings[$key]))
            return $this->settings[$key];
        return $this->settings;
    }


}