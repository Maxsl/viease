<?php namespace Laravel;

/**
 * Stub the global setcookie method into the Laravel namespace.
 */
function setcookie($name, $value, $time, $path, $domain, $secure)
{
	$_SERVER['cookie.stub'][$name] = compact('name', 'value', 'time', 'path', 'domain', 'secure');
}

function headers_sent()
{
	return $_SERVER['function.headers_sent'];
}

class CookieTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		Cookie::$jar = array();
	}

	/**
	 * Tear down the test environment.
	 */
	public function tearDown()
	{
		Cookie::$jar = array();
	}

	/**
	 * Test Cookie::has method.
	 *
	 * @group laravel
	 */
	public function testHasMethodIndicatesIfCookieInSet()
	{
		Cookie::$jar['foo'] = array('value' => 'bar');
		$this->assertTrue(Cookie::has('foo'));
		$this->assertFalse(Cookie::has('bar'));

		Cookie::put('baz', 'foo');
		$this->assertTrue(Cookie::has('baz'));
	}

	/**
	 * Test the Cookie::get method.
	 *
	 * @group laravel
	 */
	public function testGetMethodCanReturnValueOfCookies()
	{
		Cookie::$jar['foo'] = array('value' => 'bar');
		$this->assertEquals('bar', Cookie::get('foo'));

		Cookie::put('bar', 'baz');
		$this->assertEquals('baz', Cookie::get('bar'));
	}

	/**
	 * Test Cookie::forever method.
	 *
	 * @group laravel
	 */
	public function testForeverShouldUseATonOfMinutes()
	{
		Cookie::forever('foo', 'bar');
		$this->assertEquals('bar', Cookie::$jar['foo']['value']);
		$this->assertEquals(525600, Cookie::$jar['foo']['minutes']);

		Cookie::forever('bar', 'baz', 'path', 'domain', true);
		$this->assertEquals('path', Cookie::$jar['bar']['path']);
		$this->assertEquals('domain', Cookie::$jar['bar']['domain']);
		$this->assertTrue(Cookie::$jar['bar']['secure']);
	}

	/**
	 * Test the Cookie::forget method.
	 *
	 * @group laravel
	 */
	public function testForgetSetsCookieWithExpiration()
	{
		Cookie::forget('bar', 'path', 'domain', true);
		$this->assertEquals(-2000, Cookie::$jar['bar']['minutes']);
		$this->assertEquals('path', Cookie::$jar['bar']['path']);
		$this->assertEquals('domain', Cookie::$jar['bar']['domain']);
		$this->assertTrue(Cookie::$jar['bar']['secure']);
	}

}