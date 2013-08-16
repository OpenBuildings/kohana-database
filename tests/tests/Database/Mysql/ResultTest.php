<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\Database_Query;
use Openbuildings\Kohana\Database_Result;
use Openbuildings\Kohana\Testcase_Test;

class Test_Mysql_Result_Object2 {

	public $param;
	public $id;
	public $name;
	public $description;
	public $price;

	function __construct()
	{
	}
}

/**
 * @package database
 * @group   database
 * @group   database.result
 */
class Database_Mysql_ResultTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL_Result::seek
	 * @covers Openbuildings\Kohana\Database_MySQL_Result::__construct
	 * @covers Openbuildings\Kohana\Database_MySQL_Result::__destruct
	 * @covers Openbuildings\Kohana\Database_MySQL_Result::current
	 */
	public function test_ArrayAccess()
	{
		$expected = array(
			'id' => 1, 
			'name' => 'test1', 
			'description' => 'test test3', 
			'price' => 0.22
		);

		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');
		$result = $query->execute();

		$this->assertTrue($result->seek(1));
		$result->prev();
		$current = $result->current();
		$this->assertEquals(1, $current['id']);
		$this->assertFalse($result->seek(3));
		$this->assertNotNull($result->current());

		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');
		$result = $query->as_object()->execute();
		$this->assertEquals( (object) $expected, $result->current());

		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');
		$result = $query->as_object('Test_Mysql_Result_Object2')->execute();
		$this->assertEquals($expected['name'], $result->current()->name);
		$this->assertEquals($expected['description'], $result->current()->description);
	}
}

