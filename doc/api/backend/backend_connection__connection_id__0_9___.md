
# /backend/connection/$connection_id<[0-9]+>


## GET


### GET Response - 200 OK

<div id="psx_model_Connection" class="psx-object"><h4>connection</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"id"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Integer</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"name"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"class"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"config"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Object (<a href="#psx_model_Config" title="RFC4648">config</a>)</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">id</span></td><td><span class="psx-property-type">Integer</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">name</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div><dl class="psx-property-constraint"><dt>Pattern</dt><dd><span class="psx-constraint-pattern">[a-zA-Z0-9\-\_]{3,64}</span></dd></dl></td></tr><tr><td><span class="psx-property-name psx-property-optional">class</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">config</span></td><td><span class="psx-property-type">Object (<a href="#psx_model_Config" title="RFC4648">config</a>)</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>
<div id="psx_model_Config" class="psx-object"><h4>config</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"*"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Object (<a href="#psx_model_Config" title="RFC4648">config</a>)</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">*</span></td><td><span class="psx-property-type">Object (<a href="#psx_model_Config" title="RFC4648">config</a>)</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>

## PUT


### PUT Request

<div id="psx_model_Connection" class="psx-object"><h4>connection</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"id"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Integer</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"name"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"class"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"config"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Object (<a href="#psx_model_Config" title="RFC4648">config</a>)</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">id</span></td><td><span class="psx-property-type">Integer</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">name</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div><dl class="psx-property-constraint"><dt>Pattern</dt><dd><span class="psx-constraint-pattern">[a-zA-Z0-9\-\_]{3,64}</span></dd></dl></td></tr><tr><td><span class="psx-property-name psx-property-optional">class</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">config</span></td><td><span class="psx-property-type">Object (<a href="#psx_model_Config" title="RFC4648">config</a>)</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>
<div id="psx_model_Config" class="psx-object"><h4>config</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"*"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Object (<a href="#psx_model_Config" title="RFC4648">config</a>)</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">*</span></td><td><span class="psx-property-type">Object (<a href="#psx_model_Config" title="RFC4648">config</a>)</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>

### PUT Response - 200 OK

<div id="psx_model_Message" class="psx-object"><h4>message</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"success"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Boolean</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"message"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">success</span></td><td><span class="psx-property-type">Boolean</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">message</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>

## DELETE


### DELETE Response - 200 OK

<div id="psx_model_Message" class="psx-object"><h4>message</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"success"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Boolean</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"message"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">success</span></td><td><span class="psx-property-type">Boolean</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">message</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>
