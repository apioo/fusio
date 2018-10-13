
## Connection

A connection enables Fusio to connect to other remote sources. This can be i.e. 
a database or message queue server. Please take a look at the [adapter](https://www.fusio-project.org/adapter)
to see a list of all available connections. It is also easy possible to develop
your own custom connection.

### Development

To develop a custom connection you need to create a class which implements the
interface `Fusio\Engine\ConnectionInterface`. Then you can add this class to the
`provider.php` file. Thus it is possible to select this connection at the backend.
