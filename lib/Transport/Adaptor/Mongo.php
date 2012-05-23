<?php
namespace Transport\Adaptor

class Mongo extends \Transport\Adaptor
{
	public $name = "mongodb";

	public function connect($dsn)
	{
		$connection = new \Mongo($dsn);
		$this->transport = new MongoTransport($connection->selectDB($this->db));
		return $connection;
	}
}
