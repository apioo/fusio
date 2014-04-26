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
					<li class="active">Model</li>
				</ol>
				<table class="table">
				<colgroup>
					<col width="*" />
					<col width="200" />
				</colgroup>
				<thead>
				<tr>
					<th>Name</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td></td>
					<td></td>
				</tr>
				</tbody>
				</table>
				<button type="button" class="btn btn-primary" onclick="fusio.user.add()">Add user</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>
