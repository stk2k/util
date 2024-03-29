<?php
declare(strict_types=1);

namespace stk2k\util;

interface EqualableInterface
{
    /**
     *  Check whether specified item is same to this object
     *
     * @param mixed $other
     *
     * @return bool
     */
    public function equals( $other ) : bool;
}

