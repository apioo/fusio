
## Processor

A process simplifies calling multiple actions. All available action classes
and parameters can be obtained through the <code>action:list</code> and 
<code>action:detail</code> console command. In the following an example process 
definition:

    Fusio\Impl\Action\Validator:
        "id": validator
        "rules": |
            /title: filter.alnum(value)
        "action": transform

    Fusio\Impl\Action\Transform:
        "id": transform
        "patch": |
            [
                { "op": "add", "path": "/foo", "value": "bar" }
            ]
        "action": title-check

    Fusio\Impl\Action\Condition:
        "id": title-check
        "condition": body.get("/title") == "foo" && body.get("/foo") == "bar"
        "true": select-all
        "false": fault-response

    Fusio\Impl\Action\StaticResponse:
        "id": fault-response
        "statusCode": 500,
        "response": |
            {"error": "foo"}

    Fusio\Impl\Action\SqlFetchRow:
        "id": select-all
        "connection": 1
        "sql": |
            SELECT id,
                   name
              FROM app_news 
             WHERE id = {{ body.get("/news_id")|prepare }}
