<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\Testcase_Test;
use Openbuildings\Kohana\DB;
use Openbuildings\Kohana\Database_Query_Builder_Update;

/**
 * @package database
 * @group   database
 * @group   database.query
 * @group   database.query.builder
 * @group   database.query.builder.update
 */
class Database_Query_Builder_UpdateTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Update::__construct
	 */
	public function test_construct()
	{
		$query = new Database_Query_Builder_Update();

		$this->assertEquals(
			"UPDATE `` SET ", 
			$query->compile()
		);

		$query = new Database_Query_Builder_Update('table1');

		$this->assertEquals(
			"UPDATE `table1` SET ", 
			$query->compile()
		);

		$this->assertEquals(Database::UPDATE, $query->type());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Update::table
	 */
	public function test_table()
	{
		$query = new Database_Query_Builder_Update();

		$this->assertEquals(
			"UPDATE `table1` SET ", 
			$query->table('table1')->compile()
		);
	}


	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Update::value
	 */
	public function test_value()
	{
		$query = new Database_Query_Builder_Update('table1');

		$this->assertEquals(
			"UPDATE `table1` SET `one` = NULL, `two` = 'text'", 
			$query->value('one', NULL)->value('two', 'text')->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Update::compile
	 */
	public function test_compile()
	{
		$query = new Database_Query_Builder_Update('table1');

		$this->assertEquals(
			"UPDATE `table1` SET `name` = 'new', `price` = 20 WHERE `name` = 'test' ORDER BY `name` DESC LIMIT 10", 
			$query
				->set(array('name' => 'new', 'price' => 20))
				->where('name', '=', 'test')
				->order_by('name', 'DESC')
				->limit(10)
				->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Update::set
	 * @covers Openbuildings\Kohana\Database_Query_Builder::_compile_set
	 */
	public function test_set()
	{
		$query = new Database_Query_Builder_Update('table1');

		$this->assertEquals(
			"UPDATE `table1` SET `one` = 10, `two` = 20", 
			$query->set(array('one' => 10, 'two' => 20))->compile()
		);
	}
	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Update::reset
	 */
	public function test_reset()
	{
		$query = new Database_Query_Builder_Update('table1');

		$this->assertEquals(
			"UPDATE `` SET ", 
			$query->set(array('one' => 10, 'two' => 20))->reset()->compile()
		);
	}
}

