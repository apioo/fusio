
# /backend/log/$log_id<[0-9]+>


## GET


### GET Response - 200 OK

#### log

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
ip | String |  | 
userAgent | String |  | 
method | String |  | 
path | String |  | 
header | String |  | 
body | String |  | 
date | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 
errors | Array (Object ([error](#psx_model_Error))) |  | 

#### error

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
message | String |  | 
trace | String |  | 
file | String |  | 
line | String |  | 

