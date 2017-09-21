
# /backend/scope/$scope_id<[0-9]+>


## GET


### GET Response - 200 OK

#### scope

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
description | String |  | 
routes | Array (Object ([route](#psx_model_Route))) |  | 

#### route

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
routeId | Integer |  | 
allow | Boolean |  | 
methods | String |  | 


## PUT


### PUT Request

#### scope

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
description | String |  | 
routes | Array (Object ([route](#psx_model_Route))) |  | 

#### route

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
routeId | Integer |  | 
allow | Boolean |  | 
methods | String |  | 


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

