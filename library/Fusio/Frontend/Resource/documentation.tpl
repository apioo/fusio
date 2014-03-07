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
			<h3>Documentation</h3>

			<h4>Endpoints</h4>
			<ul class="fusio-documentation">
				<?php foreach($apis as $api): ?>
				<li>
					<h4><a href="<?php echo $url . 'documentation/detail/' . $api->getId(); ?>"><?php echo implode('|', $api->getAllowedMethods()) . ' /api' . $api->getPath(); ?></a></h4>
					<div class="fusio-documentation-description"><?php echo $api->getDescription(); ?></div>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>

<?php include($location . '/inc/footer.tpl'); ?>

</body>
</html>
