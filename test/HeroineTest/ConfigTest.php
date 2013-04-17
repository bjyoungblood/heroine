<?php

namespace HeroineTest;

use Heroine\Config;
use PHPUnit_Framework_TestCase;

class ConfigTest extends PHPUnit_Framework_TestCase
{
	public function testEmptyConfigIsEmpty()
	{
		$config = new Config;

		$this->assertEquals(0, count($config->getInitializers()));
		$this->assertEquals(0, count($config->getAliases()));
		$this->assertEquals(0, count($config->getInstantiables()));
		$this->assertEquals(0, count($config->getCallables()));
		$this->assertEquals(0, count($config->getFactories()));
	}

	public function testAddingInitializers()
	{
		$config = new Config;
		$config->addInitializer(function() {});

		$this->assertEquals(1, count($config->getInitializers()));

		$config->addInitializer(function() {});
		$this->assertEquals(2, count($config->getInitializers()));
	}

	public function testAddingInstantiables()
	{
		$config = new Config;
		$config->addInstantiable('myStdClass', 'stdClass');

		$this->assertEquals(1, count($config->getInstantiables()));

		$config->addInstantiable('myStdClass2', 'stdClass');
		$this->assertEquals(2, count($config->getInstantiables()));
	}

	public function testAddingAliases()
	{
		$config = new Config;
		$config->addAlias('Alias', 'myStdClass');

		$this->assertEquals(1, count($config->getAliases()));
	}

	/**
	 * @expectedException Heroine\Exception\ServiceDefinitionException
	 */
	public function testDuplicateServiceNameRaisesException()
	{
		$config = new Config;
		$config->addAlias('MyName', 'myStdClass');
		$config->addInstantiable('MyName', 'stdClass');
	}

	public function testResolveAlias()
	{
		$config = new Config;
		$config->addAlias('MyAlias', 'myStdClass');
		$config->addInstantiable('myStdClass', 'stdClass');

		$result = $config->resolveAlias('MyAlias');
		$this->assertEquals('myStdClass', $result);
	}

	/**
	 * @expectedException Heroine\Exception\ServiceNotFoundException
	 */
	public function testResolveServiceThrowsExceptionOnAlias()
	{
		$config = new Config;
		$config->addAlias('MyAlias', 'myStdClass');
		$config->resolveService('MyAlias');
	}
}