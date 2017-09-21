
# /consumer/app/developer/$app_id<[0-9]+>


## GET


### GET Response - 200 OK

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


## PUT


### PUT Request

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


### PUT Response - 200 OK

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 


## DELETE


### DELETE Response - 200 OK

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 

