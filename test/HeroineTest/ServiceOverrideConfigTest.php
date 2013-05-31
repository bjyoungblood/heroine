<?php

namespace HeroineTest;

use Heroine\ServiceOverrideConfig;
use PHPUnit_Framework_TestCase;

class ServiceOverrideConfigTest extends PHPUnit_Framework_TestCase
{
	public function testEmptyConfigIsEmpty()
	{
		$config = new ServiceOverrideConfig;

		$this->assertEquals(0, count($config->getInitializers()));
		$this->assertEquals(0, count($config->getAliases()));
		$this->assertEquals(0, count($config->getInstantiables()));
		$this->assertEquals(0, count($config->getCallables()));
		$this->assertEquals(0, count($config->getFactories()));
	}

	public function testAddingInitializers()
	{
		$config = new ServiceOverrideConfig;
		$config->addInitializer(function() {});

		$this->assertEquals(1, count($config->getInitializers()));

		$config->addInitializer(function() {});
		$this->assertEquals(2, count($config->getInitializers()));
	}

	public function testAddingInstantiables()
	{
		$config = new ServiceOverrideConfig;
		$config->addInstantiable('myStdClass', 'stdClass');

		$this->assertEquals(1, count($config->getInstantiables()));

		$config->addInstantiable('myStdClass2', 'stdClass');
		$this->assertEquals(2, count($config->getInstantiables()));
	}

	public function testAddingAliases()
	{
		$config = new ServiceOverrideConfig;
		$config->addAlias('Alias', 'myStdClass');

		$this->assertEquals(1, count($config->getAliases()));
	}

	public function testDuplicateServiceNameChangesService()
	{
		$config = new ServiceOverrideConfig;
		$config->addAlias('MyName', 'myStdClass');
		$config->addInstantiable('MyName', 'stdClass');

		$this->assertFalse(array_key_exists('MyName', $config->getAliases()));
		$this->assertTrue(array_key_exists('MyName', $config->getInstantiables()));
	}

	public function testResolveAlias()
	{
		$config = new ServiceOverrideConfig;
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
		$config = new ServiceOverrideConfig;
		$config->addAlias('MyAlias', 'myStdClass');
		$config->resolveService('MyAlias');
	}
}