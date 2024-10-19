<?php

namespace Dinamiko\DKPDFG\Vendor\Mpdf\File;

class LocalContentLoader implements \Dinamiko\DKPDFG\Vendor\Mpdf\File\LocalContentLoaderInterface
{

	public function load($path)
	{
		return file_get_contents($path);
	}

}
