<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\Database_Query;
use Openbuildings\Kohana\Database_Result;
use Openbuildings\Kohana\Testcase_Test;

/**
 * @package database
 * @group   database
 * @group   database.result
 */
class Database_ResultTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\Database_Result::__construct
	 */
	public function test_construct()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');

		$this->assertInstanceOf('Openbuildings\Kohana\Database_Result', $query->execute());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Result::cached
	 */
	public function test_cached()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');

		$this->assertInstanceOf('Openbuildings\Kohana\Database_Result_Cached', $query->execute()->cached());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Result::get
	 */
	public function test_get()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');

		$this->assertEquals('test1', $query->execute()->get('name'));

		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1 LIMIT 0');

		$this->assertEquals('test default', $query->execute()->get('name', 'test default'));

		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');

		$this->assertEquals('test1', $query->as_object()->execute()->get('name'));

		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1 LIMIT 0');

		$this->assertEquals('test default', $query->as_object()->execute()->get('name', 'test default'));
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Result::count
	 */
	public function test_count()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');

		$this->assertEquals(2, $query->execute()->count());
	}


	/**
	 * @covers Openbuildings\Kohana\Database_Result::offsetExists
	 * @covers Openbuildings\Kohana\Database_Result::offsetGet
	 * @covers Openbuildings\Kohana\Database_Result::valid
	 * @covers Openbuildings\Kohana\Database_Result::key
	 * @covers Openbuildings\Kohana\Database_Result::next
	 * @covers Openbuildings\Kohana\Database_Result::prev
	 * @covers Openbuildings\Kohana\Database_Result::rewind
	 */
	public function test_ArrayAccess()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');
		$result = $query->execute();

		$this->assertTrue(isset($result[0]));
		$this->assertFalse(isset($result[4]));

		$expected = array(
			'id' => 1, 
			'name' => 'test1', 
			'description' => 'test test3', 
			'price' => 0.22
		);

		$this->assertEquals($expected, $result[0]);
		$this->assertNull($result[4]);

		foreach ($result as $i => $item) 
		{
			$this->assertEquals($i + 1, $item['id']);
		}

		for ($result->rewind(), $i = 1; $result->valid(); $result->next(), $i++) 
		{
			$item = $result->current();
			$this->assertEquals($i, $item['id']);
		}
	}

	/**
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 * @covers Openbuildings\Kohana\Database_Result::offsetSet
	 */
	public function test_offsetSet()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');
		$result = $query->execute();
		$result[0] = array();
	}


	/**
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 * @covers Openbuildings\Kohana\Database_Result::offsetUnset
	 */
	public function test_offsetUnset()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');
		$result = $query->execute();
		unset($result[0]);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Result::as_array
	 */
	public function test_as_array_associative()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');
		$result = $query->execute();

		$array = $result->as_array();

		$expected = array(
			array('id' => 1, 'name' => 'test1', 'description' => 'test test3', 'price' => 0.22),
			array('id' => 2, 'name' => 'test2', 'description' => 'test test4', 'price' => 231.99)
		);

		$this->assertEquals($expected, $array);

		$array = $result->as_array('name');
		$expected = array(
			'test1' => array('id' => 1, 'name' => 'test1', 'description' => 'test test3', 'price' => 0.22),
			'test2' => array('id' => 2, 'name' => 'test2', 'description' => 'test test4', 'price' => 231.99)
		);

		$this->assertEquals($expected, $array);

		$array = $result->as_array(NULL, 'description');
		$expected = array('test test3', 'test test4');

		$this->assertEquals($expected, $array);

		$array = $result->as_array('name', 'description');
		$expected = array('test1' => 'test test3', 'test2' => 'test test4');

		$this->assertEquals($expected, $array);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Result::as_array
	 */
	public function test_as_array_object()
	{
		$query = new Database_Query(Database::SELECT, 'SELECT * FROM table1');
		$result = $query->as_object()->execute();

		$array = $result->as_array();

		$expected = array(
			(object) array('id' => 1, 'name' => 'test1', 'description' => 'test test3', 'price' => 0.22),
			(object) array('id' => 2, 'name' => 'test2', 'description' => 'test test4', 'price' => 231.99)
		);

		$this->assertEquals($expected, $array);

		$array = $result->as_array('name');
		$expected = array(
			'test1' => (object) array('id' => 1, 'name' => 'test1', 'description' => 'test test3', 'price' => 0.22),
			'test2' => (object) array('id' => 2, 'name' => 'test2', 'description' => 'test test4', 'price' => 231.99)
		);

		$this->assertEquals($expected, $array);

		$array = $result->as_array(NULL, 'description');
		$expected = array('test test3', 'test test4');

		$this->assertEquals($expected, $array);

		$array = $result->as_array('name', 'description');
		$expected = array('test1' => 'test test3', 'test2' => 'test test4');

		$this->assertEquals($expected, $array);
	}
}

