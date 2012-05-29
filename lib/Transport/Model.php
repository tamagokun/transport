<?php
namespace Transport;

abstract class Model
{
	protected function has_one($class,$column)
	{
		return $class::find($this->$column);
	}

	protected function has_many($class,$column)
	{
		return $class::where('id',$this->$column);
	}

	protected function belongs_to($class,$foreign_key)
	{
		return $class::where($foreign_key,$this->id())->first();
	}
}
