<?php
/**
 * Created by PhpStorm.
 * User: bshmch
 * Date: 12.06.15
 * Time: 22:47
 */

namespace Bluzphp\Composer\Installers\Handlers;


class AssetsHandler extends CommonHandler
{
    /**
     * @var string
     */
    protected $path = 'assets';

    protected function setup()
    {
        $this->setPatterns(
            ['css', 'js'],
            $this->getPublicPath() . DIRECTORY_SEPARATOR
                . '%s' . DIRECTORY_SEPARATOR
                . $this->getPlugin()->getModuleName()
        );
    }
}