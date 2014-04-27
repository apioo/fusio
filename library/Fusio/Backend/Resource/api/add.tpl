<!DOCTYPE html>
<html>
<head>
	<?php include($location . '/inc/meta.tpl'); ?>
</head>
<body>

<?php include($location . '/inc/header.tpl'); ?>

<div class="main">
	<div id="sidebar">
		<?php include($location . '/inc/nav.tpl'); ?>
	</div>
	<div id="content">
		<div class="wrapper">
			<div class="container-fluid">
				<ol class="breadcrumb">
					<li><a href="<?php echo $url; ?>backend">Backend</a></li>
					<li><a href="<?php echo $url; ?>backend/api">Api</a></li>
					<li class="active">Add</li>
				</ol>

				<form role="form">
					<div class="form-group">
						<label for="path">Path</label>
						<input type="text" class="form-control" name="path" id="path" placeholder="/acme/store">
					</div>
					<div class="form-group">
						<label for="description">Description</label>
						<input type="text" class="form-control" name="description" id="description" placeholder="A short description about the API">
					</div>

					<div class="alert alert-info" id="">Please assign at least one action to the API endpoint.</div>

					<fieldset class="fusio-api-add-fieldset">
						<legend>Actions</legend>
						<table class="table" id="action-table">
						<colgroup>
							<col width="100" />
							<col width="*" />
							<col width="200" />
						</colgroup>
						<thead>
						<tr>
							<th>Method</th>
							<th>Action</th>
							<th>Options</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>GET</td>
							<td>Select fields</td>
							<td>
								<a href="#" onclick="fusio.connection.edit(' + resp.entry[i].id + ',this);return false;" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a>
								<a href="#" onclick="fusio.connection.remove(' + resp.entry[i].id + ',this);return false;" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></a>
							</td>
						</tr>
						</tbody>
						</table>
					</fieldset>
					<fieldset class="fusio-api-add-fieldset">
						<legend>Limit</legend>
						<table class="table" id="trigger-table">
						<colgroup>
							<col width="100" />
							<col width="*" />
							<col width="200" />
							<col width="200" />
						</colgroup>
						<thead>
						<tr>
							<th>Method</th>
							<th>Interval</th>
							<th>Count</th>
							<th>Options</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>GET</td>
							<td>1 Day</td>
							<td>10</td>
							<td>
								<a href="#" onclick="fusio.connection.edit(' + resp.entry[i].id + ',this);return false;" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a>
								<a href="#" onclick="fusio.connection.remove(' + resp.entry[i].id + ',this);return false;" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></a>
							</td>
						</tr>
						</tbody>
						</table>
					</fieldset>
					<fieldset class="fusio-api-add-fieldset">
						<legend>Trigger</legend>
						<table class="table" id="trigger-table">
						<colgroup>
							<col width="100" />
							<col width="*" />
							<col width="200" />
						</colgroup>
						<thead>
						<tr>
							<th>Method</th>
							<th>Trigger</th>
							<th>Options</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>GET</td>
							<td>Log request</td>
							<td>
								<a href="#" onclick="fusio.connection.edit(' + resp.entry[i].id + ',this);return false;" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a>
								<a href="#" onclick="fusio.connection.remove(' + resp.entry[i].id + ',this);return false;" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></a>
							</td>
						</tr>
						</tbody>
						</table>
					</fieldset>
					<hr />
					<button type="button" class="btn btn-primary" onclick="fusio.action.showModal()">Add action</button>
					<button type="button" class="btn btn-primary" onclick="fusio.limit.showModal()">Add limit</button>
					<button type="button" class="btn btn-primary" onclick="fusio.trigger.showModal()">Add trigger</button>
					<button type="button" class="btn btn-primary" onclick="fusio.api.add()" disabled="disabled">Create api</button>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="action-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Action</h4>
			</div>
			<div class="modal-body">
				<form method="post" id="action-form" role="form">
					<div class="form-group">
						<label for="method">Method</label>
						<select name="method" id="method" class="form-control">
							<option value="GET">GET</option>
							<option value="POST">POST</option>
							<option value="PUT">PUT</option>
							<option value="DELETE">DELETE</option>
						</select>
					</div>
					<div class="form-group">
						<label for="action">Action</label>
						<select name="action" id="action" class="form-control" onchange="fusio.action.loadParam($(this).val())">
						</select>
					</div>
					<div id="action-param"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="fusio.action.save()">Add action</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>
