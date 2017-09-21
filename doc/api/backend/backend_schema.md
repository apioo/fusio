
# /backend/schema


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
totalResults | Integer |  | 
startIndex | Integer |  | 
entry | Array (Object ([schema](#psx_model_Schema))) |  | 

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


## POST


### POST Request

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


### POST Response - 201 Created

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 

