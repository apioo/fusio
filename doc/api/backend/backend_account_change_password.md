
# /backend/account/change_password


## PUT


### PUT Request

<div id="psx_model_Credentials" class="psx-object"><h4>credentials</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"oldPassword"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"newPassword"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"verifyPassword"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-required">oldPassword</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div><dl class="psx-property-constraint"><dt>MinLength</dt><dd><span class="psx-constraint-minlength">8</span></dd><dt>MaxLength</dt><dd><span class="psx-constraint-maxlength">128</span></dd></dl></td></tr><tr><td><span class="psx-property-name psx-property-required">newPassword</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div><dl class="psx-property-constraint"><dt>MinLength</dt><dd><span class="psx-constraint-minlength">8</span></dd><dt>MaxLength</dt><dd><span class="psx-constraint-maxlength">128</span></dd></dl></td></tr><tr><td><span class="psx-property-name psx-property-required">verifyPassword</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div><dl class="psx-property-constraint"><dt>MinLength</dt><dd><span class="psx-constraint-minlength">8</span></dd><dt>MaxLength</dt><dd><span class="psx-constraint-maxlength">128</span></dd></dl></td></tr></tbody></table></div>

### PUT Response - 200 OK

<div id="psx_model_Message" class="psx-object"><h4>message</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"success"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Boolean</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"message"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">success</span></td><td><span class="psx-property-type">Boolean</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">message</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>
