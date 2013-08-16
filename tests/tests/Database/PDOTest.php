<?php

use Openbuildings\Kohana\Database;
use Openbuildings\Kohana\Testcase_Test;
use Openbuildings\Kohana\DB;

class Test_ToString_Class2 {

	public function __construct()
	{
	}

	public function __toString()
	{
		return 'test to string';
	}
}
/**
 * @package database
 * @group   database
 * @group   database.pdo
 */
class Database_PDOTest extends Testcase_Test {

	public $database_config = array(
		'type'       => 'PDO',
		'connection' => array(
			'dsn'        => 'mysql:host=localhost;dbname=test-database',
			'username'   => 'root',
			'password'   => '',
			'persistent' => TRUE,

		),
		'table_prefix' => '',
		'charset'      => 'utf8',
	);

	public function setUp()
	{
		parent::setUp();

		$this->database = Database::instance('pdo', $this->database_config);
	}

	/**
	 * @covers Openbuildings\Kohana\Database_PDO::connect
	 */
	public function test_persistant()
	{
		$this->database->connect();
	}

	/**
	 * @covers Openbuildings\Kohana\Database_PDO::connect
	 * @covers Openbuildings\Kohana\Database_PDO::__construct
	 */
	public function test_not_persistant()
	{
		$config = $this->database_config;
		$config['connection']['persistent'] = FALSE;
		$config['identifier'] = 'other-identifier';
		$database = Database::instance('not_persistant_pdo', $config);
		$database->connect();
	}

	/**
	 * @covers Openbuildings\Kohana\Database::table_prefix
	 */
	public function test_table_prefix()
	{
		$config = $this->database_config;
		$config['table_prefix'] = 'test_test_';
		$database = Database::instance('table_prefix_pdo', $config);
		$this->assertEquals('test_test_', $database->table_prefix());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_PDO::disconnect
	 * @covers Openbuildings\Kohana\Database::disconnect
	 */
	public function test_disconnect()
	{
		$this->database->connect();
		$this->assertEquals(TRUE, $this->database->disconnect());
		$this->assertEquals(TRUE, $this->database->disconnect());
	}

	/**
	 * @covers Openbuildings\Kohana\Database_PDO::connect
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
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 */
	public function test_list_tables()
	{
		$this->database->list_tables();
	}

	/**
	 * @covers Openbuildings\Kohana\Database_PDO::count_records
	 */
	public function test_count_records()
	{
		$this->assertEquals(2, $this->database->count_records('table1'));
	}

	/**
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 */
	public function test_list_columns()
	{
		$this->database->list_columns('table1');
	}

	/**
	 * @covers Openbuildings\Kohana\Database_PDO::set_charset
	 */
	public function test_set_charset()
	{
		$this->database->set_charset('latin1');
		$this->assertEquals('latin1', $this->database->query(Database::SELECT, 'SHOW SESSION VARIABLES LIKE "character_set_client"')->get('Value'));
		$this->database->set_charset('utf8');
		$this->assertEquals('utf8', $this->database->query(Database::SELECT, 'SHOW SESSION VARIABLES LIKE "character_set_client"')->get('Value'));
	}

	/**
	 * @covers Openbuildings\Kohana\Database_PDO::quote_column
	 */
	public function test_quote_column()
	{
		$this->assertEquals('table1.price', $this->database->quote_column('table1.price'));
		$this->assertEquals('*', $this->database->quote_column('*'));
		$this->assertEquals('custom column', $this->database->quote_column(DB::expr('custom column')));
		$this->assertEquals('(SELECT id, name)', $this->database->quote_column(DB::select('id', 'name')));
		$this->assertEquals('database.table1.price', $this->database->quote_column('database.table1.price'));
		$this->assertEquals('table1.price AS mycolumn', $this->database->quote_column(array('table1.price', 'mycolumn')));
		$this->assertEquals('database.table1.price AS mycolumn', $this->database->quote_column(array('database.table1.price', 'mycolumn')));

		$config = $this->database_config;
		$config['table_prefix'] = 'some_prefix_';
		$prefixed_database = Database::instance('prefixed_pdo2', $config);

		$this->assertEquals('some_prefix_table1.name', $prefixed_database->quote_column('table1.name'));
	}

	/**
	 * @covers Openbuildings\Kohana\Database_PDO::quote_table
	 */
	public function test_quote_table()
	{
		$this->assertEquals('table1', $this->database->quote_table('table1'));
		$this->assertEquals('database.table1', $this->database->quote_table('database.table1'));
		$this->assertEquals('database.table1 AS mytable', $this->database->quote_table(array('database.table1', 'mytable')));
		$this->assertEquals('custom table', $this->database->quote_table(DB::expr('custom table')));
		$this->assertEquals('(SELECT id, name)', $this->database->quote_table(DB::select('id', 'name')));

		$config = $this->database_config;
		$config['table_prefix'] = 'some_prefix_';
		$prefixed_database = Database::instance('prefixed_pdo3', $config);

		$this->assertEquals('db.some_prefix_table1', $prefixed_database->quote_table('db.table1'));

	}

	/**
	 * @covers Openbuildings\Kohana\Database_PDO::quote_identifier
	 */
	public function test_quote_identifier()
	{
		$this->assertEquals('table.some-identifier', $this->database->quote_identifier('table.some-identifier'));
		$this->assertEquals('name AS alias', $this->database->quote_identifier(array('name', 'alias')));
		$this->assertEquals('custom identifier', $this->database->quote_identifier(DB::expr('custom identifier')));
		$this->assertEquals('(SELECT id, name)', $this->database->quote_identifier(DB::select('id', 'name')));
	}

	/**
	 * @expectedException Openbuildings\Kohana\Database_Exception
	 * @covers Openbuildings\Kohana\Database_PDO::query
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

		$database = Database::instance('another_connection_pdo', $this->database_config);
		
		$result = $this->database->query(Database::SELECT, 'SELECT * FROM `table1` LIMIT 1')->as_array();

		$result = $this->database->query(Database::SELECT, 'SELECT * FROM `table1` LIMIT 1', TRUE)->as_array();
		
		$this->assertEquals(array( (object) $expected[0]), $result);

		$result = $this->database->query(Database::SELECT, 'SELECT * FROM `table1` LIMIT 1', 'Test_ToString_Class2')->as_array();
		
		$this->assertEquals($expected[0]['name'], $result[0]->name);

		$this->database->query(Database::SELECT, 'WRONG SQL');
	}

	/**
	 * @covers Openbuildings\Kohana\Database_PDO::begin
	 * @covers Openbuildings\Kohana\Database_PDO::rollback
	 * @covers Openbuildings\Kohana\Database_PDO::commit
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
	}

	/**
	 * @covers Openbuildings\Kohana\Database_PDO::escape
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
		$this->assertSame("(SELECT name, id)", $this->database->quote(DB::select('name', 'id')));
		$this->assertSame("'free text'", $this->database->quote('free text'));
		$this->assertSame("('1', 20)", $this->database->quote(array('1', 20)));
		$this->assertSame("5.123000", $this->database->quote(5.123));
	}
}