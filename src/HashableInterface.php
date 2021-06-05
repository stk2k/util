<?php
declare(strict_types=1);

namespace stk2k\Util;

interface HashableInterface
{
    /**
     *  make hash code of this object
     *
     * @return string   unique string of this object
     */
    public function hash() : string;
}

