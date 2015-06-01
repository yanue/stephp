<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 6/1/15
 * Time: 11:54 AM
 */

namespace Library\Di;

/**
 * Pimple service provider interface.
 *
 * @author  Fabien Potencier
 * @author  Dominik Zogg
 */
interface ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $di A container instance
     */
    public function register(Container $di);
}