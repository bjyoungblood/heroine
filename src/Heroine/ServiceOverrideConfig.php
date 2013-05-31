<?php

namespace Heroine;

use Heroine\Exception\ServiceDefinitionException;
use Heroine\Exception\ServiceNotFoundException;

class ServiceOverrideConfig extends Config
{
	public function addAlias($alias, $service)
	{
		if ($this->exists($alias))
			$this->_remove($alias);

		return parent::addAlias($alias, $service);
	}

	public function addInstantiable($alias, $service)
	{
		if ($this->exists($alias))
			$this->_remove($alias);

		return parent::addInstantiable($alias, $service);
	}

	public function addCallable($alias, $service)
	{
		if ($this->exists($alias))
			$this->_remove($alias);

		return parent::addCallable($alias, $service);
	}

	public function addFactory($alias, $service)
	{
		if ($this->exists($alias))
			$this->_remove($alias);

		return parent::addFactory($alias, $service);
	}

	protected function _remove($alias)
	{
		if (array_key_exists($alias, $this->_aliases))
		{
			unset($this->_aliases[$alias]);
		}

		if (array_key_exists($alias, $this->_instantiables))
		{
			unset($this->_instantiables[$alias]);
		}

		if (array_key_exists($alias, $this->_callables))
		{
			unset($this->_callables[$alias]);
		}

		if (array_key_exists($alias, $this->_factories))
		{
			unset($this->_factories[$alias]);
		}
	}
}