
# /backend/log/error


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
totalResults | Integer |  | 
startIndex | Integer |  | 
entry | Array (Object ([error](#psx_model_Error))) |  | 

#### error

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
message | String |  | 
trace | String |  | 
file | String |  | 
line | String |  | 

