<?php
/**
 * Resource Map
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource;

use stdClass;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use CommonApi\Resource\MapInterface;

/**
 * Resource Map
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class ResourceMap implements MapInterface
{
    /**
     * Resource Map Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $resource_map_filename;

    /**
     * Interface Map Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $interface_map_filename;

    /**
     * Base Path - root of the website from which paths are defined
     *
     * @var    string
     * @since  1.0
     */
    protected $base_path;

    /**
     * Namespace Prefixes + Path
     *
     * @var    array
     * @since  1.0
     */
    protected $namespace_prefixes = array();

    /**
     * Temporary Work File to accumulate Resource Map
     *
     * @var    array
     * @since  1.0
     */
    protected $resource_map = array();

    /**
     * Temporary Work File to accumulate PHP Class Files
     *
     * @var    array
     * @since  1.0
     */
    protected $php_files = array();

    /**
     * Constructor
     *
     * @param  string $base_path
     * @param  string $resource_map_filename
     * @param  string $interface_map_filename
     * @param  string $exclude_folders
     *
     * @since  1.0
     */
    public function __construct(
        $base_path,
        $resource_map_filename,
        $interface_map_filename,
        $exclude_folders
    ) {
        $this->base_path              = $base_path;
        $this->resource_map_filename  = $resource_map_filename;
        $this->interface_map_filename = $interface_map_filename;

        $this->readFile($exclude_folders, 'exclude_folders');
    }

    /**
     * Set a namespace prefix by mapping to the filesystem path
     *
     * @param   string  $namespace_prefix
     * @param   string  $namespace_base_directory
     * @param   boolean $prepend
     *
     * @return  $this
     * @since   1.0
     */
    public function setNamespace($namespace_prefix, $namespace_base_directory, $prepend = false)
    {
        if (isset($this->namespace_prefixes[$namespace_prefix])) {

            $hold = $this->namespace_prefixes[$namespace_prefix];

            if ($prepend === false) {
                $hold[]                                      = $namespace_base_directory;
                $this->namespace_prefixes[$namespace_prefix] = $hold;
            } else {
                $new   = array();
                $new[] = $namespace_base_directory;
                foreach ($hold as $h) {
                    $new[] = $h;
                }
                $this->namespace_prefixes[$namespace_prefix] = $new;
            }
        } else {
            $this->namespace_prefixes[$namespace_prefix] = array($namespace_base_directory);
        }

        return $this;
    }

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  object
     * @since   1.0
     */
    public function createMap()
    {
        $this->resource_map = array();
        $this->php_files    = array();

        foreach ($this->namespace_prefixes as $namespace_prefix => $namespace_base_directories) {

            foreach ($namespace_base_directories as $namespace_base_directory) {

                if (trim($namespace_base_directory) == '') {
                } else {
                    if (is_dir($this->base_path . '/' . $namespace_base_directory)
                        && $namespace_base_directory !== ''
                    ) {

                        $paths   = array();
                        $paths[] = $this->base_path . '/' . $namespace_base_directory;
                        $this->resource_map[strtolower($namespace_prefix)]
                                 = array_unique($paths);

                        $objects = new RecursiveIteratorIterator
                        (new RecursiveDirectoryIterator($this->base_path . '/' . $namespace_base_directory),
                            RecursiveIteratorIterator::SELF_FIRST);
                    } else {

                        if ($namespace_base_directory == '') {
                        } else {
                            echo 'createResourceMap: Not a folder '
                                . $this->base_path . '/' . $namespace_base_directory . '<br />';
                            die;
                        }

                        break;
                    }

                    foreach ($objects as $file_path => $file_object) {

                        $file_name      = $file_object->getFileName();
                        $file_extension = $file_object->getExtension();
                        $is_directory   = $file_object->isDir();
                        $php_class      = 0;

                        /** Test Namespace Rules */
                        $this->testFileForNamespaceRules(
                            $namespace_prefix,
                            $namespace_base_directory,
                            $is_directory,
                            $file_path,
                            $file_name,
                            $file_extension,
                            $php_class
                        );
                    }
                }
            }
        }

        ksort($this->resource_map);
        ksort($this->php_files);

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            file_put_contents($this->resource_map_filename, json_encode($this->resource_map, JSON_PRETTY_PRINT));
            file_put_contents($this->interface_map_filename, json_encode($this->php_files, JSON_PRETTY_PRINT));
        } else {
            file_put_contents($this->resource_map_filename, json_encode($this->resource_map));
            file_put_contents($this->interface_map_filename, json_encode($this->php_files));
        }

        return $this->resource_map;
    }

    /**
     * Test Path for this Namespace Prefix
     *
     * @param   string $namespace_prefix
     * @param   string $base_directory
     * @param   int    $is_directory
     * @param   string $file_path
     * @param   string $file_name
     * @param   string $file_extension
     * @param   string $php_class
     *
     * @return  int|object
     * @since   1.0
     */
    protected function testFileForNamespaceRules(
        $namespace_prefix,
        $base_directory,
        $is_directory,
        $file_path,
        $file_name,
        $file_extension,
        $php_class
    ) {
        $skip = 0;

        if ($is_directory == 1) {
            $pathinfo  = pathinfo($file_path);
            $base_name = $pathinfo['basename'];

        } else {

            if ($file_extension == 'php') {

                $base_name = substr($file_name, 0, strlen($file_name) - strlen($file_extension) - 1);

                if (strtolower(substr($file_name, 0, 4)) == 'hold') {
                    $skip = 1;
                } elseif (strtolower(substr($file_name, 0, 3)) == 'xxx') {
                    $skip = 1;
                } elseif (strtolower($base_name) == 'index') {
                    $skip = 1;
                } else {
                    $php_class = 1;
                }
            } else {
                $base_name = $file_name;
            }
        }

        if ($skip == 1) {
            return $this;
        }

        /** Namespace Rules */
        $file_path = substr($file_path, strlen($this->base_path . '/'), 9999);

        $skip = $this->processExcludeFolders($file_path, $base_name, $skip);
        if ($skip == 1) {
            return $this;
        }

        if ($is_directory === true) {
            $path = $file_path;
        } else {
            $path = substr($file_path, 0, strlen($file_path) - strlen($file_name) - 1);
        }

        $class_namespace_path = substr($path, strlen($base_directory), 9999);

        if ($class_namespace_path == '') {
            $fqns = $namespace_prefix;
        } else {
            $fqns = $namespace_prefix . '\\' . str_replace('/', '\\', $class_namespace_path);
        }

        $nspath = $path;

        if ($is_directory === true) {
        } else {
            $fqns   = $fqns . '\\' . $base_name;
            $nspath = $nspath . '/' . $file_name;
            if ($php_class === 1) {
                $temp = new stdClass();

                $temp->file_name = $file_name;
                $temp->base_name = $base_name;
                $temp->path      = $nspath;
                $temp->fqns      = $fqns;

                $this->php_files[$nspath] = $temp;
            }
        }

        $this->mergeFQNSPaths($nspath, $fqns);

        return $this;
    }

    /**
     * Get Resource Map Tags
     *
     * @param   string $nspath
     * @param   string $fqns
     *
     * @return  $this
     * @since   1.0
     */
    protected function mergeFQNSPaths($nspath, $fqns)
    {
        if ($nspath === '') {
            return $this;
        }

        $fqns = strtolower($fqns);

        if (isset($this->resource_map[$fqns])) {

            $existing = $this->resource_map[$fqns];

            if (is_array($existing)) {
                $paths = $existing;
                if (count($paths) == 0) {
                    $paths = array();
                }
            } else {
                $paths = array();
            }
        } else {
            $paths = array();
        }

        $paths[] = $this->base_path . '/' . $nspath;

        $this->resource_map[$fqns] = array_unique($paths);

        return $this;
    }

    /**
     * Process Exclude Folders Definitions
     *
     * @param   string $file_path
     * @param   string $base_name
     * @param   int    $skip
     *
     * @return  int
     * @since   1.0
     */
    protected function processExcludeFolders($file_path, $base_name, $skip = 1)
    {
        if ($skip === 1) {
            return $skip;
        }

        if (substr($base_name, 0, 1) == '.') {
            return 1;
        }

        if (count($this->exclude_folders) === 0) {
            return $skip;
        }

        $skip = 0;

        foreach ($this->exclude_folders as $exclude) {

            if ($base_name == $exclude) {
                $skip = 1;
                break;
            } elseif (strpos($file_path, '/' . $exclude) == false) {
            } else {
                $skip = 1;
                break;
            }
        }

        return $skip;
    }

    /**
     * Read File
     *
     * @param  string $file_name
     * @param  string $property_name_array
     *
     * @since  1.0
     */
    protected function readFile($file_name, $property_name_array)
    {
        $temp_array = array();

        if (file_exists($file_name)) {
        } else {
            $file_name = __DIR__ . '/' . $file_name;
        }

        if (file_exists($file_name)) {
        } else {
            return;
        }

        $input = file_get_contents($file_name);
        $temp  = json_decode($input);

        if (count($temp) > 0) {
            $temp_array = array();
            foreach ($temp as $key => $value) {
                $temp_array[$key] = $value;
            }
        }

        $this->$property_name_array = $temp_array;
    }
}