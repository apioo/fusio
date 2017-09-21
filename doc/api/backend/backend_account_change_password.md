
# /backend/account/change_password


## PUT


### PUT Request

#### credentials

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
oldPassword | String |  | MinLength: 8, MaxLength: 128
newPassword | String |  | MinLength: 8, MaxLength: 128
verifyPassword | String |  | MinLength: 8, MaxLength: 128


### PUT Response - 200 OK

#### message

Field | Type | Description | Constraints
----- | ---- | ----------- | -----------
success | Boolean |  | 
message | String |  | 

