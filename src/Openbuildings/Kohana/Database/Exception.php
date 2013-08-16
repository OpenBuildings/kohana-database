<?php

namespace Openbuildings\Kohana;

/**
 * Database exceptions.
 *
 * @package    Kohana/Database
 * @category   Exceptions
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Database_Exception extends \Exception {

	public function __construct($message = "", array $variables = NULL, $code = 0, Exception $previous = NULL)
	{
		// Set the message
		if ($variables) 
		{
			$message = strtr($message, $variables);
		}

		// Pass the message and integer code to the parent
		parent::__construct($message, (int) $code, $previous);

		// Save the unmodified code
		// @link http://bugs.php.net/39615
		$this->code = $code;
	}

	public static function text(\Exception $e)
	{
		return sprintf('%s [ %s ]: %s ~ %s [ %d ]',
			get_class($e), $e->getCode(), strip_tags($e->getMessage()), $e->getFile(), $e->getLine());
	}
}
