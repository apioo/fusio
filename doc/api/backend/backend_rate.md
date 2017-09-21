
# /backend/rate


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
totalResults | Integer |  | 
startIndex | Integer |  | 
entry | Array (Object ([rate](#psx_model_Rate))) |  | 

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


## POST


### POST Request

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


### POST Response - 201 Created

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 

