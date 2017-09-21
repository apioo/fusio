
# /backend/scope


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
totalResults | Integer |  | 
startIndex | Integer |  | 
entry | Array (Object ([scope](#psx_model_Scope))) |  | 

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


## POST


### POST Request

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


### POST Response - 201 Created

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 

