
## Validator

A validator validates whether the incoming data is valid and throws an error
if this is not the case. With this action you can define a list of constraints
for specific data fields. Note this action should be only considered if the JSON 
Schema constraints are not sufficient because these checks are not available
in the API documentation. This is mostly needed to check i.e. whether a database 
entry exist for a specific value. Fusio uses the Symfony ExpressionLanguage 
Component. Please visit the official [documentation] for detailed informations 
about the syntax.

### Syntax

The validation rules are descibed in a simple YAML key value format. The key
is the [JSON pointer] to the element and the value contains the validation rule.

    /id: database.rowExists('Native-Connection', 'fusio_user', 'id', value)
    /title:
        rule: filter.alnum(value)
        message: %s contains a custom error message

<br>
The `/id` filter checks whether the given user id exists. The `/title` rule
contains a simple alphanumeric filter with a custom error message.

The following list contains all available methods which can be used inside the 
rule.

 * `database`  
   * `rowExists(connectionId, table, column, value)`  
     Checks whether there is a row in the table where the column is equal to the 
     provided value
 * `filter`  
   * `length(value)`  
     Returns the string length of the value
   * `match(pattern, value)`  
     Returns whether the value matches the provided regular expression
   * `inArray(value, array)`  
     Returns whether the value is in the array
   * `alnum(value)`  
     Checks if all of the characters in the provided string are alphanumeric
   * `alpha(value)`  
     Checks if all of the characters in the provided string are alphabetic
   * `digit(value)`  
     Checks if all of the characters in the provided string are numerical
   * `xdigit(value)`  
     Checks if all of the characters in the provided string are hexadecimal digits
   * `email(value)`  
     Validates whether the value is a valid e-mail address
   * `ip(value)`  
     Validates value as IP address
   * `url(value)`  
     Validates value as URL
 * `value`  
   Contains the value of the reference

[documentation]: http://symfony.com/doc/current/components/expression_language/introduction.html
[JSON pointer]: https://tools.ietf.org/html/rfc6901