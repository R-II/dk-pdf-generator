<?php

namespace Dinamiko\DKPDFG\Vendor\DeepCopy\Matcher\Doctrine;

use Dinamiko\DKPDFG\Vendor\DeepCopy\Matcher\Matcher;
use Doctrine\Persistence\Proxy;

/**
 * @final
 */
class DoctrineProxyMatcher implements Matcher
{
    /**
     * Matches a Doctrine Proxy class.
     *
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        return $object instanceof Proxy;
    }
}
