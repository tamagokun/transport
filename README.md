# Transport

A data store designed to allow the use of any data technology:

 - File system
 - SQL
 - MongoDB

## How it works

Have your data models extend `Model`:

```php
<?php
	class Person extends \Transport\Model
	{

	}
```

Configure your connection:

```php
<?php
$store = new \Transport\Store();
$store->config("mongodb://localhost/my_db");
```

## API

 - `::find($query=null)` __aliases:__ _where_ - Find and return a set of records based on a query.
 
 - `::find_by_$prop($query=null)` - Directly query a proprty.
 
 - `->filter($query=null)` - __aliases:__ _find()_ _where()_ - Filter a result.
 
 - `::first($query=null)` - Run a query and return the first element.
 
 - `::last($query=null)` - Run a query and return the last element.
 
 - `->first()` - Return the first element of a data cursor.
 
 - `->last()` - Return the last element of a data cursor.
 
 - `->sort()` __aliases:__ _order()_ - Sort the elements of a data cursor.
 
 - `->sort_by_$prop()` __aliases:__ _order_by\_$prop()_ - Sort the elements of a data cursor directly by a property.
 
 - `->limit()` - Only allow a set number of results.
 
 - `->destroy()` - Destroy an instance of a record.
 
 - `::new($attributes = array())` - Create a record.
 
 - `->update_attributes($attributes=array())` - Update an existing record's attributes.
 
 - `->save()` - Save a record. 
 
 
## Hooks

- `create` before/after
- `save` before/after
- `update` before/after
- `destroy` before/after
 