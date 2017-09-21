
# /consumer/scope


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
totalResults | Integer |  | 
startIndex | Integer |  | 
entry | Array (Object ([scope](#psx_model_Scope))) |  | 

#### scope

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
name | String |  | Pattern: [A-z0-9\-\_]{3,64}
description | String |  | 

