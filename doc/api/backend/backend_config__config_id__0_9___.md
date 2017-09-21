
# /backend/config/$config_id<[0-9]+>


## GET


### GET Response - 200 OK

#### config

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
type | Integer |  | 
name | String |  | 
description | String |  | 
value | Mixed |  | 


## PUT


### PUT Request

#### config

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
type | Integer |  | 
name | String |  | 
description | String |  | 
value | Mixed |  | 


### PUT Response - 200 OK

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 

