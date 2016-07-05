
## SQL-Builder

Through the SQL-Buider you can build a response based on multiple SQL queries.
You can use a specialised JSON format to specify the SQL queries and the 
response format.

### Elements

#### Collection

All elements which contain an `!collection` key are replaced with an array. The
array contains for each result an object in the format of the definition.

    {
        "!collection": "SELECT id, title, content, date FROM app_news ORDER BY id DESC",
        "definition": {
            "displayTitle": "title",
            "createDate": "date|datetime",
        }
    }

#### Entity

All elements which contain an `!entity` key are replaced with an object. The
object has the format of the definition.

    {
        "!entity": "SELECT id, name, date FROM app_author WHERE id = :authorId"
        "parameters": {"authorId": "$authorId"},
        "definition": {
            "displayName": "name",
            "insertDate": "date|datetime"
        }
    }

#### Value

All elements which contain an `!value` key are replaced with a single value.

    {
        "!value": "SELECT COUNT(*) FROM app_news"
    }

### Example

    {
        "totalResults": {
            "!value": "SELECT COUNT(*) FROM app_news"
        },
        "entry": {
            "!collection": "SELECT id, title, content, date FROM app_news ORDER BY id DESC",
            "definition": {
                "id": "id|integer",
                "title": "title",
                "date": "date|datetime"
            }
        }
    }



