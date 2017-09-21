
# /backend/action


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
totalResults | Integer |  | 
startIndex | Integer |  | 
entry | Array (Object ([action](#psx_model_Action))) |  | 

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


## POST


### POST Request

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


### POST Response - 201 Created

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 

