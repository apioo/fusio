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
					<li class="active">Home</li>
				</ol>
				<div class="row">
					<div class="col-md-6">
						<h4>App usage</h4>
						<p>Top 10 apps and request count for the last month</p>
					</div>
					<div class="col-md-6">
						<h4>Overall usage</h4>
						<p>All incommidng requests for the last month</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<h4>User</h4>
						<p>Top 10 new users</p>
					</div>
					<div class="col-md-6">
						<h4>Log</h4>
						<p>Top 10 new log entries</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</body>
</html>
