
## Validator

Fusio provides the possibility to define custom validation rules. This option 
should be only considered if the JSON Schema constraints are not sufficient.
This is mostly the case if you need system dependent checks i.e. whether a 
database entry exist for an specific value. Fusio uses the Symfony 
ExpressionLanguage Component. Please visit the official [documentation] 
for detailed informations about the syntax.

### Syntax

The following list contains all available methods which can be used inside the 
condition.

 * `database`  
   * `rowExists(connectionId, table, column, value)`  
     Checks whether there is a row in the table where the column is equal to the 
     provided value
 * `filter`  
   * `strlen(value)`  
     Returns the string length of the value
   * `alnum(value)`  
     Checks if all of the characters in the provided string are alphanumeric
   * `alpha(value)`  
     Checks if all of the characters in the provided string are alphabetic
   * `digit(value)`  
     Checks if all of the characters in the provided string are numerical
   * `email(value)`  
     Validates whether the value is a valid e-mail address
   * `ip(value)`  
     Validates value as IP address
   * `url(value)`  
     Validates value as URL
   * `xdigit(value)`  
     Checks if all of the characters in the provided string are hexadecimal 'digits'
 * `value`  
   Contains the value of the reference

### Examples

Note while the condition is a powerful action you should consider to write a
custom action if there are to many specific cases. In the following a few 
examples:

* Check whether the provided value is a valid user id

        database.rowExists('Native-Connection', 'fusio_user', 'id', value)

* The provided value is an email

        filter.email(value)

[documentation]: http://symfony.com/doc/current/components/expression_language/introduction.html