<?php

namespace Dinamiko\DKPDFG\Vendor\DeepCopy;

use function function_exists;

if (false === function_exists('Dinamiko\DKPDFG\Vendor\DeepCopy\deep_copy')) {
    /**
     * Deep copies the given value.
     *
     * @param mixed $value
     * @param bool  $useCloneMethod
     *
     * @return mixed
     */
    function deep_copy($value, $useCloneMethod = false)
    {
        return (new DeepCopy($useCloneMethod))->copy($value);
    }
}
