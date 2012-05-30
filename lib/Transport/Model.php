<?php
namespace Transport;

abstract class Model
{
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
}
