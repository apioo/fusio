<!DOCTYPE html>
<html>
<head>
	<?php include($location . '/inc/meta.tpl'); ?>
</head>
<body>

<?php include($location . '/inc/nav.tpl'); ?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h3>Welcome,</h3>
			<p>Some content wich can be set through the admin panel.</p>
			<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
		</div>
	</div>

	<div class="row fusio-highlights">
		<div class="col-sm-6 col-md-4">
			<div class="thumbnail text-center">
				<img src="<?php echo $base; ?>/img/settings-128.png" />
				<div class="caption">
					<h3>Documentation</h3>
					<p>Read the API documentation</p>
					<p>
						<a href="<?php echo $url . 'documentation'; ?>" class="btn btn-primary" role="button">View documentation</a>
					</p>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="thumbnail text-center">
				<img src="<?php echo $base; ?>/img/library-128.png" />
				<div class="caption">
					<h3>Libraries</h3>
					<p>Use the API in your favorite language</p>
					<p>
						<a href="<?php echo $url . 'library'; ?>" class="btn btn-primary" role="button">Browse libraries</a>
					</p>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="thumbnail text-center">
				<img src="<?php echo $base; ?>/img/advertising-128.png" />
				<div class="caption">
					<h3>Support</h3>
					<p>See all support resources</p>
					<p>
						<a href="<?php echo $url . 'support'; ?>" class="btn btn-primary" role="button">Get support</a>
					</p>
				</div>
			</div>
		</div>

	</div>
</div>

<?php include($location . '/inc/footer.tpl'); ?>

</body>
</html>
