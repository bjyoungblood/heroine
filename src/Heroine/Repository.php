<?php

namespace Heroine;

class Repository implements RepositoryInterface
{
	protected $_repository = array();

	public function get($service)
	{
		return $this->_repository[$service] ?: FALSE;
	}

	public function set($service, $object)
	{
		$this->_repository[$service] = $object;
		return $this;
	}

	public function has($service)
	{
		if ( ! isset($this->_repository[$service]))
			return FALSE;

		return $this->_repository[$service] ? TRUE : FALSE;
	}
}