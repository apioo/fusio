<!DOCTYPE html>
<html>
<head>
	<?php include($location . '/inc/meta.tpl'); ?>
</head>
<body>


<?php include($location . '/inc/header.tpl'); ?>

<div class="main">
	<div id="sidebar">
		<ul>
			<li><a href="#">Home</a></li>
			<li><a href="#">Datasource</a></li>
			<li><a href="#">Api</a>
			<li><a href="#">Trigger</a></li>
			<li><a href="#">App</a></li>
			<li><a href="#">Log</a></li>
		</ul>
	</div>
	<div id="content">
		<div class="wrapper">
			<div class="container-fluid">

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
</div>

</body>
</html>
