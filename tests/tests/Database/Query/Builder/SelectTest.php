<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\Testcase_Test;
use Openbuildings\Kohana\DB;
use Openbuildings\Kohana\Database_Query_Builder_Select;

/**
 * @package database
 * @group   database
 * @group   database.query
 * @group   database.query.builder
 * @group   database.query.builder.select
 */
class Database_Query_Builder_SelectTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::__construct
	 */
	public function test_construct()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT *", 
			$query->compile()
		);

		$query = new Database_Query_Builder_Select(array('table1.name', 'price'));

		$this->assertEquals(
			"SELECT `table1`.`name`, `price`", 
			$query->compile()
		);

		$this->assertEquals(Database::SELECT, $query->type());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::distinct
	 */
	public function test_distinct()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT DISTINCT *", 
			$query->distinct(TRUE)->compile()
		);

		$this->assertEquals(
			"SELECT *", 
			$query->distinct(FALSE)->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::select
	 */
	public function test_select()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT `table1`.`name` AS `test_name`, `description`, `price`", 
			$query->select(array('table1.name', 'test_name'), 'description')->select('price')->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::select_array
	 */
	public function test_select_array()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT `table1`.`name` AS `test_name`, `price`, `description`", 
			$query->select_array(array(array('table1.name', 'test_name'), 'price'))->select_array(array('description'))->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::from
	 */
	public function test_from()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * FROM `table1`, `table2`", 
			$query->from('table1', 'table2')->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::join
	 */
	public function test_join()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * LEFT JOIN `table1` ON ()", 
			$query->join('table1', 'LEFT')->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::on
	 */
	public function test_on()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * FROM `table2` LEFT JOIN `table1` ON (`table1`.`name` = `table2`.`name`)", 
			$query->from('table2')->join('table1', 'LEFT')->on('table1.name', '=', 'table2.name')->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::using
	 */
	public function test_using()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * LEFT JOIN `table1` USING (`name`)", 
			$query->join('table1', 'LEFT')->using('name')->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::group_by
	 */
	public function test_group_by()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * GROUP BY `table1`.`name`, `price`", 
			$query->group_by('table1.name')->group_by('price')->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::and_having
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::having
	 */
	public function test_and_having()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * HAVING `table1`.`name` = 'test' AND `table1`.`price` => 20", 
			$query->having('table1.name', '=', 'test')->and_having('table1.price', '=>', 20)->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::or_having
	 */
	public function test_or_having()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * HAVING `table1`.`name` = 'test' OR `table1`.`price` => 20", 
			$query->having('table1.name', '=', 'test')->or_having('table1.price', '=>', 20)->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::having_open
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::having_close
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::and_having_open
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::and_having_close
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::or_having_open
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::or_having_close
	 */
	public function test_having_open()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * HAVING `table1`.`name` = 'test' AND (`table1`.`price` => 20 AND `table1`.`price` < 10) AND (`table1`.`name` LIKE 'test' AND `table1`.`name` IS NOT NULL) OR (`table1`.`description` LIKE 'test' OR `table1`.`description` IS NOT NULL)", 
			$query
				->having('table1.name', '=', 'test')
				->having_open()
					->having('table1.price', '=>', 20)
					->and_having('table1.price', '<', 10)
				->having_close()
				->and_having_open()
					->having('table1.name', 'LIKE', 'test')
					->and_having('table1.name', 'IS NOT', NULL)
				->and_having_close()
				->or_having_open()
					->having('table1.description', 'LIKE', 'test')
					->or_having('table1.description', 'IS NOT', NULL)
				->or_having_close()
				->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::union
	 */
	public function test_union()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * FROM `table1` UNION ALL SELECT * FROM `table2`", 
			$query->from('table1')->union('table2')->compile()
		);

		$this->assertEquals(
			"SELECT * FROM `table1` UNION ALL SELECT * FROM `table2` UNION ALL SELECT `name` FROM `table2`", 
			$query->from('table1')->union(DB::select('name')->from('table2'))->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::offset
	 */
	public function test_offset()
	{
		$query = new Database_Query_Builder_Select();

		$this->assertEquals(
			"SELECT * FROM `table1` OFFSET 100", 
			$query->from('table1')->offset(100)->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::compile
	 * @covers Openbuildings\Kohana\Database_Query_Builder::_compile_join
	 * @covers Openbuildings\Kohana\Database_Query_Builder::_compile_group_by
	 * @covers Openbuildings\Kohana\Database_Query_Builder::_compile_order_by
	 */
	public function test_compile()
	{
		$query = new Database_Query_Builder_Select(array('name', 'price'));

		$this->assertEquals(
			"SELECT DISTINCT `name`, `price` FROM `table1` JOIN `table2` ON (`table1`.`name` = `table2`.`name`) WHERE `name` = 'text' GROUP BY `name`, `test_name` HAVING `name` IS NOT NULL ORDER BY `name` DESC, `test_name` DESC LIMIT 100 OFFSET 100 UNION ALL SELECT * FROM `table2`", 
			$query
				->from('table1')
				->where('name', '=', 'text')
				->having('name', 'IS NOT', NULL)
				->group_by('name')
				->group_by(array('name', 'test_name'))
				->distinct(TRUE)
				->offset(100)
				->limit(100)
				->join('table2')
				->on('table1.name', '=', 'table2.name')
				->order_by('name', 'DESC')
				->order_by(array('name', 'test_name'), 'DESC')
				->union('table2')
				->compile()
		);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_Query_Builder_Select::reset
	 */
	public function test_reset()
	{
		$query = new Database_Query_Builder_Select(array('name', 'price'));

		$this->assertEquals(
			"SELECT *", 
			$query
				->from('table1')
				->where('name', '=', 'text')
				->having('name', 'IS NOT', NULL)
				->group_by('name')
				->distinct(TRUE)
				->offset(100)
				->limit(100)
				->join('table2')
				->on('table1.name', '=', 'table2.name')
				->order_by('name', 'DESC')
				->union('table2')
				->reset()
				->compile()
		);
	}
}