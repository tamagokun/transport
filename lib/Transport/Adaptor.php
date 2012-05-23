<?php
namespace Transport;

abstract class Adaptor
{
	public $name,$transport;
	public $operands = array();
	public $order = array();
	public $id_attribute = 'id';

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

	public function find($params, $collection)
	{
	  
	}

	public function update($model)
	{
	  
	}

	public function insert($model)
	{
	  
	}

	public function destroy($model)
	{
	  
	}

	public function id($id)
	{
		return $id;
	}

	public function where($prop,$value)
	{
		list($att,$oper) = $this->parse_query($prop);
		return $this->translate_query($att,$oper,$value);
	}

	public function parse_query($string)
	{
		$parts = preg_split('/([\=\<\>\~\!]{1,2})/',$string,null,PREG_SPLIT_DELIM_CAPTURE);
		if(count($parts) < 2) $parts[] = '=';
		return $parts;
	}

	public function translate_query($term,$operator,$value)
	{
		if(!array_key_exists($operator,$this->operands)) return false;
		if($term == 'id') $term = $this->id_attribute;
		if($operator == $this->operands['=']) return array($term => $value);
		return array($term => array($this->operands[$operator] => $value));
	}
}
