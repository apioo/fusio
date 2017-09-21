
## Schema

The schema defines the format of the request and response data. It uses the 
[JsonSchema] format. Inside a schema it is possible to refer to other schema 
definitions by using the <code>$ref</code> key and the <code>schema</code> 
protocol i.e. <code>schema:///[schema-name]</code>. More detailed information
about the json schema format at the [RFC].

### Example

    {
        "id": "http://acme.com/schema",
        "type": "object",
        "title": "schema",
        "properties": {
            "name": {
                "type": "string"
            },
            "author": {
                "$ref": "schema:///author"
            },
            "date": {
                "type": "string",
                "format": "date-time"
            }
        }
    }


[JsonSchema]: http://json-schema.org/
[RFC]: http://tools.ietf.org/html/draft-zyp-json-schema-04
