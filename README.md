# Heroine

Heroine has come to rescue you from your DI nightmares by providing a simple, dependency-free service locator and repository.

For more information on the service locator pattern, you can read the
[Wikipedia article](http://en.wikipedia.org/wiki/Service_locator_pattern) or on
[Martin Fowler's website](http://martinfowler.com/articles/injection.html#UsingAServiceLocator).

## Configuration and Usage

How and where you configure Heroine is up to you. All you need to do is construct
a new `Heroine\Heroine` object with an array or an instance of `Heroine\Config`.

A sample array configuration follows:

```php
<?php

$heroineConfig = array(
	'instantiables' => array(
		'MyClass'       => 'MyClass',
		'another_class' => 'Another\Class',
	),
	'callables' => array(
		'MyCalledClass' => function(Heroine\Heroine $heroine) {
			$instance = new MyCalledClass;
			$instance->setMyClass($heroine->get('MyClass'));
			return $instance;
		},
		'another_called_class' => function(Heroine\Heroine $heroine) {
			$instance = new Another\Called\Class;
			$instance->setAnotherClass($heroine->get('another_class'));
			return $instance;
		},
	),
	'factories' => array(
		'MyFactoryProductClass' => 'MyFactoryClass',
	),
	'initializers' => array(
		function (Heroine\Heroine $heroine, $instance) {
			if ($instance instanceof Heroine\HeroineAwareInterface)
			{
				$instance->setHeroine($heroine);
			}
		},
		function (Heroine\Heroine $heroine, $instance) {
			if ($instance instanceof InitializableInterface)
			{
				$instance->initalize();
			}
		},
	),
);

?>
```

The four top-level array keys here represent the four creation patterns that
Heroine provides.

 - **Instantiables** - these are classes that do not have any other dependencies, but that you want to be handled by Heroine. An example of this might be a model entity prototype.
 - **Callables** - these are simple factories that can use Heroine (or other resources) to create an instance of a class. This is recommended when your class has a few simple dependencies that are already managed by Heroine.
 - **Factories** - this is a full-blown factory class, which must implement Heroine\Factory\FactoryInterface. The `createService` method will be called to fetch an instance. This is recommended for objects with many dependencies or complex configuration.
 - **Initializers** - initializers are a list of functions that are run on every object created by Heroine to provide some initial state. For example, you might have a HeroineAwareInterface that provides a hook to inject Heroine into an object after it is created.