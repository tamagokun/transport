<?php
namespace Transport\Adaptor

class Mongo extends \Transport\Adaptor
{
	public $name = "mongodb";

	public function connect($dsn)
	{
		$connection = new \Mongo($dsn);
		$this->transport = $connection->selectDB($this->db);
		return $connection;
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
}
