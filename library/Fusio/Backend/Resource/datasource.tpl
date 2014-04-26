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
					<li class="active">Datasource</li>
				</ol>
				<div id="notification"></div>
				<table class="table">
				<colgroup>
					<col width="*" />
					<col width="400" />
					<col width="200" />
				</colgroup>
				<thead>
				<tr>
					<th>Connection</th>
					<th>Parameter</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
				</tbody>
				</table>
				<button type="button" class="btn btn-primary" onclick="fusio.datasource.showModal()">Add datasource</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="datasource-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Modal title</h4>
			</div>
			<div class="modal-body">
				<form method="post" id="datasource-form" role="form">
					<div class="form-group">
						<label for="connection">Connection</label>
						<select name="connection" id="connection" class="form-control" onchange="fusio.datasource.loadParam()">
							<option value="">Localhost (Mysql)</option>
						</select>
					</div>
					<div id="datasource-param"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="fusio.datasource.save()">Save changes</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>
