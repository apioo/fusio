
# /consumer/scope


## GET


### GET Response - 200 OK

<div id="psx_model_Collection" class="psx-object"><h4>collection</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"totalResults"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Integer</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"startIndex"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Integer</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"entry"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Array (Object (<a href="#psx_model_Scope" title="RFC4648">scope</a>))</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">totalResults</span></td><td><span class="psx-property-type">Integer</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">startIndex</span></td><td><span class="psx-property-type">Integer</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">entry</span></td><td><span class="psx-property-type">Array (Object (<a href="#psx_model_Scope" title="RFC4648">scope</a>))</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>
<div id="psx_model_Scope" class="psx-object"><h4>scope</h4><pre class="psx-object-json"><span class="psx-object-json-pun">{</span>
  <span class="psx-object-json-key">"id"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">Integer</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"name"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
  <span class="psx-object-json-key">"description"</span><span class="psx-object-json-pun">: </span><span class="psx-property-type">String</span><span class="psx-object-json-pun">,</span>
<span class="psx-object-json-pun">}</span></pre><table class="table psx-object-properties"><colgroup><col width="30%" /><col width="70%" /></colgroup><thead><tr><th>Field</th><th>Description</th></tr></thead><tbody><tr><td><span class="psx-property-name psx-property-optional">id</span></td><td><span class="psx-property-type">Integer</span><br /><div class="psx-property-description"></div></td></tr><tr><td><span class="psx-property-name psx-property-optional">name</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div><dl class="psx-property-constraint"><dt>Pattern</dt><dd><span class="psx-constraint-pattern">[A-z0-9\-\_]{3,64}</span></dd></dl></td></tr><tr><td><span class="psx-property-name psx-property-optional">description</span></td><td><span class="psx-property-type">String</span><br /><div class="psx-property-description"></div></td></tr></tbody></table></div>
