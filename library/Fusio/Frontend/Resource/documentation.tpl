<!DOCTYPE html>
<html>
<head>
	<title>PSX Sample</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="generator" content="psx" />
	<link rel="icon" href="<?php echo $base; ?>/img/favicon.ico" type="image/x-icon" />
	<link href="<?php echo $base; ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo $base; ?>/css/default.css" rel="stylesheet" media="screen" />
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="<?php echo $base; ?>/js/bootstrap.min.js"></script>
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
