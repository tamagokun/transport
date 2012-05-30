<?php
namespace Transport;

abstract class Model
{
	const collection = "";

	public $id;
	protected $attributes = array();
	protected $dirty = array();

	/* Public */
	public function __construct($attrs = array())
	{
		if($attrs) foreach($attrs as $key=>$value) $this->attributes[$key] = $value;
		$this->id = $this->id();
	}

	public function collection()
	{
		if(!empty(static::collection)) return static::collection;
		return strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', get_class($this)));
	}

	public function is_dirty($key)
	{
		return isset($this->dirty[$key]);
	}

	/* Protected */
	protected function has_one($class,$column)
	{
		return $this->data_or_relationship($class,$column,'id');
	}

	protected function has_many($class,$column)
	{
		return $this->data_or_relationship($class,$column,'id',true);
	}

	protected function belongs_to($class,$foreign_key)
	{
		return $this->data_or_relationship($class,'id',$foreign_key);
	}

	protected function data_or_relationship($class, $column, $key, $many = false)
	{
		if(is_array($this->$column) || is_object($this->$column)) return $this->$column;
		$result = $class::where($key,$this->$column);
		return $many? $result : $result->first();
	}

	/* Magic */
	public function __isset($prop) { return isset($this->attributes[$prop]); }
	public function __get($prop) { return isset($this->attributes[$prop])? $this->attributes[$prop] : null; }
	public function __set($prop,$value)
	{
		$this->attributes[$prop] = $value;
		$this->dirty[$prop] = $value;
	}
	public function __unset($prop) { unset($this->attributes[$prop]); }
}
