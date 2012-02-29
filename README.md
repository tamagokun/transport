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