<?php

namespace Sample\Demo\Application;

use PSX\Module\ViewAbstract;

class Index extends ViewAbstract
{
	public function onLoad()
	{
		$this->getTemplate()->assign('title', 'PSX Framework');
		$this->getTemplate()->assign('subTitle', 'Template sample ...');
	}
}
