<?php

namespace Dinamiko\DKPDFG\Vendor\Mpdf\PsrLogAwareTrait;

use Dinamiko\DKPDFG\Vendor\Psr\Log\LoggerInterface;

trait PsrLogAwareTrait
{

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Psr\Log\LoggerInterface
	 */
	protected $logger;

	public function setLogger(LoggerInterface $logger): void
	{
		$this->logger = $logger;
	}

}
