<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\DB;
use Openbuildings\Kohana\Testcase_Test;

/**
 * @package database
 * @group   database
 * @group   database.mysql
 */
class DBTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\DB::query
	 */
	public function test_query()
	{
		$result = DB::query(Database::SELECT, 'SELECT test');

		$this->assertInstanceOf('Openbuildings\Kohana\Database_Query', $result);
		$this->assertEquals(Database::SELECT, $result->type());
		$this->assertEquals('SELECT test', $result->compile());
	}

	/**
	 * @covers Openbuildings\Kohana\DB::select
	 */
	public function test_select()
	{
		$result = DB::select('name', 'id');

		$this->assertInstanceOf('Openbuildings\Kohana\Database_Query_Builder_Select', $result);
		$this->assertEquals(Database::SELECT, $result->type());
		$this->assertEquals('SELECT `name`, `id`', $result->compile());
	}


	/**
	 * @covers Openbuildings\Kohana\DB::select_array
	 */
	public function test_select_array()
	{
		$result = DB::select_array(array('name', 'id'));

		$this->assertInstanceOf('Openbuildings\Kohana\Database_Query_Builder_Select', $result);
		$this->assertEquals(Database::SELECT, $result->type());
		$this->assertEquals('SELECT `name`, `id`', $result->compile());
	}

	/**
	 * @covers Openbuildings\Kohana\DB::insert
	 */
	public function test_insert()
	{
		$result = DB::insert('table1');

		$this->assertInstanceOf('Openbuildings\Kohana\Database_Query_Builder_Insert', $result);
		$this->assertEquals(Database::INSERT, $result->type());
		$this->assertEquals('INSERT INTO `table1` () VALUES ', $result->compile());
	}

	/**
	 * @covers Openbuildings\Kohana\DB::update
	 */
	public function test_update()
	{
		$result = DB::update('table1');

		$this->assertInstanceOf('Openbuildings\Kohana\Database_Query_Builder_Update', $result);
		$this->assertEquals(Database::UPDATE, $result->type());
		$this->assertEquals('UPDATE `table1` SET ', $result->compile());
	}

	/**
	 * @covers Openbuildings\Kohana\DB::delete
	 */
	public function test_delete()
	{
		$result = DB::delete('table1');

		$this->assertInstanceOf('Openbuildings\Kohana\Database_Query_Builder_Delete', $result);
		$this->assertEquals(Database::DELETE, $result->type());
		$this->assertEquals('DELETE FROM `table1`', $result->compile());
	}

	/**
	 * @covers Openbuildings\Kohana\DB::expr
	 */
	public function test_expr()
	{
		$result = DB::expr('SQL EXPRESSION');

		$this->assertInstanceOf('Openbuildings\Kohana\Database_Expression', $result);
		$this->assertEquals('SQL EXPRESSION', $result->compile());
	}
}

