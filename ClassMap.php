<?php
/**
 * Class Map
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource;

use stdClass;
use Exception;
use ReflectionClass;
use ReflectionParameter;
use CommonApi\Resource\ClassMapInterface;

/**
 * Class Map
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class ClassMap implements ClassMapInterface
{
    /**
     * Interface Map Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $class_map_filename;

    /**
     * Interfaces Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $interface_classes_filename;

    /**
     * Class Dependencies
     *
     * @var    string
     * @since  1.0
     */
    protected $concrete_classes_filename;

    /**
     * Events
     *
     * @var    string
     * @since  1.0
     */
    protected $events_filename;

    /**
     * Constructor
     *
     * @param  string $class_map_filename
     * @param  string $interface_classes_filename
     * @param  string $concrete_classes_filename
     * @param  string $events_filename
     *
     * @since  1.0
     */
    public function __construct(
        $class_map_filename,
        $interface_classes_filename,
        $concrete_classes_filename,
        $events_filename
    ) {
        $this->class_map_filename         = $class_map_filename;
        $this->interface_classes_filename = $interface_classes_filename;
        $this->concrete_classes_filename  = $concrete_classes_filename;
        $this->events_filename            = $events_filename;
    }

    /**
     * Create interface to concrete references
     *
     * @return  $this
     * @since   1.0
     */
    public function createMap()
    {
        $php_files = $this->readFile($this->class_map_filename);

        if (count($php_files) > 0) {
        } else {
            return array();
        }

        $interfaces      = array();
        $interface_usage = array();
        $concretes       = array();

        foreach ($php_files as $file) {

            $result = $this->setInterfaceClass($file->fqns, $file->path);

            /** Concrete */
            if ($result === false) {
                $temp                   = $this->setConcreteClass($file->fqns, $file->path, $interface_usage);
                $concretes[$file->fqns] = $temp[0];
                $interface_usage        = $temp[1];
            } else {
                /** Interface */
                $interfaces[$file->fqns] = $result;
            }
        }

        $interfaces = $this->setAggregateInterfaceUsage($interfaces, $interface_usage);

        foreach ($concretes as $dependency) {

            if (isset($dependency->constructor_parameters)
                && count($dependency->constructor_parameters) > 0
            ) {

                foreach ($dependency->constructor_parameters as $parameter) {

                    if ($parameter->instance_of === null) {
                    } else {

                        $instance_of = $parameter->instance_of;

                        if (isset($interfaces[$instance_of]->implemented_by)) {
                            $parameter->implemented_by = $interfaces[$instance_of]->implemented_by;
                            $parameter->concrete       = false;
                        } else {
                            $parameter->implemented_by = null;
                            $parameter->concrete       = true;
                        }
                    }
                }
            }
        }

        $results   = $this->setAggregateConcreteInterfaceUsage($concretes, $concretes);
        $concretes = $results[0];
        $events    = $results[1];

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            file_put_contents($this->interface_classes_filename, json_encode($interfaces, JSON_PRETTY_PRINT));
            file_put_contents(
                $this->concrete_classes_filename,
                json_encode($concretes, JSON_PRETTY_PRINT)
            );
            file_put_contents($this->events_filename, json_encode($events, JSON_PRETTY_PRINT));
        } else {
            file_put_contents($this->interface_classes_filename, json_encode($interfaces));
            file_put_contents($this->concrete_classes_filename, json_encode($concretes));
            file_put_contents($this->events_filename, json_encode($events));
        }

        return $this;
    }

    /**
     * Process Interface for Metadata
     *
     * @param  string $fqns
     * @param  string $path
     *
     * @since  1.0
     * @return array
     */
    protected function setInterfaceClass($fqns, $path)
    {
        try {
            $reflection = new ReflectionClass($fqns);
        } catch (Exception $e) {
            return false;
        }

        if ($reflection->isInterface()) {
        } else {
            return false;
        }

        $interface_object            = new stdClass();
        $interface_object->name      = $reflection->getShortName();
        $interface_object->namespace = $reflection->getNamespaceName();
        $interface_object->fqns      = $reflection->getName();
        $interface_object->path      = $path;

        $parent = $reflection->getParentClass();

        if ($parent === false) {
            $interface_object->parent = false;
        } else {
            $interface_object->parent = $parent->name;
        }

        return $interface_object;
    }

    /**
     * For each Interface, determine Concrete Classes which Implement the Interface and
     *  Requirements for a Concrete Class expressed by the Interface as a Type Hint in the Method Parameters
     *
     * @param  array $interfaces
     * @param  array $interface_usage
     *
     * @since  1.0
     * @return array
     */
    protected function setAggregateInterfaceUsage(array $interfaces, array $interface_usage)
    {
        if (count($interfaces) === 0) {
            return $interfaces;
        }

        ksort($interface_usage);
        ksort($interfaces);

        foreach ($interfaces as $interface) {

            if (isset($interface_usage[$interface->fqns])) {

                if (isset($interface_usage[$interface->fqns]->implemented_by)) {
                    $interface->implemented_by = $interface_usage[$interface->fqns]->implemented_by;
                } else {
                    $interface->implemented_by = array();
                }

                if (isset($interface_usage[$interface->fqns]->dependency_for)) {
                    $interface->dependency_for = $interface_usage[$interface->fqns]->dependency_for;
                } else {
                    $interface->dependency_for = array();
                }
            } else {

                $interface->implemented_by = array();
                $interface->dependency_for = array();
            }
        }

        return $interfaces;
    }

    /**
     * Process Concrete Class for Dependencies and Metadata
     *
     * @param  string $fqns
     * @param  string $path
     * @param  array  $interface_usage
     *
     * @return  $this
     * @since   1.0
     */
    protected function setConcreteClass($fqns, $path, array $interface_usage = array())
    {
        $class_object = new stdClass();

        try {
            $reflection = new ReflectionClass($fqns);
        } catch (Exception $e) {
            return array($class_object, $interface_usage);
        }

        if ($reflection->isInterface()) {
            return array($class_object, $interface_usage);
        }

        $class_object->name      = $reflection->getShortName();
        $class_object->namespace = $reflection->getNamespaceName();
        $class_object->fqns      = $reflection->getName();
        $class_object->file_name = $reflection->getFileName();
        $class_object->path      = $path;

        $parent = $reflection->getParentClass();

        if ($parent === false) {
            $class_object->parent = false;
        } else {
            $class_object->parent = $parent->name;
        }

        $implemented                          = $reflection->getInterfaceNames();
        $class_object->implemented_interfaces = $implemented;

        if (count($implemented) > 0) {
            foreach ($implemented as $interface) {
                $interface_usage = $this->setConcreteInterfaceUsageImplementations(
                    $interface,
                    $interface_usage,
                    $class_object->fqns
                );
            }
        }

        $class_object->constructor_parameters = array();

        if (method_exists($fqns, '__construct')) {
            $construct                            = $reflection->getMethod('__construct');
            $class_object->constructor_docComment = $construct->getDocComment();
            $parameters                           = $construct->getParameters();

            if (count($parameters) > 0) {

                $temp = array();
                foreach ($parameters as $parameter) {
                    $temp[] = $this->processDependencies(array($fqns, '__construct'), $parameter);
                }

                $class_object->constructor_parameters = $temp;

                $interface_usage
                    = $this->setConcreteInterfaceUsageDependencyTypeHints
                    (
                        $class_object->fqns,
                        $class_object->constructor_parameters,
                        $interface_usage
                    );
            }
        }

        return array($class_object, $interface_usage);
    }

    /**
     * Process Dependencies for the Class
     *
     * @param   array  $class_method_array
     * @param   object $parameter
     *
     * @return  $this
     * @since   1.0
     */
    protected function processDependencies($class_method_array, $parameter)
    {
        $parameters_object = new stdClass();
        $param             = new ReflectionParameter($class_method_array, $parameter->name);

        $parameters_object->name = $param->getName();

        if ($param->isDefaultValueAvailable() === true) {
            $parameters_object->default_available = true;
            $parameters_object->default_value     = $param->getDefaultValue();
        } else {
            $parameters_object->default_available = false;
            $parameters_object->default_value     = null;
        }

        $instance_dependency = $param->getClass();

        if ($instance_dependency === null) {
            $parameters_object->instance_of     = null;
            $parameters_object->is_instantiable = false;
        } else {
            $parameters_object->instance_of     = $instance_dependency->name;
            $parameters_object->is_instantiable = $instance_dependency->isInstantiable();
        }

        return $parameters_object;
    }

    /**
     * Process Implemented Interfaces for the Class
     *
     * @param  string $interface
     * @param  array  $interface_usage
     * @param  string $concrete_fqns
     *
     * @return mixed
     * @since  1.0
     */
    protected function setConcreteInterfaceUsageImplementations($interface, $interface_usage, $concrete_fqns)
    {
        $paths = array();

        if (isset($interface_usage[$interface])) {

            $temp = $interface_usage[$interface];

            if (isset($temp->implemented_by)) {
                $paths = $temp->implemented_by;
            }

            if (is_array($paths)) {
            } else {
                $paths = array();
            }
        } else {
            $temp  = new stdClass();
            $paths = array();
        }

        $paths[] = $concrete_fqns;

        array_unique($paths);
        sort($paths);

        $temp->implemented_by = $paths;

        $interface_usage[$interface] = $temp;

        return $interface_usage;
    }

    /**
     * Add to Dependency Interfaces List
     *
     * @param  string $fqns
     * @param  array  $concretes
     * @param  array  $interface_usage
     *
     * @return mixed
     * @since  1.0
     */
    protected function setConcreteInterfaceUsageDependencyTypeHints($fqns, array $concretes, array $interface_usage)
    {
        if (count($concretes) > 0) {

            foreach ($concretes as $interface) {

                if ($interface->instance_of === null) {
                } else {
                    if (isset($interface_usage[$interface->instance_of])) {
                    } else {
                        $interface_usage[$interface->instance_of]                 = new stdClass();
                        $interface_usage[$interface->instance_of]->dependency_for = array();
                    }

                    if (isset($interface_usage[$interface->instance_of]->dependency_for)) {
                        $dependency_for = $interface_usage[$interface->instance_of]->dependency_for;
                    } else {
                        $interface_usage[$interface->instance_of]->dependency_for = array();
                        $dependency_for                                           = array();
                    }

                    if (is_array($dependency_for)) {
                    } else {
                        $dependency_for = array();
                    }

                    if ($interface_usage[$interface->instance_of]->dependency_for === null) {
                    } else {
                        $dependency_for[] = $fqns;
                    }

                    array_unique($dependency_for);
                    sort($dependency_for);

                    if (count($dependency_for) === 0) {
                        $dependency_for = null;
                    }
                    $interface_usage[$interface->instance_of]->dependency_for = $dependency_for;
                }
            }
        }

        return $interface_usage;
    }

    /**
     * For each Interface, determine Concrete Classes which Implement the Interface and
     *  Requirements for a Concrete Class expressed by the Interface as a Type Hint in the Method Parameters
     *
     * @param  array $concretes
     * @param  array $concretes_all
     *
     * @since  1.0
     * @return array
     */
    protected function setAggregateConcreteInterfaceUsage(array $concretes, array $concretes_all)
    {
        $events = array();

        foreach ($concretes as $concrete) {

            if (empty($concrete->fqns)) {
            } else {

                if (isset($concretes_all[$concrete->fqns])) {

                    if (isset($concretes_all[$concrete->fqns]->implemented_by)) {
                        $concrete->implemented_by = $concretes_all[$concrete->fqns]->implemented_by;
                    } else {
                        $concrete->implemented_by = array();
                    }

                    if (isset($concretes[$concrete->name]->dependency_for)) {
                        $concrete->dependency_for = $concretes_all[$concrete->fqns]->dependency_for;
                    } else {
                        $concrete->dependency_for = array();
                    }
                } else {

                    $concrete->implemented_by = array();
                    $concrete->dependency_for = array();
                }

                $concrete->method = get_class_methods($concrete->fqns);

                if (count($concrete->method) > 0) {

                    foreach ($concrete->method as $method) {

                        $class_instance = new \ReflectionClass($concrete->fqns);
                        $abstract       = $class_instance->isAbstract();

                        if (substr($method, 0, 2) == 'on' && $abstract === false) {

                            $reflectionMethod = new \ReflectionMethod(new $concrete->fqns, $method);
                            $results          = $reflectionMethod->getDeclaringClass();

                            if ($results->name == $concrete->fqns) {
                                if (isset($events[$method])) {
                                    $classes = $events[$method];
                                } else {
                                    $classes = array();
                                }
                                $classes[]       = $concrete->fqns;
                                $events[$method] = array_unique($classes);
                            }
                        }
                    }
                }
            }
        }

        return array($concretes, $events);
    }

    /**
     * Read File
     *
     * @param   string $filename
     *
     * @return  mixed|string
     * @since   1.0
     */
    protected function readFile($filename)
    {
        if (file_exists($filename)) {
        } else {
            return '';
        }

        $input = file_get_contents($filename);

        return json_decode($input);
    }
}