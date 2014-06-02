<!DOCTYPE html>
<html>
<head>
	<?php include($location . '/inc/meta.tpl'); ?>
</head>
<body>

<?php include($location . '/inc/nav.tpl'); ?>

<div class="container">
	<div class="row">
		<div class="col-md-9">
			<h4><?php if($api->getStatus() == \Fusio\Entity\Api::STATUS_DEPRECATED): ?><span class="label label-warning">Deprecated</span><?php endif; ?> <?php echo implode('|', $api->getAllowedMethods()) . ' /api' . $api->getPath(); ?></h4>
			<p><a href="<?php echo $url . 'api' . $api->getPath(); ?>"><?php echo $url . 'api' . $api->getPath(); ?></a></p>
			<div class="fusio-documentation-description"><?php echo $api->getDescription(); ?></div>
			<hr />

			<?php if($api->getView()): ?>
				<h4>View</h4>
				<p>asdasd</p>

				<hr />
			<?php endif; ?>

			<?php if($api->getModel()): ?>
				<h4>Model</h4>
				<p>asdasd</p>
				<pre><?php echo $json; ?></pre>
				<hr />
				<?php echo $html; ?>
				<hr />
				<p><small>* Field is required</small></p>
			<?php endif; ?>
		</div>
		<div class="col-md-3">
			<?php include($location . '/inc/documentation_endpoint.tpl'); ?>
		</div>
	</div>
</div>

<?php include($location . '/inc/footer.tpl'); ?>

</body>
</html>
