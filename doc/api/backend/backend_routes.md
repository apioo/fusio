
# /backend/routes


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
totalResults | Integer |  | 
startIndex | Integer |  | 
entry | Array (Object ([routes](#psx_model_Routes))) |  | 

#### routes

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
path | String |  | 
config | Array (Object ([version](#psx_model_Version))) |  | 

#### version

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
version | Integer |  | 
status | Integer |  | 
scopes | Array (String) |  | 
methods | Object ([methods](#psx_model_Methods)) |  | 

#### methods

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
^(GET|POST|PUT|DELETE)$ | Object ([method](#psx_model_Method)) |  | 

#### method

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
method | String |  | 
version | Integer |  | 
status | Integer |  | 
active | Boolean |  | 
public | Boolean |  | 
parameters | Integer |  | 
request | Integer |  | 
response | Integer |  | 
responses | Object ([responses](#psx_model_Responses)) |  | 
action | Integer |  | 

#### responses

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
^([0-9]{3})$ | Integer |  | 


## POST


### POST Request

#### routes

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
path | String |  | 
config | Array (Object ([version](#psx_model_Version))) |  | 

#### version

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
version | Integer |  | 
status | Integer |  | 
scopes | Array (String) |  | 
methods | Object ([methods](#psx_model_Methods)) |  | 

#### methods

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
^(GET|POST|PUT|DELETE)$ | Object ([method](#psx_model_Method)) |  | 

#### method

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
method | String |  | 
version | Integer |  | 
status | Integer |  | 
active | Boolean |  | 
public | Boolean |  | 
parameters | Integer |  | 
request | Integer |  | 
response | Integer |  | 
responses | Object ([responses](#psx_model_Responses)) |  | 
action | Integer |  | 

#### responses

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
^([0-9]{3})$ | Integer |  | 


### POST Response - 201 Created

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 

