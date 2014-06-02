
<ul class="fusio-documentation-endpoints">
	<?php foreach($apis as $api): ?>
		<?php if($api->getStatus() == \Fusio\Entity\Api::STATUS_DEPRECATED): ?>
			<li class="fusio-api-deprecated"><a href="<?php echo $url . 'documentation/detail/' . $api->getId(); ?>"><?php echo '/api' . $api->getPath(); ?></a></li>
		<?php else: ?>
			<li><a href="<?php echo $url . 'documentation/detail/' . $api->getId(); ?>"><?php echo '/api' . $api->getPath(); ?></a></li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
