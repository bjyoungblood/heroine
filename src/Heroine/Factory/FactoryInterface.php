<?php

namespace Heroine\Factory;

use Heroine\Heroine;

interface FactoryInterface
{
	public function createService(Heroine $heroine, $service);
}