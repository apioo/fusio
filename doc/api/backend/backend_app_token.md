
# /backend/app/token


## GET


### GET Response - 200 OK

#### collection

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
totalResults | Integer |  | 
startIndex | Integer |  | 
entry | Array (Object ([token](#psx_model_Token))) |  | 

#### token

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
id | Integer |  | 
token | String |  | 
scope | String |  | 
ip | String |  | 
expire | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 
date | [DateTime](http://tools.ietf.org/html/rfc3339#section-5.6) |  | 

