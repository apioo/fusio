
# /backend/connection/$connection_id<[0-9]+>


## GET


### GET Response - 200 OK

#### connection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
class | String |  | 
config | Object ([config](#psx_model_Config)) |  | 

#### config

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
* | Object ([config](#psx_model_Config)) |  | 


## PUT


### PUT Request

#### connection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
class | String |  | 
config | Object ([config](#psx_model_Config)) |  | 

#### config

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
* | Object ([config](#psx_model_Config)) |  | 


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

