<?php

namespace Dinamiko\DKPDFG\Vendor\Mpdf\Writer;

use Dinamiko\DKPDFG\Vendor\Mpdf\Strict;
use Dinamiko\DKPDFG\Vendor\Mpdf\Mpdf;
use Dinamiko\DKPDFG\Vendor\Mpdf\PsrLogAwareTrait\PsrLogAwareTrait;
use Dinamiko\DKPDFG\Vendor\Psr\Log\LoggerInterface;

final class ResourceWriter implements \Dinamiko\DKPDFG\Vendor\Psr\Log\LoggerAwareInterface
{

	use Strict;
	use PsrLogAwareTrait;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Mpdf
	 */
	private $mpdf;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Writer\BaseWriter
	 */
	private $writer;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Writer\ColorWriter
	 */
	private $colorWriter;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Writer\FontWriter
	 */
	private $fontWriter;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Writer\ImageWriter
	 */
	private $imageWriter;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Writer\FormWriter
	 */
	private $formWriter;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Writer\OptionalContentWriter
	 */
	private $optionalContentWriter;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Writer\BackgroundWriter
	 */
	private $backgroundWriter;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Writer\BookmarkWriter
	 */
	private $bookmarkWriter;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Writer\MetadataWriter
	 */
	private $metadataWriter;

	/**
	 * @var \Dinamiko\DKPDFG\Vendor\Mpdf\Writer\JavaScriptWriter
	 */
	private $javaScriptWriter;

	public function __construct(
		$mpdf,
		BaseWriter $writer,
		ColorWriter $colorWriter,
		FontWriter $fontWriter,
		ImageWriter $imageWriter,
		FormWriter $formWriter,
		OptionalContentWriter $optionalContentWriter,
		BackgroundWriter $backgroundWriter,
		BookmarkWriter $bookmarkWriter,
		MetadataWriter $metadataWriter,
		JavaScriptWriter $javaScriptWriter,
		LoggerInterface $logger
	) {
		$this->mpdf = $mpdf;
		$this->writer = $writer;
		$this->colorWriter = $colorWriter;
		$this->fontWriter = $fontWriter;
		$this->imageWriter = $imageWriter;
		$this->formWriter = $formWriter;
		$this->optionalContentWriter = $optionalContentWriter;
		$this->backgroundWriter = $backgroundWriter;
		$this->bookmarkWriter = $bookmarkWriter;
		$this->metadataWriter = $metadataWriter;
		$this->javaScriptWriter = $javaScriptWriter;
		$this->logger = $logger;
	}

