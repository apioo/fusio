
# /consumer/app/developer


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
status | Integer |  | 
name | String |  | Pattern: [A-z0-9\-\_]{3,64}
url | String |  | 
appKey | String |  | 
appSecret | String |  | 
date | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 
scopes | Array (String) |  | 


## POST


### POST Request

#### app

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
status | Integer |  | 
name | String |  | Pattern: [A-z0-9\-\_]{3,64}
url | String |  | 
appKey | String |  | 
appSecret | String |  | 
date | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 
scopes | Array (String) |  | 


### POST Response - 201 Created

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 

