<?php

namespace HeroineTest\Mock;

class MyClassFactory implements \Heroine\Factory\FactoryInterface
{
	public function createService(\Heroine\Heroine $heroine, $service)
	{
		return new \HeroineTest\Mock\MyClass;
	}
}