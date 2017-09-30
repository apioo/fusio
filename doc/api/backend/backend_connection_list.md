
# /backend/connection/list


## GET


### GET Response - 200 OK

<div id="psx_model_Index" class="psx-object"><h4>index</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"connections"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Array (Object (<a href="#psx_model_Connection" title="RFC4648">connection</a>))</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">connections</span></td><td><span class="psx-property-type">Array (Object (<a href="#psx_model_Connection" title="RFC4648">connection</a>))</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>
<div id="psx_model_Connection" class="psx-object"><h4>connection</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"name"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"class"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">name</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">class</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>
