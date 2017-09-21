
# /backend/schema/$schema_id<[0-9]+>


## GET


### GET Response - 200 OK

#### schema

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
status | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
source | Object ([source](#psx_model_Source)) |  | 

#### source

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
* | Mixed |  | 


## PUT


### PUT Request

#### schema

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
status | Integer |  | 
name | String |  | Pattern: [a-zA-Z0-9\-\_]{3,64}
source | Object ([source](#psx_model_Source)) |  | 

#### source

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
* | Mixed |  | 


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

