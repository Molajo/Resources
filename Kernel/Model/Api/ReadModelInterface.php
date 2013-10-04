<?php
/**
 * Read Model Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Model\Api;

use Molajo\Model\Exception\ReadModelException;

/**
 * Read Model Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface ReadModelInterface
{
    /**
     * Using the Model Registry, establish the base query
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Model\Exception\ReadModelException
     */
    public function setBaseQuery();

    /**
     * Add View Permission Verification to the Query
     *
     * Note: When Language query runs, Permissions Service is not yet available.
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Model\Exception\ReadModelException
     */
    public function checkPermissions();

    /**
     * Uses joins defined in model registry to build SQL statements
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Model\Exception\ReadModelException
     */
    public function useSpecialJoins();

    /**
     * Add Model Registry Criteria to Query
     *
     * @param   object $parameters
     *
     * @return  $this
     * @since   1.0
     * @throws  \Molajo\Model\Exception\ReadModelException
     */
    public function setModelCriteria($parameters);

    /**
     * getQueryResults - Execute query and returns an associative array of data elements
     *
     * @return  int     count of total rows for pagination
     * @since   1.0
     * @throws  \Molajo\Model\Exception\ReadModelException
     */
    public function getQueryResults();
}
