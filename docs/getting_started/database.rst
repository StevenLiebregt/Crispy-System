Database
========

Connection
----------

The *Connection* class returns a PDO object, in order for this to work you need to have a *database.php* configuration file in *ROOT/config*. This file should look something like this:

.. code-block:: php

    <?php

    return [
        'driver' => 'mysql',
        'host' => '172.17.0.2',
        'port' => '3306',
        'dbname' => 'databasename',
        'user' => 'root',
        'pass' => 'secret',
    ];

To retrieve a PDO object from the class, use the *Config::getPdo* method.

Models
------

With this framework included is a base model (*StevenLiebregt\\CrispySystem\\Database\\Model*) that contains some basic queries.

When extending this class, you will need to override 2 properties, those are:

 * $table, the name of the table as in the database
 * $fields, an array containing all the field names in the database table

The queries included in the base class are:

**showTables**

Runs a *SHOW TABLES* query on the database and return the result.

**getAll**

Runs a *SELECT* query to retrieve all records from a table, and return the result.

**getOneById**

Runs a *SELECT* query to retrieve one record by id from a table, and return the result.

**insert**

Run an *INSERT* query, the method takes one parameter which should be an array of a list of the values to insert. This array should contain the values for all fields except the *id* field, as that is expected to be an *AUTO_INCREMENT* field.

This method returns the id of the newly inserted record.

**updateById**

Update one or more records in the database table. Takes two arguments:

 * An id, or an array of ids, these are the ids of the records to be updated
 * An associative array with all the values to be updated, as where the key should be the name of the field and the value the new value of the field

This method returns the amount of rows affected by the query.

**deleteById**

Delete one or more records from a database table. It takes one parameter, which can be an id or an array of ids.

This method returns the amount of rows affected by the query.