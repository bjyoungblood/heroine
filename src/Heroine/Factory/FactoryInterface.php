<?php

namespace Heroine\Factory;

interface FactoryInterface
{
	public function createService(Heroine $heroine, $service);
}