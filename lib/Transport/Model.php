<?php
namespace Transport;

abstract class Model
{
	const collection = "";

	public $id;
	protected $attributes = array();
	protected $dirty = array();

	/* Static */
	public static function all()
	{
		$class = get_called_class();
		return $class::build_query(func_get_args());
	}

	public static function any_in($prop,$values=array())
	{
		$class = get_called_class();
		return $class::where($prop,array('in'=>$values));
	}

	public static function find($id)
	{
		$class = get_called_class();
		return is_array($id)? $class::any_in('id',$id) : $class::where('id=?',$id)->first();
	}

	public static function first() { return static::all(func_get_args())->first(); }
	public static function last() { return static::all(func_get_args())->last(); }

	public static function where($prop,$value=null)
	{
		$class = get_called_class();
		$params = array();
		if(is_array($prop))
		{
			foreach($prop as $attribute=>$value)
			  $params[] = transport()->where($prop,$value);
		}else $params = transport()->where($prop,$value);
		return $class::query($params);
	}

	public static function query($params = array())
	{
		return new ModelCollection(get_called_class(),$params);
	}

	protected static function build_query($args = array())
	{
		$class = get_called_class();
		if(count($args) == 2) return $class::where($args[0],$args[1]);
		if(count($args) == 1 && !empty($args[0])) return $class::where($args[0][0],$args[0][1]);
		return $class::query();
	}

	/* Public */
	public function __construct($attrs = array())
	{
		if($attrs) foreach($attrs as $key=>$value) $this->attributes[$key] = $value;
		$this->id = $this->id();
	}

	public function attr_properties()
	{
		return $this->attributes;
	}

	public function collection()
	{
		if(!empty(static::collection)) return static::collection;
		return strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', get_class($this)));
	}

	public function id()
	{
		$id = transport()->id_attribute;
		$value = isset($this->attributes['id'])? $this->attributes['id'] : null;
		if(isset($this->attributes[$id])) $valie = $this->attributes[$id];
		return !is_string($value) && !is_null($value)? $value->__toString() : $value;
	}

	public function is_dirty($key)
	{
		return isset($this->dirty[$key]);
	}

	public function properties()
	{
		return array();
	}

	public function save()
	{
		if(!$this->before_save()) return false;
		if($this->id())
		  if(!$this->update()) return false;
		else if(!$this->insert()) return false;
		if(!$this->after_save()) return false;
		return true;
	}

	public function destroy()
	{
		if(!$this->before_destroy()) return false;
		if(!transport()->destroy($this)) return false;
		if(!$this->after_destroy())
		{
			$this->save();
			return false;
		}
		return true;
	}

	public function validate()
	{
		if(!$this->before_validate()) return false;
		if(!$this->validation()) return false;
		if(!$this->after_validate()) return false;
		return true;
	}

	protected function before_save() { return true; }
	protected function before_insert() { return true; }
	protected function before_update() { return true; }
	protected function before_destroy() { return true; }
	protected function before_validate() { return true; }
	protected function after_save() { return true; }
	protected function after_insert() { return true; }
	protected function after_update() { return true; }
	protected function after_destroy() { return true; }
	protected function after_validate() { return true; }

	protected function update()
	{
		if(!$this->before_update()) return false;
		if(!transport()->update($this)) return false;
		if(!$this->after_update())
		{
			//TODO : rollback
			return false;
		}
		return true;
	}

	protected function insert()
	{
		if(!$this->before_insert()) return false;
		if(!transport()->insert($this)) return false;
		if(!$this->after_insert())
		{
			$this->destroy();
			return false;
		}
		return true;
	}

	protected function validation()
	{
		return true;
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
