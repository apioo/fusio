<!DOCTYPE html>
<html>
<head>
	<?php include($location . '/inc/meta.tpl'); ?>
</head>
<body>

<?php include($location . '/inc/nav.tpl'); ?>

<div class="container">
	<div class="row">
		<div class="col-md-8">
			<h3>Welcome,</h3>
			<p>Register content foobar</p>
		</div>
		<div class="col-md-4">
			<h3>Register</h3>

			<form role="form">
				<div class="form-group">
					<label for="exampleInputEmail1">Email address</label>
					<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Password</label>
					<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Password repeat</label>
					<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
				</div>
				<button type="submit" class="btn btn-primary">Register</button>
			</form>
		</div>
		<p>&nbsp;</p>
	</div>
</div>

<?php include($location . '/inc/footer.tpl'); ?>

</body>
</html>
