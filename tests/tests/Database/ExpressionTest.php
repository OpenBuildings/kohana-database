<?php

use Openbuildings\Kohana\Database_Expression;
use Openbuildings\Kohana\Testcase_Test;

/**
 * @package database
 * @group   database
 * @group   database.expression
 */
class Database_ExpressionTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\Database_Expression::__construct
	 * @covers Openbuildings\Kohana\Database_Expression::value
	 */
	public function test_construct()
	{
		$expr = new Database_Expression('some expression :test', array(':test' => 'test2'));

		$this->assertEquals('some expression :test', $expr->value());
		$this->assertEquals("some expression 'test2'", $expr->compile());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Expression::__toString
	 */
	public function test_toString()
	{
		$expr = new Database_Expression('some expression :test', array(':test' => 'test2'));

		$this->assertEquals('some expression :test', (string) $expr);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Expression::parameters
	 * @covers Openbuildings\Kohana\Database_Expression::bind
	 * @covers Openbuildings\Kohana\Database_Expression::compile
	 * @covers Openbuildings\Kohana\Database_Expression::param
	 */
	public function test_parameters()
	{
		$expr = new Database_Expression('some expression :param1 :param2 :param3');

		$param2 = 20;
		$expr->parameters(array(':param1' => 'test'));
		$expr->param(':param3', NULL);
		$expr->bind(':param2', $param2);
		$param2 = 10;

		$expected = "some expression 'test' 10 NULL";

		$result = $expr->compile();

		$this->assertEquals($expected, $result);
	}
}