	public function writeResources() // _putresources
	{
		if ($this->mpdf->hasOC || count($this->mpdf->layers)) {
			$this->optionalContentWriter->writeOptionalContentGroups();
		}

		$this->mpdf->_putextgstates();
		$this->colorWriter->writeSpotColors();

		// @log Compiling Fonts

		$this->fontWriter->writeFonts();

		// @log Compiling Images

		$this->imageWriter->writeImages();

		$this->formWriter->writeFormObjects();

		$this->mpdf->writeImportedPagesAndResolvedObjects();

		$this->backgroundWriter->writeShaders();
		$this->backgroundWriter->writePatterns();

		// Resource dictionary
		$this->mpdf->offsets[2] = strlen($this->mpdf->buffer);
		$this->writer->write('2 0 obj');
		$this->writer->write('<</ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');

		$this->writer->write('/Font <<');
		foreach ($this->mpdf->fonts as $font) {
			if (isset($font['type']) && $font['type'] === 'TTF' && !$font['used']) {
				continue;
			}
			if (isset($font['type']) && $font['type'] === 'TTF' && ($font['sip'] || $font['smp'])) {
				foreach ($font['n'] as $k => $fid) {
					$this->writer->write('/F' . $font['subsetfontids'][$k] . ' ' . $font['n'][$k] . ' 0 R');
				}
			} else {
				$this->writer->write('/F' . $font['i'] . ' ' . $font['n'] . ' 0 R');
			}
		}
		$this->writer->write('>>');

		if (count($this->mpdf->spotColors)) {
			$this->writer->write('/ColorSpace <<');
			foreach ($this->mpdf->spotColors as $color) {
				$this->writer->write('/CS' . $color['i'] . ' ' . $color['n'] . ' 0 R');
			}
			$this->writer->write('>>');
		}

		if (count($this->mpdf->extgstates)) {
			$this->writer->write('/ExtGState <<');
			foreach ($this->mpdf->extgstates as $k => $extgstate) {
				if (isset($extgstate['trans'])) {
					$this->writer->write('/' . $extgstate['trans'] . ' ' . $extgstate['n'] . ' 0 R');
				} else {
					$this->writer->write('/GS' . $k . ' ' . $extgstate['n'] . ' 0 R');
				}
			}
			$this->writer->write('>>');
		}

		/* -- BACKGROUNDS -- */
		if (($this->mpdf->gradients !== null && (count($this->mpdf->gradients) > 0))) { // mPDF 5.7.3

			$this->writer->write('/Shading <<');

			foreach ($this->mpdf->gradients as $id => $grad) {
				$this->writer->write('/Sh' . $id . ' ' . $grad['id'] . ' 0 R');
			}

			$this->writer->write('>>');

			/*
			  // ??? Not needed !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			  $this->writer->write('/Pattern <<');
			  foreach ($this->mpdf->gradients as $id => $grad) {
			  $this->writer->write('/P'.$id.' '.$grad['pattern'].' 0 R');
			  }
			  $this->writer->write('>>');
			 */
		}
		/* -- END BACKGROUNDS -- */

		if (count($this->mpdf->images) || count($this->mpdf->formobjects) || count($this->mpdf->getImportedPages())) {
			$this->writer->write('/XObject <<');
			foreach ($this->mpdf->images as $image) {
				$this->writer->write('/I' . $image['i'] . ' ' . $image['n'] . ' 0 R');
			}
			foreach ($this->mpdf->formobjects as $formobject) {
				$this->writer->write('/FO' . $formobject['i'] . ' ' . $formobject['n'] . ' 0 R');
			}
			/* -- IMPORTS -- */
			foreach ($this->mpdf->getImportedPages() as $pageData) {
				$this->writer->write('/' . $pageData['id'] . ' ' . $pageData['objectNumber'] . ' 0 R');
			}
			/* -- END IMPORTS -- */
			$this->writer->write('>>');
		}

		/* -- BACKGROUNDS -- */

		if (count($this->mpdf->patterns)) {
			$this->writer->write('/Pattern <<');
			foreach ($this->mpdf->patterns as $k => $patterns) {
				$this->writer->write('/P' . $k . ' ' . $patterns['n'] . ' 0 R');
			}
			$this->writer->write('>>');
		}
		/* -- END BACKGROUNDS -- */

		if ($this->mpdf->hasOC || count($this->mpdf->layers)) {
			$this->writer->write('/Properties <<');
			if ($this->mpdf->hasOC) {
				$this->writer->write('/OC1 ' . $this->mpdf->n_ocg_print . ' 0 R /OC2 ' . $this->mpdf->n_ocg_view . ' 0 R /OC3 ' . $this->mpdf->n_ocg_hidden . ' 0 R ');
			}
			if (count($this->mpdf->layers)) {
				foreach ($this->mpdf->layers as $id => $layer) {
					$this->writer->write('/ZI' . $id . ' ' . $layer['n'] . ' 0 R');
				}
			}
			$this->writer->write('>>');
		}

		$this->writer->write('>>');
		$this->writer->write('endobj'); // end resource dictionary

		$this->bookmarkWriter->writeBookmarks();

		if (!empty($this->mpdf->js)) {
			$this->javaScriptWriter->writeJavascript();
		}

		if ($this->mpdf->encrypted) {
			$this->writer->object();
			$this->mpdf->enc_obj_id = $this->mpdf->n;
			$this->writer->write('<<');
			$this->metadataWriter->writeEncryption();
			$this->writer->write('>>');
			$this->writer->write('endobj');
		}
	}
}
