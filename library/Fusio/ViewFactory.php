<?php

namespace Fusio;

class ViewFactory
{
	protected $views = array();

	public function registerView(ViewInterface $view)
	{
		$this->views[] = $view;
	}

	public function factory($type)
	{
		foreach($this->views as $view)
		{
			if($view->getType() == $type)
			{
				return $view;
			}
		}
	}
}
