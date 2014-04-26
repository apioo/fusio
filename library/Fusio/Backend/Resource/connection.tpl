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
					<li class="active">Connection</li>
				</ol>
				<div id="notification"></div>
				<table class="table">
				<colgroup>
					<col width="*" />
					<col width="200" />
				</colgroup>
				<thead>
				<tr>
					<th>Connection</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
				</tbody>
				</table>
				<button type="button" class="btn btn-primary" onclick="fusio.connection.showModal()">Add connection</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="connection-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Connection</h4>
			</div>
			<div class="modal-body">
				<form method="post" id="datasource-form" role="form">
					<div class="form-group">
						<label for="name">Name</label>
						<input type="text" name="name" id="name" class="form-control">
					</div>
					<div class="form-group">
						<label for="type">Type</label>
						<select name="type" id="type" class="form-control" onchange="fusio.connection.loadParam($(this).val())">
						</select>
					</div>
					<div id="connection-param"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="fusio.connection.save()">Save changes</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	fusio.connection.load();
});
</script>

</body>
</html>
