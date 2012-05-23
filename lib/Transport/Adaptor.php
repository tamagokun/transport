<?php
namespace Transport;

abstract class Adaptor
{
	public $transport;
	public $name = false;

	private static $map = array(
		'mongodb' => 'Adaptor\Mongo',
		'file'    => 'Adaptor\FileSystem',
		'sql'     => 'Adaptor\Sql'
	);

	public static function find($scheme)
	{
		$adaptor = array_key_exists($scheme, self::$map)? self::$map[$scheme] : null;
		return is_null($adaptor)? false : new $adaptor();
	}

	public function connect($dsn)
	{
	  return false;
	}
}
