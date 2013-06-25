<?php
/**
 * Resource Locator Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Locator;

use Molajo\Locator\Exception\LocatorException;
use Molajo\Locator\Api\ResourceLocaterInterface;
use Molajo\Locator\Api\ClassLoaderInterface;
use Molajo\Locator\Api\ResourceMapInterface;

/**
 * Resource Locator Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements ResourceLocaterInterface, ClassLoaderInterface
{
    /**
     * Handler Instances
     *
     * @var    object  Molajo\Locator\Api\LocatorHandlerInterface
     * @since  1.0
     */
    protected $handler_instance = array();

    /**
     * Constructor
     *
     * @param   ResourceLocaterInterface $handler_instance
     * @param   ResourceMapInterface     $resource_map_instance
     * @param   array                    $scheme_type
     *
     * @since   1.0
     */
    public function __construct(array $scheme_type = array())
    {
        // load each handler instance

        if ($handler == 'Class') {
            $this->register();
        }
    }

    /**
     * Register Class as Autoloader
     *
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0
     */
    public function register($prepend = true)
    {
        spl_autoload_register(array($this, 'get'), true, $prepend);

        return $this;
    }

    /**
     * Cancel Class Registration as Autoloader
     *
     * @return  $this
     * @since   1.0
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'get'));

        return $this;
    }

    /**
     * Locates folder/file associated with URI Namespace for Resource
     *
     * @param   string $uri_namespace
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function get($uri_namespace)
    {
        // split by protocol, namespace, options
        // findResource
        // handle
    }

    /**
     * Retrieve a collection of a specific resource type (ex., all CSS files registered)
     *
     * @param   array $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function getCollection(array $options = array())
    {
        // split by protocol, namespace, options
        // findResource
        // handle

        return $this->handler_instance[$scheme]->getCollection($resource);
    }

    /**
     * Locates folder/file associated with Fully Qualified Namespace
     *
     * @param   string $resource
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    protected function getResource($resource, array $options = array())
    {
        return $this->handler_instance[$scheme]->getResource($resource, $options);
    }

    /**
     * Special file or folder handling for resource type
     *
     * @param   string $resource
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    protected function handleResource($resource, array $options = array())
    {
        return $this->handler_instance[$scheme]->handleResource($resource, $options);
    }

    /**
     * Define Schemes for Resource location
     *
     * @param   string $scheme
     * @param   string $handler
     * @param   array  $extensions
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function addScheme($scheme, array $handler = 'File', $extensions = array())
    {

    }

    /**
     * Map a namespace prefix to a filesystem path
     *
     * @param   string   $namespace_prefix
     * @param   string   $base_directory
     * @param   boolean  $prepend
     *
     * @return  $this
     * @since   1.0
     */
    public function addNamespace($namespace_prefix, $base_directory, $prepend = false)
    {
        $this->handler_instance[$scheme]->addNamespace($namespace_prefix, $base_directory, $prepend);

        return $this;
    }

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function createMap()
    {

    }

    /**
     * Verify the correctness of the resource map
     *
     * @return  array
     * @since   1.0
     * @throws  \Molajo\Locator\Exception\LocatorException
     */
    public function editMap()
    {

    }
}
