
# /backend/action/$action_id<[0-9]+>


## GET


### GET Response - 200 OK

#### action

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
status | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
class | String |  | 
engine | String |  | 
config | Object ([config](#psx_model_Config)) |  | 

#### config

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
* | Object ([config](#psx_model_Config)) |  | 


## PUT


### PUT Request

#### action

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
status | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
class | String |  | 
engine | String |  | 
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

