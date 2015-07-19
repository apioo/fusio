
## Connection

A connection enables fusio to connect to other remote sources. This can be i.e. 
a database or message queue server. It is also possible to provide a custom 
implementation.

### Custom implementation

In order to provide a custom implementation the class name of the connection 
must be added to the key `fusio_connection` in the `configuration.php` file. The 
class must be autoloadable by composer therefor you must add an autoload rule in 
the `composer.json` file. Please visit the offical [autoload] documentation for 
more informations.

[autoload]: https://getcomposer.org/doc/04-schema.md#autoload

