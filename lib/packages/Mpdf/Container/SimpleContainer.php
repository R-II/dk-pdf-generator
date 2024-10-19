<?php

namespace Dinamiko\DKPDFG\Vendor\Mpdf\Container;

class SimpleContainer implements \Dinamiko\DKPDFG\Vendor\Mpdf\Container\ContainerInterface
{

	private $services;

	public function __construct(array $services)
	{
		$this->services = $services;
	}

	public function get($id)
	{
		if (!$this->has($id)) {
			throw new \Dinamiko\DKPDFG\Vendor\Mpdf\Container\NotFoundException(sprintf('Unable to find service of key "%s"', $id));
		}

		return $this->services[$id];
	}

	public function has($id)
	{
		return isset($this->services[$id]);
	}

	public function getServices()
	{
		return $this->services;
	}

}
