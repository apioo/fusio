
# /backend/action/execute/$action_id<[0-9]+>


## POST


### POST Request

#### request

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
method | String |  | Pattern: GET|POST|PUT|DELETE
uriFragments | String |  | 
parameters | String |  | 
headers | String |  | 
body | Object ([body](#psx_model_Body)) |  | 

#### body

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
* | Mixed |  | 


### POST Response - 200 OK

#### response

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
statusCode | Integer |  | 
headers | Object ([headers](#psx_model_Headers)) |  | 
body | Object ([body](#psx_model_Body)) |  | 

#### headers

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
* | Object ([headers](#psx_model_Headers)) |  | 

#### body

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
* | Mixed |  | 

