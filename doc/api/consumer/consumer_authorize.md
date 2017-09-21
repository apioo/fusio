
# /consumer/authorize


## POST


### POST Request

#### request

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
responseType | String |  | 
clientId | String |  | 
redirectUri | String |  | 
scope | String |  | 
state | String |  | 
allow | Boolean |  | 


### POST Response - 200 OK

#### response

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
type | String |  | 
token | Object ([token](#psx_model_Token)) |  | 
code | String |  | 
redirectUri | String |  | 

#### token

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
access_token | String |  | 
token_type | String |  | 
expires_in | String |  | 
scope | String |  | 

