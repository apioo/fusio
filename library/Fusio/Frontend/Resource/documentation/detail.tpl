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
			<ul class="fusio-documentation">
				<li>
					<h4><?php echo implode('|', $api->getAllowedMethods()) . ' /api' . $api->getPath(); ?></h4>
					<p><a href="<?php echo $url . 'api' . $api->getPath(); ?>"><?php echo $url . 'api' . $api->getPath(); ?></a></p>
					<div class="fusio-documentation-description"><?php echo $api->getDescription(); ?></div>
				</li>
			</ul>

			<?php foreach($api->getAllowedMethods() as $method): ?>
			<h4><?php echo $method; ?> Parameters</h4>
			<div class="fusio-documentation-parameter">
				<table class="table">
				<colgroup>
					<col width="220" />
					<col width="140" />
					<col width="*" />
				</colgroup>
				<thead>
				<tr>
					<th>Key</th>
					<th>Type</th>
					<th>Description</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($api->getFieldsByType($method) as $field): ?>
				<tr>
					<td><?php echo '<code>' . $field->getName() . '</code> ' . ($field->isRequired() ? '' : '<small class="text-muted">(optional)</small>'); ?></td>
					<td><?php echo $field->getObjectName(); ?></td>
					<td><?php echo $field->getDescription(); ?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
				</table>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<?php include($location . '/inc/footer.tpl'); ?>

</body>
</html>
