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
					<li class="active">Log</li>
				</ol>
				<table class="table">
				<colgroup>
					<col width="100" />
					<col width="200" />
					<col width="*" />
				</colgroup>
				<thead>
				<tr>
					<th>Level</th>
					<th>App</th>
					<th>Message</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

</body>
</html>
