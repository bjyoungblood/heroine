<?php

namespace Heroine;

class Config
{
	public const TYPE_INSTANTIABLE = 0;
	public const TYPE_CALLABLE = 1;
	public const TYPE_FACTORY = 2;

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

		throw new Exception\ServiceNotFoundException;
	}
}