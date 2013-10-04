<?php
/**
 * Request Service Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 *
 */
namespace Molajo\Tests;

use Molajo\Http\Request\Request;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-02-17 at 11:54:00.
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AbstractHttp
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $_SERVER['REQUEST_METHOD']  = 'GET';
        $_SERVER['REQUEST_URI']     = 'http://molajo:crocodile/molajo.org:80/base/path/index.php?name=value&amy=first#fragment';
        $_SERVER['HTTPS']           = null;
        $_SERVER['SERVER_PORT']     = 80;
        $_SERVER['PHP_AUTH_USER']   = 'molajo';
        $_SERVER['PHP_AUTH_PW']     = 'crocodile';
        $_SERVER['HTTP_HOST']       = 'molajo.org';
        $_SERVER['QUERY_STRING']    = 'name=value&amy=first';
        $_SERVER['SCRIPT_FILENAME'] = '';
        $_SERVER['CONTENT_TYPE']    = 'text/html';

        $this->object = new Request();
    }

    /**
     * Get the current value (or default) of the specified key
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGet($key = null, $default = null, $filter = 'Alphanumeric', $filter_options = array())
    {
        $key            = 'fragment';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            'fragment',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Set the value of a specified key
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testSet()
    {
        $key            = 'fragment';
        $value          = 'dog';
        $default        = '';
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->object->set($key, $value);

        $this->assertEquals(
            'dog',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Get Request Method - 'GET', 'POST', 'PUT', 'DELETE', 'HEAD', 'OPTIONS', 'PATCH'
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGetMethod()
    {
        $key            = 'method';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            'GET',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Get Uri
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGetUri()
    {
        $key            = 'uri';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            'http://molajo:crocodile/molajo.org:80/base/path/index.php?name=value&amy=first#fragment',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Returns the Scheme - HTTP or HTTPS
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGetScheme()
    {
        $key            = 'scheme';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            'http://',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Get the User
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGetUser()
    {
        $key            = 'user';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            'molajo',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Get the Password
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGetPassword()
    {
        $key            = 'password';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            'crocodile',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Host
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGetHost()
    {
        $key            = 'host';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            'molajo.org',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Port
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGetPort()
    {
        $key            = 'port';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            '80',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Authority
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGetAuthority()
    {
        $key            = 'authority';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            'molajo:crocodile/molajo.org',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Returns Path
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGetPath()
    {
        $key            = 'path';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            'base/path',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Builds normalized query string with alphabetized key/value pairs
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGetQueryString()
    {
        $key            = 'query_string';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            'name=value&amy=first',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Content Type
     *
     * @covers Molajo\Http\Request\Adapter::getMethod
     */
    public function testGetContentType()
    {
        $key            = 'mimetype';
        $default        = null;
        $filter         = 'Alphanumeric';
        $filter_options = array();

        $this->assertEquals(
            'php',
            $this->object->get($key, $default, $filter, $filter_options)
        );
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }
}
