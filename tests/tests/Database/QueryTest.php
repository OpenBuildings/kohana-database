<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\Database_Query;
use Openbuildings\Kohana\Testcase_Test;


class Test_Mysql_Result_Object {

	public $param;
	public $id;
	public $name;
	public $description;
	public $price;

	function __construct($param)
	{
		$this->param = $param;
	}
}

/**
 * @package database
 * @group   database
 * @group   database.query
 */
class Database_QueryTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\Database_Query::__construct
	 * @covers Openbuildings\Kohana\Database_Query::type
	 */
	public function test_construct()
	{
		$query = new Database_Query(Database::SELECT, 'TEST QUERY');

		$this->assertEquals(Database::SELECT, $query->type());
		$this->assertEquals('TEST QUERY', $query->compile());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query::__toString
	 */
	public function test_toString()
	{
		$query = $this->getMock('Openbuildings\Kohana\Database_Query', array('compile'), array(Database::SELECT, 'TEST QUERY'));

		$query->expects($this->at(0))
			->method('compile')
			->will($this->returnValue('COMPILED QUERY'));

		$query->expects($this->at(1))
			->method('compile')
			->will($this->throwException(new Exception('TEST ERROR')));

		$this->assertEquals('COMPILED QUERY', (string) $query);

		$this->assertContains('Exception [ 0 ]: TEST ERROR ~ ', (string) $query);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query::as_assoc
	 */
	public function test_as_assoc()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');
		$expected = array(
			array(
				'id' => 1, 
				'name' => 'test1', 
				'description' => 'test test3', 
				'price' => 0.22
			),
			array(
				'id' => 2, 
				'name' => 'test2', 
				'description' => 'test test4', 
				'price' => 231.99
			)
		);
		$result = $query->as_object('Test_Mysql_Result_Object')->as_assoc()->execute()->as_array();

		$this->assertEquals($expected, $result);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query::as_object
	 * @covers Openbuildings\Kohana\Database_Query::execute
	 * @covers Openbuildings\Kohana\Database_Result::__construct
	 */
	public function test_as_object()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');
		$expected = array(
			new Test_Mysql_Result_Object('tmp'),
			new Test_Mysql_Result_Object('tmp'),
		);

		$expected[0]->id = 1;
		$expected[0]->name = 'test1';
		$expected[0]->description = 'test test3';
		$expected[0]->price = 0.22;

		$expected[1]->id = 2;
		$expected[1]->name = 'test2';
		$expected[1]->description = 'test test4';
		$expected[1]->price = 231.99;		
		
		$result = $query->as_object(new Test_Mysql_Result_Object('tmp'), array('tmp'))->execute()->as_array();

		$this->assertEquals($expected, $result);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query::parameters
	 * @covers Openbuildings\Kohana\Database_Query::bind
	 * @covers Openbuildings\Kohana\Database_Query::compile
	 * @covers Openbuildings\Kohana\Database_Query::param
	 */
	public function test_parameters()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1 WHERE name = :param1 AND price > :param2 AND name IS NOT :param3');

		$param2 = 20;
		$query->parameters(array(':param1' => 'test'));
		$query->param(':param3', NULL);
		$query->bind(':param2', $param2);
		$param2 = 10;

		$expected = "SELECT * FROM table1 WHERE name = 'test' AND price > 10 AND name IS NOT NULL";

		$result = $query->compile();

		$this->assertEquals($expected, $result);
	}
}

