<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\Testcase_Test;
use Openbuildings\Kohana\DB;

class Test_ToString_Class {

	public function __toString()
	{
		return 'test to string';
	}
}
/**
 * @package database
 * @group   database
 * @group   database.mysql
 */
class Database_MySQLTest extends Testcase_Test {

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::connect
	 */
	public function test_not_persistant()
	{
		$config = $this->database_config;
		$config['connection']['persistent'] = FALSE;
		$database = Database::instance('not_persistant', $config);
		$database->connect();
	}

	/**
	 * @covers Openbuildings\Kohana\Database::configure
	 */
	public function test_configure()
	{
		Database::configure('new_configureation', $this->database_config);

		$database = Database::instance('not_persistant');
		$database->connect();
	}

	/**
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 * @covers Openbuildings\Kohana\Database_MySQL::connect
	 * @covers Openbuildings\Kohana\Database_MySQL::_select_db
	 */
	public function test_exception_on_select_db()
	{
		$config = $this->database_config;
		$config['connection']['database'] = 'not-existant';
		$database = Database::instance('error_select_db', $config);
		$database->connect();
	}

	/**
	 * @covers Openbuildings\Kohana\Database::table_prefix
	 */
	public function test_table_prefix()
	{
		$config = $this->database_config;
		$config['table_prefix'] = 'test_test_';
		$database = Database::instance('table_prefix', $config);
		$this->assertEquals('test_test_', $database->table_prefix());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::disconnect
	 * @covers Openbuildings\Kohana\Database::disconnect
	 */
	public function test_disconnect()
	{
		$this->database->connect();
		$this->assertEquals(TRUE, $this->database->disconnect());
		$this->assertEquals(TRUE, $this->database->disconnect());
	}

	/**
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 * @covers Openbuildings\Kohana\Database::instance
	 */
	public function test_instance()
	{
		$database = Database::instance();
		$this->assertInstanceOf('Openbuildings\Kohana\Database_MySQL', $database);
		$database->connect();

		$database = Database::instance('not_set');
	}

	/**
	 * @covers Openbuildings\Kohana\Database::__toString
	 */
	public function test_toString()
	{
		$this->assertEquals('default', Database::instance());
	}

	/**
	 * @covers Openbuildings\Kohana\Database::__destruct
	 */
	public function test_destruct()
	{
		$database = $this->getMock('Openbuildings\Kohana\Database_MySQL', array('disconnect'), array('default', $this->database_config));
		$database->expects($this->once())->method('disconnect');

		$database->__destruct();
	}
	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::connect
	 * @covers Openbuildings\Kohana\Database::connect
	 */
	public function test_connection_variables()
	{
		$config = $this->database_config;
		$config['connection']['variables'] = array(
			'sort_buffer_size' => 200000,
			'auto_increment_increment' => 10,
		);
		$database = Database::instance('variables_test', $config);
		
		$this->assertEquals(200000, $database->query(Database::SELECT, 'SHOW SESSION VARIABLES LIKE "sort_buffer_size"')->get('Value'));

		$this->assertEquals(10, $database->query(Database::SELECT, 'SHOW SESSION VARIABLES LIKE "auto_increment_increment"')->get('Value'));
	}

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::set_charset
	 */
	public function test_set_charset()
	{
		$this->database->set_charset('latin1');
		$this->assertEquals('latin1', $this->database->query(Database::SELECT, 'SHOW SESSION VARIABLES LIKE "character_set_client"')->get('Value'));
		$this->database->set_charset('utf8');
		$this->assertEquals('utf8', $this->database->query(Database::SELECT, 'SHOW SESSION VARIABLES LIKE "character_set_client"')->get('Value'));
	}

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::connect
	 * @covers Openbuildings\Kohana\Database_MySQL::_select_db
	 */
	public function test_connect()
	{
		$this->database->connect();
		$this->assertInstanceOf('Openbuildings\Kohana\Database_MySQL', $this->database);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::list_tables
	 */
	public function test_list_tables()
	{
		$this->assertEquals(array('table1', 'table2'), $this->database->list_tables());
		$this->assertEquals(array('table2'), $this->database->list_tables('table2'));
	}

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::count_records
	 */
	public function test_count_records()
	{
		$this->assertEquals(2, $this->database->count_records('table1'));
	}

	/**
	 * @covers Openbuildings\Kohana\Database::datatype
	 */
	public function test_datatype()
	{
		$this->assertEquals(array('type' => 'int', 'min' => '-2147483648', 'max' => '2147483647'), $this->database->datatype('int'));
		$this->assertEquals(array('type' => 'string'), $this->database->datatype('varchar'));
		$this->assertEquals(array(), $this->database->datatype('nonexistent'));
	}

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::list_columns
	 * @covers Openbuildings\Kohana\Database::_parse_type
	 */
	public function test_list_columns()
	{
		$this->assertEquals(array(
			'id' => array(
				'type' => 'int',
				'min' => '0',
				'max' => '4294967295',
				'column_name' => 'id',
				'column_default' => '',
				'data_type' => 'int unsigned',
				'is_nullable' => '',
				'ordinal_position' => '1',
				'display' => '11',
				'comment' => '',
				'extra' => 'auto_increment',
				'key' => 'PRI',
				'privileges' => 'select,insert,update,references',
			),
			'name' => array(
				'type' => 'string',
				'column_name' => 'name',
				'column_default' => '',
				'data_type' => 'varchar',
				'is_nullable' => '1',
				'ordinal_position' => '2',
				'character_maximum_length' => '32',
				'collation_name' => 'utf8_general_ci',
				'comment' => '',
				'extra' => '',
				'key' => 'UNI',
				'privileges' => 'select,insert,update,references',
			),
			'description' => array(
				'type' => 'string',
				'column_name' => 'description',
				'column_default' => '',
				'data_type' => 'varchar',
				'is_nullable' => '',
				'ordinal_position' => '3',
				'character_maximum_length' => '255',
				'collation_name' => 'utf8_general_ci',
				'comment' => '',
				'extra' => '',
				'key' => '',
				'privileges' => 'select,insert,update,references',
			),
			'price' => array(
				'type' => 'float',
				'exact' => '1',
				'column_name' => 'price',
				'column_default' => '',
				'data_type' => 'decimal',
				'is_nullable' => '1',
				'ordinal_position' => '4',
				'numeric_scale' => '2',
				'numeric_precision' => '10',
				'comment' => '',
				'extra' => '',
				'key' => '',
				'privileges' => 'select,insert,update,references',
			)
		), $this->database->list_columns('table1'));

		$this->assertEquals(array(
			'price' => array(
				'type' => 'float',
				'exact' => TRUE,
				'column_name' => 'price',
				'column_default' => NULL,
				'data_type' => 'decimal',
				'is_nullable' => TRUE,
				'ordinal_position' => '1',
				'numeric_scale' => '2',
				'numeric_precision' => '10',
				'comment' => '',
				'extra' => '',
				'key' => '',
				'privileges' => 'select,insert,update,references',
			)
		), $this->database->list_columns('table1', 'price'));
	}

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::quote_column
	 */
	public function test_quote_column()
	{
		$this->assertEquals('`table1`.`price`', $this->database->quote_column('table1.price'));
		$this->assertEquals('*', $this->database->quote_column('*'));
		$this->assertEquals('custom column', $this->database->quote_column(DB::expr('custom column')));
		$this->assertEquals('(SELECT `id`, `name`)', $this->database->quote_column(DB::select('id', 'name')));
		$this->assertEquals('`database`.`table1`.`price`', $this->database->quote_column('database.table1.price'));
		$this->assertEquals('`table1`.`price` AS `mycolumn`', $this->database->quote_column(array('table1.price', 'mycolumn')));
		$this->assertEquals('`database`.`table1`.`price` AS `mycolumn`', $this->database->quote_column(array('database.table1.price', 'mycolumn')));

		$config = $this->database_config;
		$config['table_prefix'] = 'some_prefix_';
		$prefixed_database = Database::instance('prefixed2', $config);

		$this->assertEquals('`some_prefix_table1`.`name`', $prefixed_database->quote_column('table1.name'));
	}

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::quote_table
	 */
	public function test_quote_table()
	{
		$this->assertEquals('`table1`', $this->database->quote_table('table1'));
		$this->assertEquals('`database`.`table1`', $this->database->quote_table('database.table1'));
		$this->assertEquals('`database`.`table1` AS `mytable`', $this->database->quote_table(array('database.table1', 'mytable')));
		$this->assertEquals('custom table', $this->database->quote_table(DB::expr('custom table')));
		$this->assertEquals('(SELECT `id`, `name`)', $this->database->quote_table(DB::select('id', 'name')));

		$config = $this->database_config;
		$config['table_prefix'] = 'some_prefix_';
		$prefixed_database = Database::instance('prefixed3', $config);

		$this->assertEquals('`db`.`some_prefix_table1`', $prefixed_database->quote_table('db.table1'));

	}

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::quote_identifier
	 */
	public function test_quote_identifier()
	{
		$this->assertEquals('`table`.`some-identifier`', $this->database->quote_identifier('table.some-identifier'));
		$this->assertEquals('`name` AS `alias`', $this->database->quote_identifier(array('name', 'alias')));
		$this->assertEquals('custom identifier', $this->database->quote_identifier(DB::expr('custom identifier')));
		$this->assertEquals('(SELECT `id`, `name`)', $this->database->quote_identifier(DB::select('id', 'name')));
	}

	/**
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 * @covers Openbuildings\Kohana\Database_MySQL::query
	 */
	public function test_query()
	{
		$expected = array(
			array(
				'id' => 1, 
				'name' => 'test1', 
				'description' => 'test test3', 
				'price' => 0.22
			)
		);

		$result = $this->database->query(Database::SELECT, 'SELECT * FROM `table1` LIMIT 1')->as_array();
		
		$this->assertEquals($expected, $result);

		$result = $this->database->query(Database::INSERT, 'INSERT INTO `table1` SET id = 3, name = "test new"');

		$this->assertEquals(array(3, 1), $result);

		$result = $this->database->query(Database::DELETE, 'DELETE FROM `table1` WHERE id = 3');

		$this->assertEquals(1, $result);

		$database = Database::instance('another_connection', $this->database_config);
		
		$result = $this->database->query(Database::SELECT, 'SELECT * FROM `table1` LIMIT 1')->as_array();
		
		$this->assertEquals($expected, $result);

		$this->database->query(Database::SELECT, 'WRONG SQL');
	}

	/**
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 * @covers Openbuildings\Kohana\Database_MySQL::begin
	 * @covers Openbuildings\Kohana\Database_MySQL::rollback
	 * @covers Openbuildings\Kohana\Database_MySQL::commit
	 */
	public function test_transactions()
	{
		$this->database->begin();

		$this->assertEquals(2, $this->database->count_records('table1'));

		$this->database->query(Database::INSERT, 'INSERT INTO `table1` SET id = 3, name = "test new"');

		$this->assertEquals(3, $this->database->count_records('table1'));

		$this->database->rollback();

		$this->assertEquals(2, $this->database->count_records('table1'));

		$this->database->begin();

		$this->database->query(Database::INSERT, 'INSERT INTO `table1` SET id = 3, name = "test new"');

		$this->database->commit();

		$this->assertEquals(3, $this->database->count_records('table1'));

		$this->database->query(Database::DELETE, 'DELETE FROM `table1` WHERE id = 3');

		$this->database->begin('WRONG ISOLATION LEVEL');
	}

	/**
	 * @covers Openbuildings\Kohana\Database_MySQL::escape
	 */
	public function test_escape()
	{
		$this->assertEquals("'some value'", $this->database->escape('some value'));
		$this->assertEquals("'12'", $this->database->escape(12));
	}

	/**
	 * @covers Openbuildings\Kohana\Database::quote
	 */
	public function test_quote()
	{
		$this->assertSame('NULL', $this->database->quote(NULL));
		$this->assertSame(10, $this->database->quote(10));
		$this->assertSame("'1'", $this->database->quote(TRUE));
		$this->assertSame("'0'", $this->database->quote(FALSE));
		$this->assertSame("'test to string'", $this->database->quote(new Test_ToString_Class()));
		$this->assertSame("test", $this->database->quote(DB::expr('test')));
		$this->assertSame("(SELECT `name`, `id`)", $this->database->quote(DB::select('name', 'id')));
		$this->assertSame("'free text'", $this->database->quote('free text'));
		$this->assertSame("('1', 20)", $this->database->quote(array('1', 20)));
		$this->assertSame("5.123000", $this->database->quote(5.123));
	}
}