<?php
namespace Transport;

class Store
{
	public $db;
	protected $connection, $adaptor;

	public function __construct()
	{
	  
	}

	public function config($dsn)
	{
		$conn = parse_url($dsn);
		$this->db = basename($conn["path"]);
		$this->adaptor = Adaptor::find($conn["scheme"]);
	}

	public function connect()
	{
		$this->connection = $this->adaptor->connect();
	}

	public function connected()
	{
		return isset($this->connection) && $this->connection;
	}

	public function name()
	{
		return $this->adaptor->name;
	}
	
	public function transport()
	{
		return $this->adaptor->transport;
	}
}
