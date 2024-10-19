<?php
namespace Dinamiko\DKPDFG\Vendor\DeepCopy\TypeFilter\Spl;

use Dinamiko\DKPDFG\Vendor\DeepCopy\DeepCopy;
use Dinamiko\DKPDFG\Vendor\DeepCopy\TypeFilter\TypeFilter;

/**
 * In PHP 7.4 the storage of an ArrayObject isn't returned as
 * ReflectionProperty. So we deep copy its array copy.
 */
final class ArrayObjectFilter implements TypeFilter
{
    /**
     * @var Dinamiko\DKPDFG\Vendor\DeepCopy
     */
    private $copier;

    public function __construct($copier)
    {
        $this->copier = $copier;
    }

    /**
     * {@inheritdoc}
     */
    public function apply($arrayObject)
    {
        $clone = clone $arrayObject;
        foreach ($arrayObject->getArrayCopy() as $k => $v) {
            $clone->offsetSet($k, $this->copier->copy($v));
        }

        return $clone;
    }
}

