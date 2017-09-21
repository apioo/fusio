
# /backend/connection


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
totalResults | Integer |  | 
startIndex | Integer |  | 
entry | Array (Object ([connection](#psx_model_Connection))) |  | 

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


## POST


### POST Request

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


### POST Response - 201 Created

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 

