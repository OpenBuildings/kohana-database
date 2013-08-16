<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\Testcase_Test;
use Openbuildings\Kohana\Database_Query_Builder_Join;

/**
 * @package database
 * @group   database
 * @group   database.query
 * @group   database.query.builder
 * @group   database.query.builder.delete
 */
class Database_Query_Builder_JoinTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Join::__construct
	 */
	public function test_construct()
	{
		$query = new Database_Query_Builder_Join('table1');

		$this->assertEquals(
			"JOIN `table1` ON ()", 
			$query->compile()
		);

		$query = new Database_Query_Builder_Join('table1', 'LEFT');

		$this->assertEquals(
			"LEFT JOIN `table1` ON ()", 
			$query->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Join::on
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 */
	public function test_on()
	{
		$query = new Database_Query_Builder_Join('table1');

		$this->assertEquals(
			"JOIN `table1` ON (`table1`.`name` = `table2`.`name`)",
			$query->on('table1.name', '=', 'table2.name')->compile()
		);	

		$query = new Database_Query_Builder_Join('table1');
		$query->using('name');
		$query->on('table1.name', '=', 'table2.name');
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Join::using
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 */
	public function test_using()
	{
		$query = new Database_Query_Builder_Join('table1');

		$this->assertEquals(
			"JOIN `table1` USING (`table1`.`name`, `table2`.`name`)",
			$query->using('table1.name', 'table2.name')->compile()
		);

		$query = new Database_Query_Builder_Join('table1');
		$query->on('table1.name', '=', 'table2.name');
		$query->using('name');
	}


	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Join::compile
	 */
	public function test_compile()
	{
		$query = new Database_Query_Builder_Join('table1', 'LEFT');

		$this->assertEquals(
			"LEFT JOIN `table1` ON (`table1`.`name` = `table2`.`name`)",
			$query->on('table1.name', '=', 'table2.name')->compile()
		);

		$query = new Database_Query_Builder_Join('table1');

		$this->assertEquals(
			"JOIN `table1` USING (`name`)",
			$query->using('name')->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Join::reset
	 */
	public function test_reset()
	{
		$query = new Database_Query_Builder_Join('table1', 'LEFT');

		$this->assertEquals(
			"JOIN `` ON ()",
			$query->on('table1.name', '=', 'table2.name')->reset()->compile()
		);	
	}

}

