<?php
/**
 * Css Declarations Resources
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Resource\Handler;

use stdClass;
use CommonApi\Resource\HandlerInterface;

/**
 * Css Declarations Resources
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class CssdeclarationsHandler extends AbstractHandler implements HandlerInterface
{
    /**
     * Css Declarations
     *
     * @var    array
     * @since  1.0
     */
    protected $css = array();

    /**
     * CSS Declarations Priorities
     *
     * @var    array
     * @since  1.0
     */
    protected $css_priorities = array();

    /**
     * Language Direction
     *
     * @var    string
     * @since  1.0
     */
    protected $language_direction;

    /**
     * HTML5
     *
     * @var    string
     * @since  1.0
     */
    protected $html5;

    /**
     * Line End
     *
     * @var    string
     * @since  1.0
     */
    protected $line_end;

    /**
     * Mimetype
     *
     * @var    string
     * @since  1.0
     */
    protected $mimetype;

    /**
     * Constructor
     *
     * @param  string $base_path
     * @param  array  $resource_map
     * @param  array  $namespace_prefixes
     * @param  array  $valid_file_extensions
     * @param  string $language_direction
     * @param  string $html5
     * @param  string $line_end
     * @param  string $mimetype
     *
     * @since  1.0
     */
    public function __construct(
        $base_path = null,
        array $resource_map = array(),
        array $namespace_prefixes = array(),
        array $valid_file_extensions = array(),
        $language_direction,
        $html5,
        $line_end,
        $mimetype
    ) {
        parent::__construct(
            $base_path,
            $resource_map,
            $namespace_prefixes,
            $valid_file_extensions
        );

        $this->language_direction = $language_direction;
        $this->html5              = $html5;
        $this->line_end           = $line_end;
        $this->mimetype           = $mimetype;
    }

    /**
     * Locates folder/file associated with Namespace for Resource
     *
     * @param   string $resource_namespace
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Exception\Resources\ResourcesException
     */
    public function get($resource_namespace, $multiple = false)
    {
        return parent::get($resource_namespace);
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
        return parent::setNamespace($namespace_prefix, $namespace_base_directory, $prepend);
    }

    /**
     * Handle located folder/file associated with URI Namespace for Resource
     *
     * @param   string $scheme
     * @param   string $located_path
     * @param   array  $options
     *
     * @return  void|mixed
     * @since   1.0
     * @throws  \Exception\Resources\ResourcesException
     */
    public function handlePath($scheme, $located_path, array $options = array())
    {
        $css = '';
        if (isset($options['css'])) {
            $css = $options['css'];
        }
        $priority = 500;
        if (isset($options['priority'])) {
            $priority = $options['priority'];
        }
        $mimetype = 'text/css';
        if (isset($options['mimetype'])) {
            $mimetype = $options['mimetype'];
        }

        $temp_row = new stdClass();

        $temp_row->mimetype = $mimetype;
        $temp_row->content  = $css;
        $temp_row->priority = $priority;

        $this->css[] = $temp_row;

        $this->css_priorities[] = $priority;
        sort($priorities);

        return;
    }

    /**
     * Retrieve a collection of a specific handler
     *
     * @param   string $scheme
     * @param   array  $options
     *
     * @return  mixed
     * @since   1.0
     * @throws  \Exception\Resources\ResourcesException
     */
    public function getCollection($scheme, array $options = array())
    {
        $temp = $this->css;

        if (is_array($temp) && count($temp) > 0) {
        } else {
            return array();
        }

        $priorities = $this->css_priorities;
        sort($priorities);

        $query_results = array();

        foreach ($priorities as $priority) {

            foreach ($temp as $temp_row) {

                $include = false;

                if (isset($temp_row->priority)) {
                    if ($temp_row->priority == $priority) {
                        $include = true;
                    }
                }

                if ($include === false) {
                } else {
                    $temp_row->application_html5 = $this->html5;
                    $temp_row->end               = $this->line_end;
                    $temp_row->page_mimetype     = $this->mimetype;
                    $query_results[]             = $temp_row;
                }
            }
        }

        return $query_results;
    }
}
