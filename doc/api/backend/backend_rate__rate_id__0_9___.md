
# /backend/rate/$rate_id<[0-9]+>


## GET


### GET Response - 200 OK

#### rate

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
priority | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
rateLimit | Integer |  | 
timespan | [Duration](https://en.wikipedia.org/wiki/ISO_8601#Durations) |  | 
allocation | Array (Object ([allocation](#psx_model_Allocation))) |  | 

#### allocation

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
routeId | Integer |  | 
appId | Integer |  | 
authenticated | Boolean |  | 
parameters | String |  | 


## PUT


### PUT Request

#### rate

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
priority | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
rateLimit | Integer |  | 
timespan | [Duration](https://en.wikipedia.org/wiki/ISO_8601#Durations) |  | 
allocation | Array (Object ([allocation](#psx_model_Allocation))) |  | 

#### allocation

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
routeId | Integer |  | 
appId | Integer |  | 
authenticated | Boolean |  | 
parameters | String |  | 


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

