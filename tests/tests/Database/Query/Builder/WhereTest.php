<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\Testcase_Test;
use Openbuildings\Kohana\Database_Query_Builder_Select;

/**
 * @package database
 * @group   database
 * @group   database.query
 * @group   database.query.builder
 * @group   database.query.builder.where
 */
class Database_Query_Builder_WhereTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::order_by
	 */
	public function test_order_by()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * ORDER BY `table1`.`name` DESC", 
			$query->order_by('table1.name', 'DESC')->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::limit
	 */
	public function test_limit()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * LIMIT 100", 
			$query->limit(100)->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::and_where
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::where
	 */
	public function test_and_where()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * WHERE `table1`.`name` = 'test' AND `table1`.`price` => 20", 
			$query->where('table1.name', '=', 'test')->and_where('table1.price', '=>', 20)->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::or_where
	 */
	public function test_or_where()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * WHERE `table1`.`name` = 'test' OR `table1`.`price` => 20", 
			$query->where('table1.name', '=', 'test')->or_where('table1.price', '=>', 20)->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::where_open
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::where_close
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::and_where_open
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::and_where_close
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::or_where_open
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::or_where_close
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Where::where_close_empty
	 	 * @covers Openbuildings\Kohana\Database_Query_Builder::_compile_conditions
	 */
	public function test_where_open()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * WHERE `table1`.`name` = 'test' AND (`table1`.`price` => 20 AND `table1`.`price` < 10) AND (`table1`.`name` LIKE 'test' AND `table1`.`name` IS NOT NULL) OR (`table1`.`description` LIKE 'test' OR `table1`.`description` IS NOT NULL) AND (`table1`.`description` IS NULL OR `table1`.`description` NOT NULL)", 
			$query
				->where('table1.name', '=', 'test')
				->where_open()
					->where('table1.price', '=>', 20)
					->and_where('table1.price', '<', 10)
				->where_close()
				->and_where_open()
					->where('table1.name', 'LIKE', 'test')
					->and_where('table1.name', 'IS NOT', NULL)
				->and_where_close()
				->or_where_open()
					->where('table1.description', 'LIKE', 'test')
					->or_where('table1.description', 'IS NOT', NULL)
				->or_where_close()
				->where_open()
					->where('table1.description', 'IS', NULL)
					->or_where('table1.description', 'NOT', NULL)
				->where_close_empty()
				->where_open()
				->where_close_empty()
				->compile()
		);
	}

	/**
 	 * @covers Openbuildings\Kohana\Database_Query_Builder::_compile_conditions
	 */
	public function test_where_operands()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * WHERE `table1`.`name` = 'test' AND `table1`.`name` IS NULL AND `table1`.`name` IS NOT NULL AND `table1`.`price` BETWEEN 10 AND 20 AND `table1`.`name` = 'other test'", 
			$query
				->where('table1.name', '=', 'test')
				->where('table1.name', '=', NULL)
				->where('table1.name', '!=', NULL)
				->where('table1.price', 'BETWEEN', array(10, 20))
				->where(array('table1.name', 'test_name'), '=', 'other test')
				->compile()
		);
	}

}

