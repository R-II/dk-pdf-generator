<?php

namespace Dinamiko\DKPDFG\Vendor\Mpdf\File;

interface LocalContentLoaderInterface
{

	/**
	 * @return string|null
	 */
	public function load($path);

}
