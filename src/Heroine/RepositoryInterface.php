<?php

namespace Heroine;

interface RepositoryInterface
{
	/**
	 * Returns an object based on its string key
	 * @param  string $service
	 * @return object
	 */
	public function get($service);

	/**
	 * Sets an object
	 * @param string $service service name of the object
	 * @param object $object
	 */
	public function set($service, $object);

	/**
	 * Whether the repository has a service set
	 * @param  string  $service service name
	 * @return boolean
	 */
	public function has($service);
}