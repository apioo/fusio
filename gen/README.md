
# Model Generation

Fusio is tightly integrated with [TypeSchema](https://typeschema.org/). It helps to
generate models based on a TypeSchema definition. Through this your API can be completely
type safe, that means your action receives a PHP object which contains the JSON payload 
and your action can also use these PHP objects as response. For more examples you can
also take a look at our [internal definitions](https://github.com/apioo/fusio-impl/tree/master/resources)
which are used by Fusio.

To generate the PHP classes for the types defined at `typeschema.json` file simply run
the following command:
 
```
php gen_model.php
```
