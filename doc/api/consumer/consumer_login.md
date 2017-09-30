
# /consumer/login


## POST


### POST Request

<div id="psx_model_User" class="psx-object"><h4>user</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"username"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"password"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"scopes"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Array (String)</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">username</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">password</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">scopes</span></td><td><span class="psx-property-type">Array (String)</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>

### POST Response - 200 OK

<div id="psx_model_Jwt" class="psx-object"><h4>jwt</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"token"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">token</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>
