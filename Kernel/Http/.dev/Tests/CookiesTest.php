<?php
namespace Molajo\Tests;

use Molajo\Http\Cookie\Cookie;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-02-17 at 11:54:00.
 */
class UserCookiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var $cookiesClass
     */
    protected $cookiesClass;

    /**
     * @covers Molajo\Http\Cookie\Cookie::exists
     */
    public function setUp()
    {
        $class              = 'Molajo\\Http\\Cookie\\Cookie';
        $this->cookiesclass = new $class;

        return;
    }

    /**
     * @covers Molajo\Http\Cookie\Cookie::exists
     */
    public function testExists()
    {
        $parameters = array();

        $expire               = 60 * 60 * 60;
        $parameters['expire'] = time() + $expire;

        $path               = '';
        $parameters['path'] = $path;

        $domain               = '';
        $parameters['domain'] = $domain;

        $secure               = 0;
        $parameters['secure'] = $secure;

        $httponly               = 0;
        $parameters['httponly'] = $httponly;

        $key   = 'MolajoCookie';
        $value = 'dogfood';

        $_COOKIE[$key] = $value;

        $this->assertTrue($this->cookiesClass->exists($key));
    }

    /**
     * @covers Molajo\Http\Cookie\Cookie::exists
     */
    public function testExistsFalse()
    {
        $parameters = array();

        $expire               = 60 * 60 * 60;
        $parameters['expire'] = time() + $expire;

        $path               = '';
        $parameters['path'] = $path;

        $domain               = '';
        $parameters['domain'] = $domain;

        $secure               = 0;
        $parameters['secure'] = $secure;

        $httponly               = 0;
        $parameters['httponly'] = $httponly;

        $key = 'MolajoCookie';

        $this->assertFalse($this->cookiesClass->exists($key));
    }

    /**
     * @covers Molajo\Http\Cookie\Cookie::set
     */
    public function testSet()
    {
        $parameters = array();

        $expire               = 60 * 60 * 60;
        $parameters['expire'] = time() + $expire;

        $path               = '';
        $parameters['path'] = $path;

        $domain               = '';
        $parameters['domain'] = $domain;

        $secure               = 0;
        $parameters['secure'] = $secure;

        $httponly               = 0;
        $parameters['httponly'] = $httponly;

        $key   = 'MolajoCookie';
        $value = 'Toothpick';

        $set = $this->cookiesClass->set($key, $value, $parameters);

        $value2 = htmlspecialchars_decode($_COOKIE[$key]);
        $new    = @unserialize($value2);
        $this->assertEquals($value, $new);
    }

    /**
     * @covers Molajo\Http\Cookie\Cookie::get
     */
    public function testGet()
    {
        $parameters = array();

        $expire               = 60 * 60 * 60;
        $parameters['expire'] = time() + $expire;

        $path               = '';
        $parameters['path'] = $path;

        $domain               = '';
        $parameters['domain'] = $domain;

        $secure               = 0;
        $parameters['secure'] = $secure;

        $httponly               = 0;
        $parameters['httponly'] = $httponly;

        $key   = 'MolajoCookie';
        $value = 'Toothpick';

        $set = $this->cookiesClass->set($key, $value, $parameters);

        $get = $this->cookiesClass->get($key);

        $value2 = htmlspecialchars_decode($_COOKIE[$key]);
        $new    = @unserialize($value2);
        $this->assertEquals($value, $get);
    }

    /**
     * @covers Molajo\Http\Cookie\Cookie::get
     * @expectedException  Molajo\Http\Exception\CookieException
     */
    public function testGetFail()
    {
        $parameters = array();

        $expire               = 60 * 60 * 60;
        $parameters['expire'] = time() + $expire;

        $path               = '';
        $parameters['path'] = $path;

        $domain               = '';
        $parameters['domain'] = $domain;

        $secure               = 0;
        $parameters['secure'] = $secure;

        $httponly               = 0;
        $parameters['httponly'] = $httponly;

        $key = 'MolajoCookieDoesNotExist';

        $get = $this->cookiesClass->get($key);
    }

    /**
     * @covers Molajo\Http\Cookie\Cookie::delete
     * @expectedException  Molajo\Http\Exception\CookieException
     */
    public function testDelete()
    {
        $parameters = array();

        $expire               = 60 * 60 * 60;
        $parameters['expire'] = time() + $expire;

        $path               = '';
        $parameters['path'] = $path;

        $domain               = '';
        $parameters['domain'] = $domain;

        $secure               = 0;
        $parameters['secure'] = $secure;

        $httponly               = 0;
        $parameters['httponly'] = $httponly;

        $key   = 'MolajoCookie';
        $value = 'Toothpick';

        $this->cookiesClass->set('MolajoCookie1', $value, $parameters);
        $this->cookiesClass->set('MolajoCookie2', $value, $parameters);
        $this->cookiesClass->set('MolajoCookie3', $value, $parameters);

        $this->cookiesClass->delete('MolajoCookie3');
        $this->cookiesClass->get('MolajoCookie3');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

}
