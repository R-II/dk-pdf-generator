<?php

namespace Dinamiko\DKPDFG\Vendor\Mpdf\Writer;

use Dinamiko\DKPDFG\Vendor\Mpdf\Strict;
use Dinamiko\DKPDFG\Vendor\Mpdf\Mpdf;

final class JavaScriptWriter
{

	use Strict;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Mpdf
	 */
	private $mpdf;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Writer\BaseWriter
	 */
	private $writer;

	public function __construct($mpdf, BaseWriter $writer)
	{
		$this->mpdf = $mpdf;
		$this->writer = $writer;
	}

	public function writeJavascript() // _putjavascript
	{
		$this->writer->object();
		$this->mpdf->n_js = $this->mpdf->n;
		$this->writer->write('<<');
		$this->writer->write('/Names [(EmbeddedJS) ' . (1 + $this->mpdf->n) . ' 0 R ]');
		$this->writer->write('>>');
		$this->writer->write('endobj');

		$this->writer->object();
		$this->writer->write('<<');
		$this->writer->write('/S /JavaScript');
		$this->writer->write('/JS ' . $this->writer->string($this->mpdf->js));
		$this->writer->write('>>');
		$this->writer->write('endobj');
	}

}
