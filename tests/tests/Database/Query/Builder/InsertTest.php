<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\Testcase_Test;
use Openbuildings\Kohana\DB;
use Openbuildings\Kohana\Database_Query_Builder_Insert;

/**
 * @package database
 * @group   database
 * @group   database.query
 * @group   database.query.builder
 * @group   database.query.builder.insert
 */
class Database_Query_Builder_InsertTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Insert::__construct
	 */
	public function test_construct()
	{
		$query = new Database_Query_Builder_Insert();

		$this->assertEquals(
			"INSERT INTO `` () VALUES ", 
			$query->compile()
		);

		$query = new Database_Query_Builder_Insert('table1', array('name', 'price'));

		$this->assertEquals(
			"INSERT INTO `table1` (`name`, `price`) VALUES ", 
			$query->compile()
		);

		$this->assertEquals(Database::INSERT, $query->type());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Insert::table
	 */
	public function test_table()
	{
		$query = new Database_Query_Builder_Insert();

		$this->assertEquals(
			"INSERT INTO `table1` () VALUES ", 
			$query->table('table1')->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Insert::columns
	 */
	public function test_columns()
	{
		$query = new Database_Query_Builder_Insert('table1');

		$this->assertEquals(
			"INSERT INTO `table1` (`name`, `price`) VALUES ", 
			$query->columns(array('name', 'price'))->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Insert::values
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 */
	public function test_values()
	{
		$query = new Database_Query_Builder_Insert('table1', array('name', 'price'));

		$this->assertEquals(
			"INSERT INTO `table1` (`name`, `price`) VALUES ('one', 10), ('two', 20)", 
			$query->values(array('one', 10), array('two', 20))->compile()
		);

		$query = new Database_Query_Builder_Insert('table1', array('name', 'price'));
		$query->select(DB::select(array('name', 'price')))->values(array('one', 10));
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Insert::select
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 */
	public function test_select()
	{
		$query = new Database_Query_Builder_Insert('table1', array('name', 'price'));

		$this->assertEquals(
			"INSERT INTO `table1` (`name`, `price`) SELECT `name` AS `price` FROM `table2`", 
			$query->select(DB::select(array('name', 'price'))->from('table2'))->compile()
		);

		$query = new Database_Query_Builder_Insert('table1', array('name', 'price'));
		$query->select(DB::update('table2'));
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Insert::compile
	 */
	public function test_compile()
	{
		$query = new Database_Query_Builder_Insert('table1', array('name', 'price'));

		$this->assertEquals(
			"INSERT INTO `table1` (`name`, `price`) VALUES ('one', 10), ('two', 20)", 
			$query->values(array('one', 10), array('two', 20))->compile()
		);

		$this->assertEquals(
			"INSERT INTO `table1` (`name`, `price`) VALUES ('one', 10), ('two', 20)", 
			$query->compile($this->database)
		);

		$query = new Database_Query_Builder_Insert('table1', array('name', 'price'));

		$this->assertEquals(
			"INSERT INTO `table1` (`name`, `price`) SELECT `name` AS `price` FROM `table2`", 
			$query->select(DB::select(array('name', 'price'))->from('table2'))->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Insert::reset
	 */
	public function test_reset()
	{
		$query = new Database_Query_Builder_Insert('table1', array('name', 'price'));

		$this->assertEquals(
			"INSERT INTO `` () VALUES ", 
			$query->values(array('one', 10), array('two', 20))->reset()->compile()
		);
	}
}

