
# /backend/audit


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
totalResults | Integer |  | 
startIndex | Integer |  | 
entry | Array (Object ([audit](#psx_model_Audit))) |  | 

#### audit

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
app | Object ([app](#psx_model_App)) |  | 
user | Object ([user](#psx_model_User)) |  | 
event | String |  | 
ip | String |  | 
message | String |  | 
content | Object ([object](#psx_model_Object)) | A key value object containing the changes | 
date | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 

#### app

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
status | Integer |  | 
name | String |  | 

#### user

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
status | Integer |  | 
name | String |  | 

