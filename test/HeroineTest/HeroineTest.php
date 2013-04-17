<?php

namespace HeroineTest;

use Heroine\Config;
use Heroine\Heroine;
use PHPUnit_Framework_TestCase;

class HeroineTest extends PHPUnit_Framework_TestCase
{
	protected $_heroine;
	protected $_config;
	protected $_configArray;

	public function setUp()
	{
		$this->_configArray = array(
			'aliases' => array(
				'MyAliasedClass'  => 'MyClass',
				'MyAliasedClass2' => 'MyCalledClass',
			),
			'instantiables' => array(
				'MyClass'       => 'HeroineTest\Mock\MyClass',
				'another_class' => 'HeroineTest\Mock\AnotherClass',
			),
			'callables' => array(
				'MyCalledClass' => function(Heroine $heroine) {
					$instance = new Mock\MyClass;
					$instance->myClass = $heroine->get('MyClass');
					return $instance;
				},
				'another_called_class' => function(Heroine $heroine) {
					$instance = new Mock\AnotherClass;
					$instance->anotherClass = $heroine->get('another_class');
					return $instance;
				},
			),
			'factories' => array(
				'MyFactoriedClass' => 'HeroineTest\Mock\MyClassFactory',
			),
			'initializers' => array(
				function (Heroine $heroine, $instance) {
					$instance->myProp = 'myProp';
				},
				function (Heroine $heroine, $instance) {
					$instance->anotherProp = 'anotherProp';
				},
			),
		);

		$this->_config = new Config($this->_configArray);
		$this->_heroine = new Heroine($this->_config);
	}

	public function testInstancesWithSameConfigAreEqual()
	{
		$heroine  = new Heroine($this->_config);
		$heroine2 = new Heroine($this->_configArray);

		$heroine3 = new Heroine();
		$heroine3->setConfig($this->_config);

		$this->assertEquals($heroine, $heroine2);
		$this->assertEquals($heroine->getConfig(), $heroine2->getConfig());

		$this->assertEquals($heroine, $heroine3);
		$this->assertEquals($heroine->getConfig(), $heroine3->getConfig());
	}

	public function testInstantiableCreatesCorrectClass()
	{
		$heroine = $this->_heroine;

		$this->assertTrue($heroine->get('MyClass') instanceof Mock\MyClass);
		$this->assertTrue($heroine->get('another_class') instanceof Mock\AnotherClass);
	}

	public function testCallableCreatesCorrectClass()
	{
		$heroine = $this->_heroine;

		$this->assertTrue($heroine->get('MyCalledClass') instanceof Mock\MyClass);
		$this->assertTrue($heroine->get('another_called_class') instanceof Mock\AnotherClass);
	}

	public function testFactoryCreatesCorrectClass()
	{
		$heroine = $this->_heroine;

		$this->assertTrue($heroine->get('MyFactoriedClass') instanceof Mock\MyClass);
	}

	public function testInitializersAreRun()
	{
		$heroine      = $this->_heroine;
		$myClass      = $heroine->get('MyClass');
		$anotherClass = $heroine->get('another_class');

		$this->assertTrue($myClass instanceof Mock\MyClass);
		$this->assertTrue($anotherClass instanceof Mock\AnotherClass);

		$this->assertTrue(property_exists($myClass, 'myProp'));
		$this->assertTrue(property_exists($anotherClass, 'anotherProp'));

		$this->assertEquals('myProp', $myClass->myProp);
		$this->assertEquals('anotherProp', $myClass->anotherProp);
	}

	public function testReturnedClassesOfSameAliasAreTheSame()
	{
		$heroine = $this->_heroine;

		$this->assertEquals(
			spl_object_hash($heroine->get('MyClass')),
			spl_object_hash($heroine->get('MyClass'))
		);

		$this->assertEquals(
			spl_object_hash($heroine->get('MyCalledClass')),
			spl_object_hash($heroine->get('MyCalledClass'))
		);

		$this->assertEquals(
			spl_object_hash($heroine->get('MyFactoriedClass')),
			spl_object_hash($heroine->get('MyFactoriedClass'))
		);
	}

	public function testReturnedAliasedClassesOfDifferentAliasAreTheSame()
	{
		$heroine = $this->_heroine;

		$this->assertEquals(
			spl_object_hash($heroine->get('MyAliasedClass')),
			spl_object_hash($heroine->get('MyClass'))
		);

		$this->assertEquals(
			spl_object_hash($heroine->get('MyAliasedClass2')),
			spl_object_hash($heroine->get('MyCalledClass'))
		);
	}

	/**
	 * @expectedException Heroine\Exception\ServiceDefinitionException
	 */
	public function testAddingAliasThrowsException()
	{
		$this->_heroine->addAlias('MyFactoriedClass', 'ShouldFail');
	}

	/**
	 * @expectedException Heroine\Exception\ServiceDefinitionException
	 */
	public function testAddingInstantiableThrowsException()
	{
		$this->_heroine->addInstantiable('MyFactoriedClass', 'ShouldFail');
	}

	/**
	 * @expectedException Heroine\Exception\ServiceDefinitionException
	 */
	public function testAddingCallableThrowsException()
	{
		$this->_heroine->addCallable('MyFactoriedClass', 'ShouldFail');
	}

	/**
	 * @expectedException Heroine\Exception\ServiceDefinitionException
	 */
	public function testAddingFactoryThrowsException()
	{
		$this->_heroine->addFactory('MyFactoriedClass', 'ShouldFail');
	}
}