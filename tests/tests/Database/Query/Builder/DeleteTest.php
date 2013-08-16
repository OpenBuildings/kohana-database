<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\Testcase_Test;
use Openbuildings\Kohana\Database_Query_Builder_Delete;

/**
 * @package database
 * @group   database
 * @group   database.query
 * @group   database.query.builder
 * @group   database.query.builder.delete
 */
class Database_Query_Builder_DeleteTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Delete::__construct
	 */
	public function test_construct()
	{
		$query = new Database_Query_Builder_Delete();

		$this->assertEquals(
			"DELETE FROM ``", 
			$query->compile()
		);

		$query = new Database_Query_Builder_Delete('table1');

		$this->assertEquals(
			"DELETE FROM `table1`", 
			$query->compile()
		);

		$this->assertEquals(Database::DELETE, $query->type());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Delete::table
	 */
	public function test_table()
	{
		$query = new Database_Query_Builder_Delete();

		$this->assertEquals(
			"DELETE FROM `table1`", 
			$query->table('table1')->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Delete::compile
	 */
	public function test_compile()
	{
		$query = new Database_Query_Builder_Delete('table1');

		$this->assertEquals(
			"DELETE FROM `table1`", 
			$query->compile()
		);

		$query = new Database_Query_Builder_Delete('table1');

		$this->assertEquals(
			"DELETE FROM `table1` WHERE `name` = 'test' ORDER BY `description` ASC LIMIT 10",
			$query->where('name', '=', 'test')->order_by('description', 'ASC')->limit(10)->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Delete::reset
	 */
	public function test_reset()
	{
		$query = new Database_Query_Builder_Delete('table1');

		$this->assertEquals(
			"DELETE FROM ``",
			$query->where('name', '=', 'test')->order_by('description', 'ASC')->limit(10)->reset()->compile()
		);	
	}

}

