
# /consumer/app/grant


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
entry | Array (Object ([grant](#psx_model_Grant))) |  | 

#### grant

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
app | Object ([app](#psx_model_App)) |  | 
createDate | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 

#### app

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
userId | Integer |  | 
status | Integer |  | 
name | String |  | Pattern: [A-z0-9\-\_]{3,64}
url | String |  | 
appKey | String |  | 
appSecret | String |  | 
date | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 
scopes | Array (String) |  | 

