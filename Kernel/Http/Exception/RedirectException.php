<?php
/**
 * Redirect  Exception
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Http\Exception;

use RuntimeException;

use Molajo\Http\Api\ExceptionInterface;

/**
 * Redirect Exception
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class RedirectException extends RuntimeException implements ExceptionInterface
{
}
