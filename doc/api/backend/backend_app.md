
# /backend/app


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
totalResults | Integer |  | 
startIndex | Integer |  | 
entry | Array (Object ([app](#psx_model_App))) |  | 

#### app

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
userId | Integer |  | 
status | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
url | String |  | 
parameters | String |  | 
appKey | String |  | 
appSecret | String |  | 
date | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 
scopes | Array (String) |  | 
tokens | Array (Object ([token](#psx_model_Token))) |  | 

#### token

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
token | String |  | 
scope | String |  | 
ip | String |  | 
expire | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 
date | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 


## POST


### POST Request

#### app

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
userId | Integer |  | 
status | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
url | String |  | 
parameters | String |  | 
appKey | String |  | 
appSecret | String |  | 
date | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 
scopes | Array (String) |  | 
tokens | Array (Object ([token](#psx_model_Token))) |  | 

#### token

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
token | String |  | 
scope | String |  | 
ip | String |  | 
expire | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 
date | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 


### POST Response - 201 Created

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 

