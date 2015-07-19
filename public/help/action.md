
## Action

The action receives the request and produces an response. Fusio contains actions 
to execute i.e. database operations or push data to a message queue. It is also 
possible to provide a custom implementation.

### Custom implementation

In order to provide a custom implementation the class name of the action must be
added to the key `fusio_action` in the `configuration.php` file. The class 
must be autoloadable by composer therefor you must add an autoload rule in the 
`composer.json` file. Please visit the offical [autoload] documentation for more 
informations.

[autoload]: https://getcomposer.org/doc/04-schema.md#autoload

