<?php
/**
 * Created by PhpStorm.
 * User: bshmch
 * Date: 12.06.15
 * Time: 22:47
 */

namespace Bluzphp\Composer\Installers\Handlers;


class TestsHandler extends CommonHandler
{
    /**
     * @var string
     */
    protected $path = 'tests';

    protected function setup()
    {
        $this->setPattern(
            'modules',
            $this->getPlugin()->getRootPath() . DIRECTORY_SEPARATOR
                . 'tests' . DIRECTORY_SEPARATOR
                . '%s' . DIRECTORY_SEPARATOR
                . $this->getPlugin()->getModuleName()
        );

        $this->setPattern(
            'models',
            $target = $this->getPlugin()->getRootPath() . DIRECTORY_SEPARATOR
                . 'tests' . DIRECTORY_SEPARATOR
                . '%s' . DIRECTORY_SEPARATOR
                . ucfirst($this->getPlugin()->getModuleName())
        );
    }
}