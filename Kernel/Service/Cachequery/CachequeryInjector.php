<?php
/**
 * Cache Query Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Cachequery;

use Molajo\IoC\Handler\AbstractInjector;
use Molajo\IoC\Api\ServiceHandlerInterface;
use Molajo\IoC\Exception\ServiceHandlerException;

/**
 * Cache Query Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class CachequeryInjector extends AbstractInjector implements ServiceHandlerInterface
{
    /**
     * Constructor
     *
     * @param  array $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->service_namespace        = 'Molajo\\Cache\\Adapter';
        $this->store_instance_indicator = true;

    }

    /**
     * Instantiate Class
     *
     * @return  object
     * @since   1.0
     * @throws  ServiceHandlerException
     */
    public function instantiateService()
    {
        $this->service_instance = $this->options['cache_query'];

        return $this;
    }
}
