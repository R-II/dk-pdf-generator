<?php

/**
 * This file is part of FPDI
 *
 * @package   Dinamiko\DKPDFG\Vendor\setasign\Fpdi
 * @copyright Copyright (c) 2024 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */

namespace Dinamiko\DKPDFG\Vendor\setasign\Fpdi\PdfParser\Filter;

use Dinamiko\DKPDFG\Vendor\setasign\Fpdi\PdfParser\PdfParserException;

/**
 * Exception for filters
 */
class FilterException extends PdfParserException
{
    const UNSUPPORTED_FILTER = 0x0201;

    const NOT_IMPLEMENTED = 0x0202;
}
