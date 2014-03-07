
<nav class="navbar navbar-default navbar-fixed-top fusio-nav" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#fusio-navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="http://fusio-project.org">Fusio</a>
		</div>
		<div class="collapse navbar-collapse" id="fusio-navbar-collapse">
			<ul class="nav navbar-nav">
				<li>
					<a href="<?php echo $url; ?>home">Home</a>
				</li>
				<li>
					<a href="<?php echo $url; ?>documentation">Documentation</a>
				</li>
				<li>
					<a href="<?php echo $url; ?>library">Libraries</a>
				</li>
				<li>
					<a href="<?php echo $url; ?>support">Support</a>
				</li>
				<?php if(!$user->isAuthenticated()): ?>
				<li>
					<a href="<?php echo $url; ?>register">Register</a>
				</li>
				<?php else: ?>
				<li>
					<a href="<?php echo $url; ?>application">Application</a>
				</li>
				<?php endif; ?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php if($user->isAuthenticated()): ?>
				<li>
					<span>Logged in <?php echo $user->getName(); ?></span>
				</li>
				<?php endif; ?>
				<li>
					<?php if(!$user->isAuthenticated()): ?>
						<a href="<?php echo $url; ?>login">Login</a>
					<?php else: ?>
						<a href="<?php echo $url; ?>logout">Logout</a>
					<?php endif; ?>
				</li>
			</ul>
		</div>
	</div>
</nav>
