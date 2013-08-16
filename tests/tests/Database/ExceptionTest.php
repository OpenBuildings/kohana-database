<?php

use Openbuildings\Kohana\Database_Exception;

/**
 * @package database
 * @group   database
 * @group   database.exception
 */
class Database_ExceptionTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers Openbuildings\Kohana\Database_Exception::__construct
	 */
	public function test_parameters()
	{
		$exception = new Database_Exception('message :param1, :param2', array(':param2' => 'p2', ':param1' => 'p1'), 10);

		$this->assertEquals('message p1, p2', $exception->getMessage());
		$this->assertEquals(10, $exception->getCode());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Exception::text
	 */
	public function test_text()
	{
		$exception = new Database_Exception('message :param1, :param2', array(':param2' => 'p2', ':param1' => 'p1'), 10);

		$this->assertContains('Openbuildings\Kohana\Database_Exception [ 10 ]: message p1, p2 ~ ', Database_Exception::text($exception));
	}

}

