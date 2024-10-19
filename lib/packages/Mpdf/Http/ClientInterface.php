<?php

namespace Dinamiko\DKPDFG\Vendor\Mpdf\Http;

use Dinamiko\DKPDFG\Vendor\Psr\Http\Message\RequestInterface;

interface ClientInterface
{

	public function sendRequest(RequestInterface $request);

}
