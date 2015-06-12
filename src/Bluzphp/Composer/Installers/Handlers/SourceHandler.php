<?php
/**
 * Created by PhpStorm.
 * User: bshmch
 * Date: 12.06.15
 * Time: 22:47
 */

namespace Bluzphp\Composer\Installers\Handlers;


class SourceHandler extends CommonHandler
{
    /**
     * @var string
     */
    protected $path = 'src';

    protected function setup()
    {
        $this->setPatterns(
            ['controllers', 'views'],
            $this->getPlugin()->getRootPath() . DIRECTORY_SEPARATOR
                . $this->getPlugin()->getModulesPath() . DIRECTORY_SEPARATOR
                . $this->getPlugin()->getModuleName() . DIRECTORY_SEPARATOR
                . '%s'
        );

        $this->setPattern(
            'models',
            $this->getPlugin()->getRootPath() . DIRECTORY_SEPARATOR
                . $this->getPlugin()->getModulesPath() . DIRECTORY_SEPARATOR
                . $this->getPlugin()->getModuleName() . DIRECTORY_SEPARATOR
                . '..' . DIRECTORY_SEPARATOR
                . '..' . DIRECTORY_SEPARATOR
                . '%s' . DIRECTORY_SEPARATOR
                . ucfirst($this->getPlugin()->getModuleName())
        );
    }
}