
# /consumer/app/meta


## GET


### GET Response - 200 OK

#### app

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
name | String |  | 
url | String |  | 
scopes | Array (Object ([scope](#psx_model_Scope))) |  | 

#### scope

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
name | String |  | Pattern: [A-z0-9\-\_]{3,64}
description | String |  | 

