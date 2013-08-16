<?php

namespace Openbuildings\Kohana;

abstract class Testcase_Test extends \PHPUnit_Framework_TestCase {

	public $database;
	public $database2;

	public $database_config = array(
		'type'       => 'MySQL',
		'connection' => array(
			'hostname'   => 'localhost',
			'database'   => 'test-database',
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

		Database::configure('default', $this->database_config);
		
		$this->database = Database::instance('default');
	}
}

