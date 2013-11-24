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
			<h3>Welcome,</h3>
			<p>This is an PSX sample application. It should help to bootstrap a
			project by providing all needed files and some examples. You can 
			install this sample through composer:</p>

			<pre>php composer.phar create-project psx/sample . dev-master</pre>

			<p>More informations about PSX at</p>
			<dl>
				<dt>Website</dt>
				<dd><a href="http://phpsx.org">http://phpsx.org</a></dd>
				<dt>Github</dt>
				<dd><a href="https://github.com/k42b3/psx">https://github.com/k42b3/psx</a></dd>
			</dl>
		</div>
	</div>
</div>

<?php include($location . '/inc/footer.tpl'); ?>

</body>
</html>
