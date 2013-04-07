<?php

namespace Heroine;

use Heroine\Exception\ServiceDefinitionException;
use Heroine\Exception\ServiceNotFoundException;

class Config
{
	const TYPE_ALIAS        = 0;
	const TYPE_INSTANTIABLE = 1;
	const TYPE_CALLABLE     = 2;
	const TYPE_FACTORY      = 3;

	protected $_aliases = array();
	protected $_instantiables = array();
	protected $_callables = array();
	protected $_factories = array();

	public function __construct($config = array())
	{
		$this->_aliases = isset($config['aliases'])
			? $config['aliases']
			: array();

		$this->_instantiables = isset($config['instantiables'])
			? $config['instantiables']
			: array();

		$this->_callables = isset($config['callables'])
			? $config['callables']
			: array();

		$this->_factories = isset($config['factories'])
			? $config['factories']
			: array();
	}

	public function exists($alias)
	{
		return array_key_exists($alias, $this->_aliases)
			OR array_key_exists($alias, $this->_instantiables)
			OR array_key_exists($alias, $this->_callables)
			OR array_key_exists($alias, $this->_factories);
	}

	public function addAlias($alias, $service)
	{
		if ($this->exists($alias))
			throw new ServiceDefinitionException('Service already defined');

		if ( ! is_string($service))
			throw new ServiceDefinitionException('Expected string value for '.$alias);

		$this->_aliases[$alias] = $service;
		return TRUE;
	}

	public function addInstantiable($alias, $service)
	{
		if ($this->exists($alias))
			throw new ServiceDefinitionException('Service already defined');

		if ( ! is_string($service))
			throw new ServiceDefinitionException('Expected string value for '.$alias);

		if ( ! class_exists($service))
			throw new ServiceDefinitionException('Cannot load class for '.$alias);

		$this->_instantiables[$alias] = $service;
	}

	public function addCallable($alias, $service)
	{
		if ($this->exists($alias))
			throw new ServiceDefinitionException('Service already defined');

		if ( ! is_callable($service))
			throw new ServiceDefinitionException('Expected callable for '.$alias);

		$this->_callables[$alias] = $service;
	}

	public function addFactory($alias, $service)
	{
		if ($this->exists($alias))
			throw new ServiceDefinitionException('Service already defined');

		if ( ! is_string($service))
			throw new ServiceDefinitionException('Expected string value for '.$alias);

		if ( ! class_exists($service))
			throw new ServiceDefinitionException('Cannot load class for '.$alias);

		$this->_factories[$alias] = $service;
	}

	public function resolveAlias($service)
	{
		if (isset($this->_aliases[$service]))
		{
			return $this->resolveService($this->_aliases[$service]);
		}

		return $service;
	}

	public function resolveService($service)
	{
		if (isset($this->_instantiables[$service]))
		{
			return array(
				'type'    => self::TYPE_INSTANTIABLE,
				'factory' => $this->_instantiables[$service],
			);
		}

		if (isset($this->_callables[$service]))
		{
			return array(
				'type'    => self::TYPE_CALLABLE,
				'factory' => $this->_callables[$service],
			);
		}

		if (isset($this->_factories[$service]))
		{
			return array(
				'type'    => self::TYPE_CALLABLE,
				'factory' => $this->_factories[$service],
			);
		}

		throw new ServiceNotFoundException;
	}
}